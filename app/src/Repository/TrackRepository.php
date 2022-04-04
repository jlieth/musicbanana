<?php

namespace App\Repository;

use App\Entity\{Album, Artist, Track};
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Connection;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Track|null find($id, $lockMode = null, $lockVersion = null)
 * @method Track|null findOneBy(array $criteria, array $orderBy = null)
 * @method Track[]    findAll()
 * @method Track[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TrackRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, Connection $connection)
    {
        $this->conn = $connection;
        parent::__construct($registry, Track::class);
    }

    public function getOrCreate(
        string $title, Artist $artist, ?Album $album = null,
        int $tracknumber = 0, ?array $defaults = null
    ): Track {
        $lookup = [
            "title" => $title,
            "artist" => $artist,
            "album" => $album,
            "tracknumber" => $tracknumber
        ];
        $track = $this->findOneBy($lookup);

        if (!$track) {
            $parameters = [
                "title" => $title,
                "mbid" => $defaults["mbid"] ?? "",
                "artistid" => $artist->getId(),
                "albumid" => $album ? $album->getId() : null,
                "tracknumber" => $tracknumber,
                "length" => $defaults["length"]
            ];

            $this->conn->createQueryBuilder()
                ->insert("Track")
                ->setValue("title", ":title")
                ->setValue("mbid", ":mbid")
                ->setValue("artist_id", ":artistid")
                ->setValue("album_id", ":albumid")
                ->setValue("tracknumber", ":tracknumber")
                ->setValue("length", ":length")
                ->setParameters($parameters)
                ->execute();

            $track = $this->findOneBy($lookup);
        }

        return $track;
    }

    // /**
    //  * @return Track[] Returns an array of Track objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Track
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
