<?php

namespace App\Repository;

use DateTime;
use DateTimeInterface;
use App\Entity\{Album, Artist, Listen, Profile, Track};
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Types\DateTimeTzType;
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

    public function getOrCreate(Profile $profile, DateTimeInterface $date, ?array $defaults = null) {
        $lookup = ["profile" => $profile, "date" => $date];
        $listen = $this->findOneBy($lookup);

        if (!$listen) {
            list($artist, $album, $track) = $this->getMusicEntities($defaults);

            $listen = new Listen();
            $listen->setDate($date);

            # add relations
            $profile->addListen($listen);
            $artist->addListen($listen);
            $track->addListen($listen);
            if ($album) $album->addListen($listen);

            # save
            $this->_em->persist($listen);
            $this->_em->flush();
        }

        return $listen;
    }

    private function getMusicEntities(?array $defaults) {
        $artistRepository = $this->getEntityManager()->getRepository(Artist::class);
        $albumRepository = $this->getEntityManager()->getRepository(Album::class);
        $trackRepository = $this->getEntityManager()->getRepository(Track::class);

        // get artist
        $artistName = $defaults["artistName"];
        $artistDefaults = ["mbid" => $defaults["artistMbid"]];
        $artist = $artistRepository->getOrCreate($artistName, $artistDefaults);

        // get album
        $album = null;
        $albumTitle = $defaults["albumTitle"];
        if ($albumTitle) {
            // get album artist if different from track artist
            $albumArtistName = $defaults["albumArtistName"];
            $albumArtistDefaults = ["mbid" => $defaults["albumArtistMbid"] ?? ""];

            $albumArtist = $artist;
            if ($albumArtistName) {
                $albumArtist = $artistRepository->getOrCreate($albumArtistName, $albumArtistDefaults);
            }

            $albumDefaults = ["mbid" => $defaults["albumMbid"]];
            $album = $albumRepository->getOrCreate($albumTitle, $albumArtist, $albumDefaults);
        }

        // get track
        // string $title, Artist $artist, ?Album $album = null, int $tracknumber = 0, ?array $defaults = null
        $trackTitle = $defaults["trackTitle"];
        $tracknumber = $defaults["tracknumber"];
        $trackDefaults = [
            "length" => $defaults["length"] ?? 0,
            "mbid" => $defaults["trackMbid"]
        ];
        $track = $trackRepository->getOrCreate($trackTitle, $artist, $album, $tracknumber, $trackDefaults);

        return [$artist, $album, $track];
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
