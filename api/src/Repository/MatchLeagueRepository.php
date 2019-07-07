<?php

namespace App\Repository;

use App\Entity\MatchLeague;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method MatchLeague|null find($id, $lockMode = null, $lockVersion = null)
 * @method MatchLeague|null findOneBy(array $criteria, array $orderBy = null)
 * @method MatchLeague[]    findAll()
 * @method MatchLeague[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MatchLeagueRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, MatchLeague::class);
    }

    // /**
    //  * @return MatchLeague[] Returns an array of MatchLeague objects
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
    public function findOneBySomeField($value): ?MatchLeague
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
