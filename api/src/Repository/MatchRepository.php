<?php

namespace App\Repository;

use App\DataProvider\FootballDataProvider;
use App\Entity\Match;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Match|null find($id, $lockMode = null, $lockVersion = null)
 * @method Match|null findOneBy(array $criteria, array $orderBy = null)
 * @method Match[]    findAll()
 * @method Match[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MatchRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Match::class);
    }

    public function findCurrent(): ?Match
    {
        $qb = $this->createQueryBuilder('m');
        return $qb
            ->andWhere(
                $qb->expr()->orX(
                    'm.matchHomeTeamId = :team_id',
                    'm.matchAwayTeamId = :team_id'
                )
            )
            ->andWhere('m.matchDateTime >= :min_date')
            ->setParameter('team_id', FootballDataProvider::$teamId)
            ->setParameter('min_date', (new DateTime('now'))->setTime(0, 0))
            ->orderBy('m.matchDateTime', 'ASC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
