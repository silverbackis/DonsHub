<?php

namespace App\Command;

use App\Factory\ChatUserFactory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class UsersAddCommand extends Command
{
    protected static $defaultName = 'app:users:add';
    private $chatUserFactory;

    public function __construct(ChatUserFactory $chatUserFactory)
    {
        $this->chatUserFactory = $chatUserFactory;
        parent::__construct(self::$defaultName);
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Add a short description for your command')
            ->addOption('users', '-u', InputArgument::OPTIONAL, 'How many users to add', 10)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $totalUsers = (int)$input->getOption('users') ?: 10;

        $io->comment(sprintf('Creating %d users', $totalUsers));

        $this->chatUserFactory->create($totalUsers);

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');
    }
}
