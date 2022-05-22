<?php

declare(strict_types=1);

namespace App\Tests\QueryBuilder;

use DateTime;
use DateTimeZone;
use App\Entity\{Album, Artist, Listen, Profile, Track, User};
use App\QueryBuilder\ListenQueryBuilder;
use App\Tests\BaseDbTest;

class ListenQueryBuilderTest extends BaseDbTest {
    /** @var User[] $users */
    private array $users;
    /** @var Profile[] $profiles */
    private array $profiles;
    /** @var Artist[] $artists */
    private array $artists;
    /** @var Album[] $artists */
    private array $albums;
    /** @var Track[] $tracks */
    private array $tracks;

    public function setUp(): void
    {
        parent::setUp();

        // create two users, alice and bob
        $alice = $this->createUser("Alice");
        $bob = $this->createUser("Bob");
        $this->users = [$alice, $bob];

        // create profiles for users
        $this->profiles[] = $this->createProfile("default", $alice);
        $this->profiles[] = $this->createProfile("secret", $alice, false);
        $this->profiles[] = $this->createProfile("default", $bob);

        // create artists
        $artist1 = $this->createArtist("Artist 1");
        $artist2 = $this->createArtist("Artist 2");
        $this->artists = [$artist1, $artist2];

        // create albums
        $album1 = $this->createAlbum("Album 1", $artist1);
        $album2 = $this->createAlbum("Album 2", $artist1);
        $this->albums = [$album1, $album2];

        // create tracks
        $track1 = $this->createTrack("Track 1", $artist1, $album1, 1, 200);
        $track2 = $this->createTrack("Track 2", $artist2, $album2, 1, 200);
        $track3 = $this->createTrack("Track 3", $artist1, null, 0, 200);
        $this->tracks = [$track1, $track2, $track3];
    }

    private function getQB(): ListenQueryBuilder
    {
        $tableName = $this->em->getClassMetadata("App:Listen")->getTableName();
        $qb = new ListenQueryBuilder($this->conn);
        $qb->from($tableName, "l");
        return $qb;
    }

    /**
     * Tests App\QueryBuilder\ListenQueryBuilder::filterByUser
     *
     * Create two listens, one belonging to a profile of user Alice and
     * another belonging to a profile of user Bob. Filter listens by user
     * Alice; only the first listen should be returned. Filter listens by
     * user Bob; only the second listen should be returned.
     */
    public function testFilterByUser(): void
    {
        $alice = $this->users[0];
        $bob = $this->users[1];

        // track and date can be anything
        $track = $this->tracks[0];
        $date = new DateTime("now", new DateTimeZone("UTC"));

        // create one listen for each user
        $listen1 = $this->createListen($date, $alice->getProfiles()[0], $track->getArtist(), $track->getAlbum(), $track);
        $listen2 = $this->createListen($date, $bob->getProfiles()[0], $track->getArtist(), $track->getAlbum(), $track);

        // filter listens by user alice
        $qb = $this->getQB()->select("l.id")->filterByUser($alice);
        $rows = $qb->fetchAllAssociative();

        $this->assertCount(1, $rows);
        $this->assertEquals($rows[0]["id"], $listen1->getId());

        // filter listens by user bob
        $qb = $this->getQB()->select("l.id")->filterByUser($bob);
        $rows = $qb->fetchAllAssociative();

        $this->assertCount(1, $rows);
        $this->assertEquals($rows[0]["id"], $listen2->getId());
    }

    public function testPublic(): void
    {
        /*
         * Tests App\QueryBuilder\ListenQueryBuilder::public
         *
         * Create two listens, one for a private profile and one for a public
         * profile. Filtering by public should only return the listen on the
         * public profile.
         */
        $profile1 = $this->profiles[0];
        $profile2 = $this->profiles[1];

        // Assert that we have a public and a private profile
        $this->assertTrue($profile1->getIsPublic());
        $this->assertFalse($profile2->getIsPublic());

        // track and date can be anything
        $track = $this->tracks[0];
        $date = new DateTime("now", new DateTimeZone("UTC"));

        // create one listen for each profile
        $listen1 = $this->createListen($date, $profile1, $track->getArtist(), $track->getAlbum(), $track);
        $listen2 = $this->createListen($date, $profile2, $track->getArtist(), $track->getAlbum(), $track);

        // filter listens by public
        $qb = $this->getQB()->select("l.id")->public();
        $rows = $qb->fetchFirstColumn();

        $this->assertCount(1, $rows);
        $this->assertEquals($rows[0], $listen1->getId());
        $this->assertNotTrue(in_array($listen2->getId(), $rows));
    }

}
