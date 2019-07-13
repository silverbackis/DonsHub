<?php

namespace App\Handler;

use App\DataPersister\TweetsDataPersister;
use App\DataProvider\TweetsDataProvider;
use App\Exception\UpdateTweetsHandlerException;
use App\Message\UpdateTweetsMessage;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

class UpdateTweetsHandler implements MessageHandlerInterface
{
    private $dataProvider;
    private $dataPersister;
    private $messageBus;
    public static $PIDFile = '/tmp/PID/TWTS';

    public function __construct(
        TweetsDataProvider $dataProvider,
        TweetsDataPersister $dataPersister,
        MessageBusInterface $messageBus
    )
    {
        $this->dataProvider = $dataProvider;
        $this->dataPersister = $dataPersister;
        $this->messageBus = $messageBus;
    }

    // The message we are looping with should have the same timestamp as the one that was started initially
    // This will ensure we do not have multiple processes running and an increase in API calls
    // When the command is called to create the first message, it will update the PID with a new timestamp
    // We validate the message we receive in the handler is the latest looping message
    /**
     * @param UpdateTweetsMessage $message
     * @throws UpdateTweetsHandlerException
     */
    private function validateMessage(UpdateTweetsMessage $message): void
    {
        $PID = file_get_contents(self::$PIDFile);
        $messageTimestamp = $message->getId();
        if ($PID !== $messageTimestamp) {
            throw new UpdateTweetsHandlerException(sprintf('Message received is not valid. (%s !== %s)', $PID, $messageTimestamp));
        }
    }

    /**
     * @param UpdateTweetsMessage $message
     * @throws ExceptionInterface
     * @throws UpdateTweetsHandlerException
     */
    public function __invoke(UpdateTweetsMessage $message)
    {
        // Check this is a valid message received by message bus
        // May have taken a while to come through, it may be added some other way
        $this->validateMessage($message);

        if (($sleepSeconds = $message->getSleepSeconds()) > 0) {
            sleep($sleepSeconds);
        }

        // Still valid after our nap?
        $this->validateMessage($message);

        $clubTweets = $this->dataProvider->fetchClubTweets();
        $this->dataPersister->persistTweets($clubTweets);
        $fanTweets = $this->dataProvider->fetchFanTweets();
        $this->dataPersister->persistTweets($fanTweets, true);

        try {
            // Still valid after processing?
            $this->validateMessage($message);
            $message->setSleepSeconds(15);
            $this->messageBus->dispatch($message);
        } catch (UpdateTweetsHandlerException $e) {
            // No longer a valid message, stop the loop
        }
    }
}
