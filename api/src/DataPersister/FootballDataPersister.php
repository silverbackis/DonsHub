<?php

namespace App\DataPersister;

use App\Entity\Match;
use App\Entity\MatchLeague;
use App\Entity\MatchLeagueTeam;
use App\Repository\MatchLeagueTeamRepository;
use App\Repository\MatchRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class FootballDataPersister
{
    private $entityManager;
    private $matchRepository;
    private $teamRepository;
    private $serializer;
    private $apiTimeZone;
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
        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $this->serializer = new Serializer($normalizers, $encoders);
    }

    /**
     * @param $matchesArray
     * @throws ExceptionInterface
     * @throws Exception
     */
    public function persistMatchData($matchesArray): void
    {
        foreach ($matchesArray as $matchData) {
            $matchDateTime = new DateTime($matchData['match_date'] . ' ' . $matchData['match_time'] . ':00', $this->apiTimeZone);
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
     * @param array $leagueTeamData
     * @throws ExceptionInterface
     */
    public function persistLeagueTeamData(MatchLeague $league, array $leagueTeamData): void
    {
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
            $existingTeam->setGoalDifference($team->getGoalDifference());
            $existingTeam->setGamesPlayed($team->getGamesPlayed());
        } else {
            $team->setMatchLeague($league);
            $this->entityManager->persist($team);
        }
    }
}
