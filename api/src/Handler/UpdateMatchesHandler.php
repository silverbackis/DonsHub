<?php

namespace App\Handler;

use App\DataProvider\FootballDataProvider;
use App\Entity\Match;
use App\Entity\MatchLeague;
use App\Entity\MatchLeagueTeam;
use App\Exception\FootballApiException;
use App\Message\UpdateMatchMessage;
use App\Repository\MatchLeagueRepository;
use App\Repository\MatchLeagueTeamRepository;
use App\Repository\MatchRepository;
use DateTime;
use DateTimeZone;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Stopwatch\Stopwatch;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class UpdateMatchesHandler implements MessageHandlerInterface
{
    private $dataProvider;
    private $entityManager;
    private $matchRepository;
    private $leagueRepository;
    private $teamRepository;
    private $serializer;
    private $apiTimeZone;
    private $utcTimeZone;
    private $messageBus;

    private static $dateFormat = 'Y-m-d';

    public function __construct(
        FootballDataProvider $dataProvider,
        EntityManagerInterface $entityManager,
        MatchRepository $matchRepository,
        MatchLeagueRepository $leagueRepository,
        MatchLeagueTeamRepository $teamRepository,
        MessageBusInterface $messageBus
    )
    {
        $this->dataProvider = $dataProvider;
        $this->entityManager = $entityManager;
        $this->matchRepository = $matchRepository;
        $this->leagueRepository = $leagueRepository;
        $this->teamRepository = $teamRepository;
        $this->messageBus = $messageBus;
        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $this->serializer = new Serializer($normalizers, $encoders);
        $this->apiTimeZone = new DateTimeZone('Europe/Berlin');
        $this->utcTimeZone = new DateTimeZone('Europe/London');
    }

    /**
     * @param Match $currentMatch
     * @return void
     * @throws ClientExceptionInterface
     * @throws ExceptionInterface
     * @throws FootballApiException
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    private function fetchRelatedMatches(Match $currentMatch): void
    {
        $matchDateTime = $currentMatch->getMatchDateTime();
        if (!$matchDateTime) {
            throw new FootballApiException('Match date time does not exist');
        }
        $response = $this->dataProvider->request('get_events', [
            'from' => $matchDateTime->setTime(0,0)->format(self::$dateFormat),
            'to' => $matchDateTime->setTime(23,59, 59)->format(self::$dateFormat),
            'league_id' => $currentMatch->getLeagueId()
        ]);

        $responseArray = $this->getResponseArray($response);
        $this->processMatchData($responseArray);
        $this->fetchRelatedLeague($currentMatch);
    }

    /**
     * @param Match $currentMatch
     * @throws ClientExceptionInterface
     * @throws ExceptionInterface
     * @throws FootballApiException
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    private function fetchRelatedLeague(Match $currentMatch): void
    {
        $league = $this->leagueRepository->findOneBy([
            'match' => $currentMatch
        ]);
        if (!$league) {
            $league = new MatchLeague();
            $league->setMatch($currentMatch);
            $this->entityManager->persist($league);
            $this->entityManager->flush();
            $this->fetchRelatedLeague($currentMatch);
            return;
        }
        $response = $this->dataProvider->request('get_standings', [
            'league_id' => $currentMatch->getLeagueId()
        ]);
        if ($response->getStatusCode() !== 200) {
            // may be a friendly...
            return;
        }
        $responseArray = $this->getResponseArray($response);
        foreach ($responseArray as $leagueTeamData) {
            /** @var MatchLeagueTeam $team */
            $team = $this->serializer->denormalize($leagueTeamData, MatchLeagueTeam::class, 'json');
            $existingTeam = $this->teamRepository->findOneBy(
                [
                    'matchLeague' => $league,
                    'teamName' => $team->getTeamName()
                ]
            );
            if ($existingTeam) {
                $existingTeam->setOverallLeaguePosition($team->getOverallLeaguePosition());
            } else {
                $team->setMatchLeague($league);
                $this->entityManager->persist($team);
            }
        }
    }

    /**
     * @return void
     * @throws ClientExceptionInterface
     * @throws ExceptionInterface
     * @throws FootballApiException
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    private function fetchMatchFixtures(): void
    {
        $fromDate = new DateTime();
        $toDate = (new DateTime())->modify('+6 months');
        $response = $this->dataProvider->request('get_events', [
            'from' => $fromDate->format(self::$dateFormat),
            'to' => $toDate->format(self::$dateFormat),
            'team_id' => FootballDataProvider::$teamId
        ]);

        $responseArray = $this->getResponseArray($response);
        $this->processMatchData($responseArray);
    }

    /**
     * @param ResponseInterface $response
     * @return array
     * @throws ClientExceptionInterface
     * @throws FootballApiException
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    private function getResponseArray(ResponseInterface $response): array
    {
        if ($response->getStatusCode() !== 200) {
            throw new FootballApiException('Error: Status code: ' . $response->getStatusCode());
        }
        $content = $response->getContent();

        return json_decode($content, true);
    }

    /**
     * @param $matchesArray
     * @throws ExceptionInterface
     */
    private function processMatchData($matchesArray): void
    {
        foreach ($matchesArray as $matchData) {
            $matchDateTime = new DateTime($matchData['match_date'] . ' ' . $matchData['match_time'] . ':00', $this->apiTimeZone);
            $matchDateTime->setTimezone($this->utcTimeZone);
            $matchData['match_date_time'] = $matchDateTime;

            /** @var Match $match */
            $match = $this->serializer->denormalize($matchData, Match::class, 'json');
            $existingMatch = $this->matchRepository->findOneBy(
                [
                    'matchId' => $match->getMatchId()
                ]
            );
            if ($existingMatch === $match) {
                return;
            }
            if ($existingMatch) {
                $this->entityManager->remove($existingMatch);
            }
            $this->entityManager->persist($match);
        }
        $this->entityManager->flush();
    }

    /**
     * @param UpdateMatchMessage $message
     * @throws ClientExceptionInterface
     * @throws ExceptionInterface
     * @throws FootballApiException
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function __invoke(UpdateMatchMessage $message)
    {
        $refreshDuration = 20;
        $stopwatch = new Stopwatch();
        $stopwatch->start('footballApiRequest');

        $currentMatch = $this->matchRepository->findCurrent();
        $isMatchOngoing = $currentMatch && $currentMatch->isGatesOpen() && $currentMatch->getMatchDateTime();
        if ($isMatchOngoing) {
            $this->fetchRelatedMatches($currentMatch);
        } else {
            $this->fetchMatchFixtures();
        }

        $event = $stopwatch->stop('footballApiRequest');
        $ms = $event->getDuration();
        $seconds = ceil($ms/1000);
        $sleepTime = $refreshDuration-$seconds;
        if ($sleepTime > 0) {
            sleep($sleepTime);
        }
        $this->messageBus->dispatch($message);
    }
}
