<?php

namespace App\Repository;

use App\Entity\Artist;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Connection;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Artist|null find($id, $lockMode = null, $lockVersion = null)
 * @method Artist|null findOneBy(array $criteria, array $orderBy = null)
 * @method Artist[]    findAll()
 * @method Artist[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArtistRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, Connection $connection)
    {
        $this->conn = $connection;
        parent::__construct($registry, Artist::class);
    }

    public function getOrCreate(string $name, ?array $defaults = null): Artist {
        $lookup = ["name" => $name];
        $artist = $this->findOneBy($lookup);

        if (!$artist) {
            $parameters = [
                "name" => $name,
                "mbid" => $defaults["mbid"] ?? ""
            ];

            $this->conn->createQueryBuilder()
                ->insert("Artist")
                ->setValue("name", ":name")
                ->setValue("mbid", ":mbid")
                ->setParameters($parameters)
                ->execute();

            $artist = $this->findOneBy($lookup);
        }

        return $artist;
    }

    // /**
    //  * @return Artist[] Returns an array of Artist objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Artist
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
