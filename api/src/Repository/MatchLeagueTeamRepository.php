<?php

namespace App\Repository;

use App\Entity\MatchLeagueTeam;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method MatchLeagueTeam|null find($id, $lockMode = null, $lockVersion = null)
 * @method MatchLeagueTeam|null findOneBy(array $criteria, array $orderBy = null)
 * @method MatchLeagueTeam[]    findAll()
 * @method MatchLeagueTeam[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MatchLeagueTeamRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, MatchLeagueTeam::class);
    }

    // /**
    //  * @return MatchLeagueTeam[] Returns an array of MatchLeagueTeam objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MatchLeagueTeam
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
