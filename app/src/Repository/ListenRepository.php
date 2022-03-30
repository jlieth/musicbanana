<?php

namespace App\Repository;

use App\Entity\Listen;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Listen|null find($id, $lockMode = null, $lockVersion = null)
 * @method Listen|null findOneBy(array $criteria, array $orderBy = null)
 * @method Listen[]    findAll()
 * @method Listen[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ListenRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Listen::class);
    }

    // /**
    //  * @return Listen[] Returns an array of Listen objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Listen
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
