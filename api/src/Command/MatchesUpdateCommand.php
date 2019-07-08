<?php

namespace App\Command;

use App\Exception\FootballApiException;
use App\Exception\UpdateMatchesException;
use App\Handler\UpdateMatchesHandler;
use App\Message\UpdateMatchMessage;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class MatchesUpdateCommand extends Command
{
    protected static $defaultName = 'app:matches:update';
    private $matchesHandler;

    public function __construct(
        UpdateMatchesHandler $matchesHandler
    )
    {
        $this->matchesHandler = $matchesHandler;
        parent::__construct(self::$defaultName);
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Read in match events from API and upload local database')
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void|null
     * @throws ClientExceptionInterface
     * @throws ExceptionInterface
     * @throws FootballApiException
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     * @throws UpdateMatchesException
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $io->comment('Updating...');
        $message = new UpdateMatchMessage();
        file_put_contents(UpdateMatchesHandler::$PIDFile, $message->getId());
        $this->matchesHandler->__invoke($message);
        $io->success('Matches update request complete. A message has been sent via messenger to continue requesting updates.');
    }
}
