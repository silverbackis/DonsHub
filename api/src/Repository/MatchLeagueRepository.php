<?php

namespace App\Repository;

use App\Entity\Match;
use App\Entity\MatchLeague;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
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

    /**
     * @param Match $match
     * @return MatchLeague
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function findOneByMatch(Match $match): MatchLeague
    {
        $league = $this->findOneBy([
            'match' => $match
        ]);

        if (!$league) {
            $league = new MatchLeague();
            $league->setMatch($match);
            $this->_em->persist($league);
            $this->_em->flush();
            $this->_em->refresh($league);
        }

        return $league;
    }
}
