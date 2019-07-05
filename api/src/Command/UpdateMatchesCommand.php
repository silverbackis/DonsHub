<?php

namespace App\Command;

use App\Entity\Match;
use App\DataProvider\FootballDataProvider;
use App\Repository\MatchRepository;
use DateTime;
use DateTimeZone;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class UpdateMatchesCommand extends Command
{
    protected static $defaultName = 'app:update-matches';
    private $dataProvider;
    private $entityManager;
    private $matchRepository;

    public function __construct(
        FootballDataProvider $dataProvider,
        EntityManagerInterface $entityManager,
        MatchRepository $matchRepository)
    {
        $this->dataProvider = $dataProvider;
        $this->entityManager = $entityManager;
        $this->matchRepository = $matchRepository;
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
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     * @throws ExceptionInterface
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);
        $io = new SymfonyStyle($input, $output);
        $fromDate = new DateTime();
        $toDate = new DateTime();
        $toDate->modify('+6 months');
        $format = 'Y-m-d';
        $apiTimeZone = new DateTimeZone('Europe/Berlin');
        $utcTimeZone = new DateTimeZone('Europe/London');

        $io->comment('Fetching events from API...');
        $response = $this->dataProvider->request('get_events', [
            'from' => $fromDate->format($format),
            'to' => $toDate->format($format),
            'team_id' => FootballDataProvider::$teamId
        ]);
        $matchesArray = json_decode($response->getContent(), true);
        $io->comment('Processing result...');
        $progress = $io->createProgressBar();
        foreach ($progress->iterate($matchesArray) as $matchData) {
            $matchDateTime = new DateTime($matchData['match_date'] . ' ' . $matchData['match_time'] . ':00', $apiTimeZone);
            $matchDateTime->setTimezone($utcTimeZone);
            $matchData['match_date_time'] = $matchDateTime;

            /** @var Match $match */
            $match = $serializer->denormalize($matchData, Match::class, 'json');
            $existingMatch = $this->matchRepository->findOneBy([
                'matchId' => $match->getMatchId()
            ]);
            if ($existingMatch) {
                $this->entityManager->remove($existingMatch);
            }
            $this->entityManager->persist($match);
        }
        $io->newLine(2);
        $this->entityManager->flush();
        $io->success('Matches imported');
    }
}
