<?php

namespace App\Handler;

use App\DataPersister\FootballDataPersister;
use App\DataProvider\FootballDataProvider;
use App\Entity\Match;
use App\Exception\FootballApiException;
use App\Exception\UpdateMatchesException;
use App\Message\UpdateMatchMessage;
use App\Repository\MatchLeagueRepository;
use App\Repository\MatchRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class UpdateMatchesHandler implements MessageHandlerInterface
{
    private $dataProvider;
    private $dataPersister;
    private $matchRepository;
    private $leagueRepository;
    private $messageBus;
    public static $PIDFile = '/tmp/PID/UMM';

    public function __construct(
        FootballDataProvider $dataProvider,
        FootballDataPersister $dataPersister,
        MatchRepository $matchRepository,
        MatchLeagueRepository $leagueRepository,
        MessageBusInterface $messageBus
    )
    {
        $this->dataProvider = $dataProvider;
        $this->dataPersister = $dataPersister;
        $this->matchRepository = $matchRepository;
        $this->leagueRepository = $leagueRepository;
        $this->messageBus = $messageBus;
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
    private function processRelatedMatches(Match $currentMatch): void
    {
        $responseData = $this->dataProvider->fetchLeagueEvents($currentMatch);
        $this->dataPersister->persistMatchData($responseData);
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
    private function processUpcomingFixtures(): void
    {
        $responseData = $this->dataProvider->fetchUpcomingEvents();
        $this->dataPersister->persistMatchData($responseData);
    }

    /**
     * @param Match $currentMatch
     * @throws ClientExceptionInterface
     * @throws ExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     * @throws ORMException
     * @throws OptimisticLockException
     */
    private function processRelatedLeague(Match $currentMatch): void
    {
        try {
            $responseData = $this->dataProvider->fetchLeagueByMatch($currentMatch);
        } catch (FootballApiException $e) {
            // may be a friendly...
            return;
        }

        $league = $this->leagueRepository->findOneByMatch($currentMatch);
        $this->dataPersister->persistLeagueTeamData($league, $responseData);
    }

    private function getActiveMatch(?Match $match): ?Match
    {
        return ($match && $match->isGatesOpen()) ? $match : null;
    }

    private function getNextSleepTime(?Match $match)
    {
        $minimumRefreshTime = 20; // 20 seconds to prevent exceeding limits of 1 per 20 seconds to each endpoint
        if ($this->getActiveMatch($match)) {
            return $minimumRefreshTime;
        }
        $refreshDuration = 60 * 15; // 15 minutes
        if ($match) {
            $gatesOpenSeconds = $match->getSecondsUntilGatesOpen();
            if ($gatesOpenSeconds < $refreshDuration) {
                $refreshDuration = max($gatesOpenSeconds, $minimumRefreshTime);
            }
        }
        return $refreshDuration;
    }

    // The message we are looping with should have the same timestamp as the one that was started initially
    // This will ensure we do not have multiple processes running and an increase in API calls
    // When the command is called to create the first message, it will update the PID with a new timestamp
    // We validate the message we receive in the handler is the latest looping message
    /**
     * @param UpdateMatchMessage $message
     * @throws UpdateMatchesException
     */
    private function validateMessage(UpdateMatchMessage $message): void
    {
        $PID = file_get_contents(self::$PIDFile);
        $messageTimestamp = $message->getId();
        if ($PID !== $messageTimestamp) {
            throw new UpdateMatchesException(sprintf('Message received is not valid. (%s !== %s)', $PID, $messageTimestamp));
        }
    }

    /**
     * @param UpdateMatchMessage $message
     * @throws ClientExceptionInterface
     * @throws ExceptionInterface
     * @throws FootballApiException
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     * @throws UpdateMatchesException
     */
    public function __invoke(UpdateMatchMessage $message)
    {
        // Check this is a valid message received by message bus
        // May have taken a while to come through, it may be added some other way
        $this->validateMessage($message);

        if (($sleepSeconds = $message->getSleepSeconds()) > 0) {
            sleep($sleepSeconds);
        }

        // Still valid after our nap?
        $this->validateMessage($message);

        $currentMatch = $this->matchRepository->findCurrent();

        if ($activeMatch = $this->getActiveMatch($currentMatch)) {
            $this->processRelatedMatches($activeMatch);
            $this->processRelatedLeague($activeMatch);
        } else {
            $this->processUpcomingFixtures();
        }

        try {
            // Still valid after processing?
            $this->validateMessage($message);
            $message->setSleepSeconds($this->getNextSleepTime($currentMatch));
            $this->messageBus->dispatch($message);
        } catch (UpdateMatchesException $e) {
            // No longer a valid message, stop the loop
        }
    }
}
