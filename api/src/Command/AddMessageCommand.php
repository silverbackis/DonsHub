<?php

namespace App\Command;

use App\Entity\ChatMessage;
use App\Repository\ChatUserRepository;
use App\Repository\MatchRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class AddMessageCommand extends Command
{
    protected static $defaultName = 'app:add-message';

    private $entityManager;
    private $matchRepository;
    private $chatUserRepository;

    public function __construct(EntityManagerInterface $entityManager, MatchRepository $matchRepository, ChatUserRepository $chatUserRepository)
    {
        $this->entityManager = $entityManager;
        $this->matchRepository = $matchRepository;
        $this->chatUserRepository = $chatUserRepository;
        parent::__construct(self::$defaultName);
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Randomise score for the current game')
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void|null
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $match = $this->matchRepository->findCurrent();
        if (!$match) {
            $io->error('No current match found');
            return;
        }
        $chatUser = $this->chatUserRepository->findAll();
        $chatUser = $chatUser[0];
        $message = new ChatMessage();
        $message
            ->setCreated(new \DateTime())
            ->setMatch($match)
            ->setChatUser($chatUser)
            ->setMessage('A message form a command...')
        ;
        $this->entityManager->persist($message);
        $this->entityManager->flush();
        $io->success('Message added');
    }
}
