<?php

declare(strict_types=1);

namespace App\Tests\QueryBuilder;

use DateInterval;
use DateTime;
use DateTimeZone;
use App\Entity\{Album, Artist, Profile, Track, User};
use App\QueryBuilder\ListenQueryBuilder;
use App\Tests\BaseDbTest;
use PHPUnit\Framework\MockObject\MockObject;

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
     * Tests App\QueryBuilder\ListenQueryBuilder::{year, month, week, day}
     */
    public function testYearMonthWeekDay(): void
    {
        // year
        $start = new DateTime("2016-01-01", new DateTimeZone("UTC"));
        $end = new DateTime("2017-01-01T00:00:00+00:00", new DateTimeZone("UTC"));
        $mockQB = $this->getDaterangeMock($start, $end);

        /** @var ListenQueryBuilder $mockQB */
        $mockQB->year($start);

        // month
        $start = new DateTime("2016-02-01", new DateTimeZone("UTC"));
        $end = new DateTime("2016-03-01T00:00:00+00:00", new DateTimeZone("UTC"));
        $mockQB = $this->getDaterangeMock($start, $end);

        /** @var ListenQueryBuilder $mockQB */
        $mockQB->month($start);

        // week
        $start = new DateTime("2022-05-09", new DateTimeZone("UTC"));
        $end = new DateTime("2022-05-16T00:00:00+00:00", new DateTimeZone("UTC"));
        $mockQB = $this->getDaterangeMock($start, $end);

        /** @var ListenQueryBuilder $mockQB */
        $mockQB->week($start);

        // day
        $start = new DateTime("2022-05-09", new DateTimeZone("UTC"));
        $end = new DateTime("2022-05-10T00:00:00+00:00", new DateTimeZone("UTC"));
        $mockQB = $this->getDaterangeMock($start, $end);

        /** @var ListenQueryBuilder $mockQB */
        $mockQB->day($start);
    }

    private function getDaterangeMock(DateTime $start, DateTime $end): MockObject
    {
        // Create a mock for the QueryBuilder class, only mock the daterange() method.
        $mockQB = $this
            ->getMockBuilder(ListenQueryBuilder::class)
            ->setConstructorArgs([$this->conn])
            ->onlyMethods(["daterange"])
            ->getMock();

        // Set up the expectation for the daterange() method to be called once
        // with the expected start and end date
        $mockQB
            ->expects($this->once())
            ->method("daterange")
            ->with($this->equalTo($start), $this->equalTo($end));

        return $mockQB;
    }

    /**
     * Tests App\QueryBuilder\ListenQueryBuilder::daterange
     *
     * daterange() takes two arguments: $start and $end of the requested
     * range. $end is optional; if no end datetime is given, it is set to
     * now. The method should return all listens that fall in this time range,
     * with $start inclusive and $end exclusive.
     *
     * This test should cover the following assertions:
     * - listens with a timestamp earlier than $start should not be returned
     * - listens with the same timestamp as $start should be returned
     * - listens with the same timestamp as $end should not be returned
     * - listens with a timestamp later than $end should not be returned
     * - listens with a timestamp $start <= timestamp < $end should be returned
     * - additionally, when $end is not given, $end is set to now, so the list
     *   of listens that is expected to be returned changes
     *
     * |        |                      |         |     expected to be returned     |
     * | vars   | datetimes            | listens | when end given | when not given |
     * |--------|----------------------|---------|----------------|----------------|
     * |        | <2019-01-01T00:00:00 | listen1 | NO             | NO             |
     * | $start |  2019-01-01T00:00:00 | listen2 | YES            | YES            |
     * |        |  ...                 | listen3 | YES            | YES            |
     * | $end   |  2021-01-01T00:00:00 | listen4 | NO             | YES            |
     * |        | >2021-01-01T00:00:00 | listen5 | NO             | YES            |
     * |        |  now                 | listen6 | NO             | YES*           |
     *
     * Caveat for listens with a timestamp identical to "now": Since $end is
     * exclusive, this listen will only be returned when "now" is later than
     * its timestamp. For the test, I substracted one second from the timestamp.
     */
    public function testDaterange(): void
    {
        $utc = new DateTimeZone("UTC");
        $start = new DateTime("2019-01-01T00:00:00", $utc);
        $end = new DateTime("2021-01-01T00:00:00", $utc);

        // profile and track info don't matter
        $profile = $this->profiles[0];
        $track = $this->tracks[0];

        // create listens
        $date1 = new DateTime("2018-01-01T00:00:00", $utc);
        $date2 = $start;
        $date3 = new DateTime("2020-01-01T00:00:00", $utc);
        $date4 = $end;
        $date5 = new DateTime("2022-01-01T00:00:00", $utc);
        $date6 = (new DateTime("now", $utc))->sub(new DateInterval("PT1S"));

        $listen1 = $this->createListen($date1, $profile, $track->getArtist(), $track->getAlbum(), $track);
        $listen2 = $this->createListen($date2, $profile, $track->getArtist(), $track->getAlbum(), $track);
        $listen3 = $this->createListen($date3, $profile, $track->getArtist(), $track->getAlbum(), $track);
        $listen4 = $this->createListen($date4, $profile, $track->getArtist(), $track->getAlbum(), $track);
        $listen5 = $this->createListen($date5, $profile, $track->getArtist(), $track->getAlbum(), $track);
        $listen6 = $this->createListen($date6, $profile, $track->getArtist(), $track->getAlbum(), $track);

        // filter with giving $end
        $qb = $this->getQB()->select("l.id")->daterange($start, $end);
        $rows = $qb->fetchFirstColumn();

        $this->assertCount(2, $rows);
        $this->assertTrue(in_array($listen2->getId(), $rows));
        $this->assertTrue(in_array($listen3->getId(), $rows));
        $this->assertNotTrue(in_array($listen1->getId(), $rows));
        $this->assertNotTrue(in_array($listen4->getId(), $rows));
        $this->assertNotTrue(in_array($listen5->getId(), $rows));
        $this->assertNotTrue(in_array($listen6->getId(), $rows));

        // filter without giving $end
        $qb = $this->getQB()->select("l.id")->daterange($start);
        $rows = $qb->fetchFirstColumn();

        $this->assertCount(5, $rows);
        $this->assertTrue(in_array($listen2->getId(), $rows));
        $this->assertTrue(in_array($listen3->getId(), $rows));
        $this->assertTrue(in_array($listen4->getId(), $rows));
        $this->assertTrue(in_array($listen5->getId(), $rows));
        $this->assertTrue(in_array($listen6->getId(), $rows));
        $this->assertNotTrue(in_array($listen1->getId(), $rows));
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
     * Tests App\QueryBuilder\ListenQueryBuilder::filterByTrack
     *
     * Create two listens, one with track1 and another with track2.
     * Filter listens by track1; only the first listen should be returned.
     * Filter listens by track2; only the second listen should be returned.
     */
    public function testFilterByTrack(): void
    {
        $track1 = $this->tracks[0];
        $track2 = $this->tracks[1];

        // profile and date don't matter, but must be different for different listens
        $profile1 = $this->profiles[0];
        $profile2 = $this->profiles[1];
        $date = new DateTime("now", new DateTimeZone("UTC"));

        // create one listen for each track
        $listen1 = $this->createListen($date, $profile1, $track1->getArtist(), $track1->getAlbum(), $track1);
        $listen2 = $this->createListen($date, $profile2, $track2->getArtist(), $track2->getAlbum(), $track2);

        // filter listens by track1
        $qb = $this->getQB()->select("l.id")->filterByTrack($track1);
        $rows = $qb->fetchFirstColumn();

        $this->assertCount(1, $rows);
        $this->assertEquals($rows[0], $listen1->getId());

        // filter listens by track2
        $qb = $this->getQB()->select("l.id")->filterByTrack($track2);
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
