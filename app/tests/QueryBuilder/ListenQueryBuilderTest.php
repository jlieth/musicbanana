<?php

declare(strict_types=1);

namespace App\Tests\QueryBuilder;

use DateInterval;
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
     * Tests App\QueryBuilder\ListenQueryBuilder::daterange
     *
     * daterange() takes two arguments: $start and $end of the requested
     * range. $end is optional; if no end datetime is given, it is set to
     * now.
     *
     * For a listen with a given date, three cases are possible:
     * - the date is before $start, so it falls out of range
     * - the date is between $start and $end, so it is within range
     * - the date is after $end, so it falls out of range
     *
     * To test this function, create four listens with the following dates:
     * - listen1: four days ago
     * - listen2: two days ago
     * - listen3: now
     * - listen4: tomorrow
     *
     * Set the range end points:
     * - $start: three days ago
     * - $end: yesterday
     *
     * Expected results:
     * - with no $end given, listen2 and listen3 should be returned
     * - with $end given, only listen2 should be returned
     */
    public function testDaterange(): void
    {
        $utc = new DateTimeZone("UTC");

        // profile and track info don't matter
        $profile = $this->profiles[0];
        $track = $this->tracks[0];

        // create listens
        $date1 = (new DateTime("now", $utc))->sub(new DateInterval("P4D"));
        $listen1 = $this->createListen($date1, $profile, $track->getArtist(), $track->getAlbum(), $track);

        $date2 = (new DateTime("now", $utc))->sub(new DateInterval("P2D"));
        $listen2 = $this->createListen($date2, $profile, $track->getArtist(), $track->getAlbum(), $track);

        $date3 = new DateTime("now", $utc);
        $listen3 = $this->createListen($date3, $profile, $track->getArtist(), $track->getAlbum(), $track);

        $date4 = (new DateTime("now", $utc))->add(new DateInterval("P1D"));
        $listen4 = $this->createListen($date4, $profile, $track->getArtist(), $track->getAlbum(), $track);

        // create range end points
        $start = (new DateTime("now", $utc))->sub(new DateInterval("P3D"));
        $end = (new DateTime("now", $utc))->sub(new DateInterval("P1D"));

        // filter without giving $end
        $qb = $this->getQB()->select("l.id")->daterange($start);
        $rows = $qb->fetchFirstColumn();

        $this->assertCount(2, $rows);
        $this->assertEquals($rows[0], $listen2->getId());
        $this->assertEquals($rows[1], $listen3->getId());
        $this->assertNotTrue(in_array($listen1->getId(), $rows));
        $this->assertNotTrue(in_array($listen4->getId(), $rows));

        // filter with giving $end
        $qb = $this->getQB()->select("l.id")->daterange($start, $end);
        $rows = $qb->fetchFirstColumn();

        $this->assertCount(1, $rows);
        $this->assertEquals($rows[0], $listen2->getId());
        $this->assertNotTrue(in_array($listen1->getId(), $rows));
        $this->assertNotTrue(in_array($listen3->getId(), $rows));
        $this->assertNotTrue(in_array($listen4->getId(), $rows));
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

    /**
     * Tests App\QueryBuilder\ListenQueryBuilder::filterByProfile
     *
     * Create two listens, one belonging to profile1 and another belonging to
     * profile2.
     * Filter listens by profile1; only the first listen should be returned.
     * Filter listens by profile2; only the second listen should be returned.
     */
    public function testFilterByProfile(): void
    {
        $profile1 = $this->profiles[0];
        $profile2 = $this->profiles[1];

        // track and date can be anything
        $track = $this->tracks[0];
        $date = new DateTime("now", new DateTimeZone("UTC"));

        // create one listen for each profile
        $listen1 = $this->createListen($date, $profile1, $track->getArtist(), $track->getAlbum(), $track);
        $listen2 = $this->createListen($date, $profile2, $track->getArtist(), $track->getAlbum(), $track);

        // filter listens by profile1
        $qb = $this->getQB()->select("l.id")->filterByProfile($profile1);
        $rows = $qb->fetchFirstColumn();

        $this->assertCount(1, $rows);
        $this->assertEquals($rows[0], $listen1->getId());

        // filter listens by profile2
        $qb = $this->getQB()->select("l.id")->filterByProfile($profile2);
        $rows = $qb->fetchFirstColumn();

        $this->assertCount(1, $rows);
        $this->assertEquals($rows[0], $listen2->getId());
    }

    /**
     * Tests App\QueryBuilder\ListenQueryBuilder::filterByArtist
     *
     * Create two listens, one with artist1 and another with artist2.
     * Filter listens by artist1; only the first listen should be returned.
     * Filter listens by artist2; only the second listen should be returned.
     */
    public function testFilterByArtist(): void
    {
        $artist1 = $this->artists[0];
        $artist2 = $this->artists[1];

        // profile and date don't matter, but must be different for different listens
        $profile1 = $this->profiles[0];
        $profile2 = $this->profiles[1];
        $date = new DateTime("now", new DateTimeZone("UTC"));

        // create one listen for each artist
        $listen1 = $this->createListen($date, $profile1, $artist1, null, $artist1->getTracks()[0]);
        $listen2 = $this->createListen($date, $profile2, $artist2, null, $artist2->getTracks()[0]);

        // filter listens by artist1
        $qb = $this->getQB()->select("l.id")->filterByArtist($artist1);
        $rows = $qb->fetchFirstColumn();

        $this->assertCount(1, $rows);
        $this->assertEquals($rows[0], $listen1->getId());

        // filter listens by artist2
        $qb = $this->getQB()->select("l.id")->filterByArtist($artist2);
        $rows = $qb->fetchFirstColumn();

        $this->assertCount(1, $rows);
        $this->assertEquals($rows[0], $listen2->getId());
    }

    /**
     * Tests App\QueryBuilder\ListenQueryBuilder::filterByAlbum
     *
     * Create two listens, one with album1 and another with album2.
     * Filter listens by album1; only the first listen should be returned.
     * Filter listens by album1; only the second listen should be returned.
     */
    public function testFilterByAlbum(): void
    {
        $album1 = $this->albums[0];
        $album2 = $this->albums[1];

        // profile and date don't matter, but must be different for different listens
        $profile1 = $this->profiles[0];
        $profile2 = $this->profiles[1];
        $date = new DateTime("now", new DateTimeZone("UTC"));

        // create one listen for each album
        $listen1 = $this->createListen($date, $profile1, $album1->getArtist(), $album1, $album1->getTracks()[0]);
        $listen2 = $this->createListen($date, $profile2, $album2->getArtist(), $album2, $album2->getTracks()[0]);

        // filter listens by album1
        $qb = $this->getQB()->select("l.id")->filterByAlbum($album1);
        $rows = $qb->fetchFirstColumn();

        $this->assertCount(1, $rows);
        $this->assertEquals($rows[0], $listen1->getId());

        // filter listens by album2
        $qb = $this->getQB()->select("l.id")->filterByAlbum($album2);
        $rows = $qb->fetchFirstColumn();

        $this->assertCount(1, $rows);
        $this->assertEquals($rows[0], $listen2->getId());
    }

    /**
     * Tests App\QueryBuilder\ListenQueryBuilder::public
     *
     * Create two listens, one for a private profile and one for a public
     * profile. Filtering by public should only return the listen on the
     * public profile.
     */
    public function testPublic(): void
    {
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
