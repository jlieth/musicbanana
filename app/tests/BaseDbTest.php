<?php

declare(strict_types=1);

namespace App\Tests;

use DateTimeInterface;
use App\Entity\{Album, Artist, Listen, Profile, Track, User};
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

abstract class BaseDbTest extends KernelTestCase
{
    protected Connection $conn;
    protected EntityManager $em;

    public function setUp(): void
    {
        $this->conn = self::getContainer()->get("doctrine.dbal.default_connection");
        $this->em = self::getContainer()->get("doctrine.orm.entity_manager");
    }

    protected function createUser(String $name): User
    {
        $user = (new User())
            ->setName($name)
            ->setEmail("$name@example.com")
            ->setPassword("foobar");
        $this->em->persist($user);
        $this->em->flush();
        return $user;
    }

    /**
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
     */
    protected function createProfile(String $name, User $user, bool $isPublic = true): Profile
    {
        $profile = (new Profile())->setName($name)->setIsPublic($isPublic);
        $user->addProfile($profile);
        $this->em->persist($profile);
        $this->em->flush();
        return $profile;
    }

    protected function createArtist(String $name): Artist
    {
        $artist = (new Artist())->setName($name);
        $this->em->persist($artist);
        $this->em->flush();
        return $artist;

    }

    protected function createAlbum(String $title, Artist $artist): Album
    {
        $album = (new Album())->setTitle($title);
        $artist->addAlbum($album);
        $this->em->persist($album);
        $this->em->flush();
        return $album;

    }

    protected function createTrack(
        string $title, Artist $artist, ?Album $album = null,
        int $tracknumber = 0, int $length = 0): Track
    {
        $track = (new Track())
            ->setTitle($title)
            ->setTracknumber($tracknumber)
            ->setLength($length);
        $artist->addTrack($track);
        if ($album !== null) $album->addTrack($track);
        $this->em->persist($track);
        $this->em->flush();
        return $track;
    }

    protected function createListen(
        DateTimeInterface $date, Profile $profile,
        Artist $artist, ?Album $album, Track $track
    ): Listen
    {
        $listen = (new Listen())->setDate($date);
        $profile->addListen($listen);
        $artist->addListen($listen);
        $track->addListen($listen);
        if ($album !== null) $album->addListen($listen);
        $this->em->persist($listen);
        $this->em->flush();
        return $listen;
    }
}
