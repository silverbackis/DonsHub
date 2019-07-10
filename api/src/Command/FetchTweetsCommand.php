<?php

namespace App\Command;

use App\Exception\UpdateTweetsHandlerException;
use App\Handler\UpdateTweetsHandler;
use App\Message\UpdateTweetsMessage;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

class FetchTweetsCommand extends Command
{
    protected static $defaultName = 'app:fetch:tweets';
    private $handler;

    public function __construct(
        UpdateTweetsHandler $handler
    )
    {
        $this->handler = $handler;
        parent::__construct(self::$defaultName);
    }

    protected function configure()
    {
        $this
            ->setDescription('Fetch tweets and send message to update in intervals')
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void|null
     * @throws UpdateTweetsHandlerException
     * @throws ExceptionInterface
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $io->comment('Updating...');
        $message = new UpdateTweetsMessage();
        file_put_contents(UpdateTweetsHandler::$PIDFile, $message->getId());
        $this->handler->__invoke($message);
        $io->success('Tweets update request complete. A message has been sent via messenger to continue requesting updates.');
    }
}
