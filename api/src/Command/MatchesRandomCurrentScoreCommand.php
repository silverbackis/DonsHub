<?php

namespace App\Command;

use App\Repository\MatchRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class MatchesRandomCurrentScoreCommand extends Command
{
    protected static $defaultName = 'app:matches:current:random-score';
    private $entityManager;
    private $matchRepository;

    public function __construct(EntityManagerInterface $entityManager, MatchRepository $matchRepository)
    {
        $this->entityManager = $entityManager;
        $this->matchRepository = $matchRepository;
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
        $homeScore = random_int(0, 10);
        $awayScore = random_int(0, 10);
        $match
            ->setMatchHomeTeamScore($homeScore)
            ->setMatchAwayTeamScore($awayScore)
        ;
        $this->entityManager->flush();
        $io->success(sprintf('Score should now be %d - %d', $homeScore, $awayScore));
    }
}
