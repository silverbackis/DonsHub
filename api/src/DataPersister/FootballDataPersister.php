<?php

namespace App\DataPersister;

use App\Entity\Match;
use App\Entity\MatchLeague;
use App\Entity\MatchLeagueTeam;
use App\Repository\MatchLeagueTeamRepository;
use App\Repository\MatchRepository;
use DateTime;
use DateTimeZone;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\NameConverter\MetadataAwareNameConverter;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class FootballDataPersister
{
    private $entityManager;
    private $matchRepository;
    private $teamRepository;
    private $serializer;
    private $apiTimeZone;
    private $appTimeZone;
    public static $PIDFile = '/tmp/PID/UMM';

    public function __construct(
        EntityManagerInterface $entityManager,
        MatchRepository $matchRepository,
        MatchLeagueTeamRepository $teamRepository
    )
    {
        $this->entityManager = $entityManager;
        $this->matchRepository = $matchRepository;
        $this->teamRepository = $teamRepository;
        $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));
        $metadataAwareNameConverter = new MetadataAwareNameConverter($classMetadataFactory);
        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer($classMetadataFactory, $metadataAwareNameConverter)];
        $this->serializer = new Serializer($normalizers, $encoders);
        $this->apiTimeZone = new DateTimeZone('Europe/Berlin');
        $this->appTimeZone = new DateTimeZone('UTC');
    }

    /**
     * @param $matchesArray
     * @throws ExceptionInterface
     * @throws Exception
     */
    public function persistMatchData($matchesArray): void
    {
        foreach ($matchesArray as $matchData) {
            $matchDateTime = new DateTime($matchData['match_date'] . ' ' . ($matchData['match_time'] ?: '00:00') . ':00', $this->apiTimeZone);
            $matchDateTime->setTimezone($this->appTimeZone);
            $matchData['match_date_time'] = $matchDateTime;

            /** @var Match $match */
            $match = $this->serializer->denormalize($matchData, Match::class, 'json');
            $existingMatch = $this->matchRepository->findOneBy(
                [
                    'matchId' => $match->getMatchId()
                ]
            );
            if ($existingMatch && $existingMatch->isMatchSame($match)) {
                $existingMatch->updateFromMatch($match);
                $this->entityManager->persist($existingMatch);
                continue;
            }
            $this->entityManager->persist($match);
        }
        $this->entityManager->flush();
    }

    /**
     * @param MatchLeague $league
     * @param array $responseData
     * @throws ExceptionInterface
     */
    public function persistLeagueTeamData(MatchLeague $league, array $responseData): void
    {
        foreach ($responseData as $leagueTeamData) {
            /** @var MatchLeagueTeam $team */
            $team = $this->serializer->denormalize($leagueTeamData, MatchLeagueTeam::class, 'json');

            // override stupid names from API
            // Have used SerializedName annotation for some, but did not want it changing the output from our API really...
            // This is pretty hacky...
            $team->setOverallGamesPlayed($leagueTeamData['overall_league_payed']);
            $team->setOverallPoints($leagueTeamData['overall_league_PTS']);

            $existingTeam = $this->teamRepository->findOneBy(
                [
                    'matchLeague' => $league,
                    'teamName' => $team->getTeamName()
                ]
            );

            if ($existingTeam) {
                $existingTeam->setOverallLeaguePosition($team->getOverallLeaguePosition());
                $existingTeam->setOverallGoalsFor($team->getOverallGoalsFor());
                $existingTeam->setOverallGamesPlayed($team->getOverallGamesPlayed());
            } else {
                $team->setMatchLeague($league);
                $this->entityManager->persist($team);
            }
        }
        $this->entityManager->flush();
    }
}
