<?php

namespace App\Repository;

use App\Entity\{Album, Artist};
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Connection;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Album|null find($id, $lockMode = null, $lockVersion = null)
 * @method Album|null findOneBy(array $criteria, array $orderBy = null)
 * @method Album[]    findAll()
 * @method Album[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AlbumRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, Connection $connection)
    {
        $this->conn = $connection;
        parent::__construct($registry, Album::class);
    }

    public function getOrCreate(string $title, Artist $artist, ?array $defaults = null): Album {
        $lookup = ["title" => $title, "artist" => $artist];
        $album = $this->findOneBy($lookup);

        if (!$album) {
            $parameters = [
                "title" => $title,
                "mbid" => $defaults["mbid"] ?? "",
                "artistid" => $artist->getId()
            ];

            $this->conn->createQueryBuilder()
                ->insert("Album")
                ->setValue("title", ":title")
                ->setValue("mbid", ":mbid")
                ->setValue("artist_id", ":artistid")
                ->setParameters($parameters)
                ->execute();

            $album = $this->findOneBy($lookup);
        }

        return $album;
    }

    // /**
    //  * @return Album[] Returns an array of Album objects
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
    public function findOneBySomeField($value): ?Album
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
