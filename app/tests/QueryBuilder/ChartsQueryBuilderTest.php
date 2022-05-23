<?php

declare(strict_types=1);

namespace App\Tests\QueryBuilder;

use DateInterval;
use DateTime;
use DateTimeZone;
use App\Entity\Listen;
use App\QueryBuilder\ChartsQueryBuilder;
use App\Tests\BaseDbTest;
use PHPUnit\Framework\MockObject\MockObject;

class ChartsQueryBuilderTest extends BaseDbTest {
    public function setUp(): void
    {
        parent::setUp();

        // create user and profiles
        $alice = $this->createUser("Alice");
        $profile1 = $this->createProfile("default", $alice);
        $profile2 = $this->createProfile("secret", $alice, false);

        // get repo
        /** @var \App\Repository\ListenRepository $repo */
        $repo = $this->em->getRepository(Listen::class);

        // load test data
        $file = __DIR__ . "/../data/charts_testdata.tsv";
        $data = explode("\n", file_get_contents($file));
        foreach ($data as $line) {
            $line = explode("\t", $line);
            if (count($line) !== 4) continue;

            list($artist, $track, $album, $ts) = $line;
            $date = new DateTime($ts, new DateTimeZone("UTC"));

            // switch to secret profile for arbitrary artist
            $profile = $profile1;
            if ($artist === "Harm") $profile = $profile2;

            $defaults = [
                "artistName" => $artist,
                "artistMbid" => null,
                "albumTitle" => $album ?? null,
                "albumMbid" => null,
                "albumArtistName" => $artist,
                "albumArtistMbid" => null,
                "trackTitle" => $track,
                "tracknumber" => 0,
                "length" => 0,
                "trackMbid" => null,
            ];

            $repo->getOrCreate($profile, $date, $defaults);
        }
    }

    private function getQB(): ChartsQueryBuilder
    {
        return new ChartsQueryBuilder($this->conn);
    }

    public function testArtist(): void
    {
        $qb = $this->getQB();
        $result = $qb->artists()->fetchAllAssociative();

        $expected = [
            ["artist_name" => "Harm", "count" => "36"],
            ["artist_name" => "Gale Ventura", "count" => "24"],
            ["artist_name" => "Becky Leo", "count" => "10"],
            ["artist_name" => "Pool", "count" => "7"],
            ["artist_name" => "Morris Michaels", "count" => "3"],
        ];

        $this->assertEquals($result, $expected);
    }

    public function testAlbums(): void
    {
        $result = $this->getQB()->albums()->fetchAllAssociative();
        $expected = [
            ["artist_name" => "Gale Ventura", "album_title" => "Cloud nine", "count" => 24],
            ["artist_name" => "Harm", "album_title" => "Patient zero", "count" => 18],
            ["artist_name" => "Harm", "album_title" => "Emergency", "count" => 14],
            ["artist_name" => "Pool", "album_title" => "Midnight oil", "count" => 6],
            ["artist_name" => "Harm", "album_title" => "Doctor in the house", "count" => 2],
            ["artist_name" => "Becky Leo", "album_title" => "Cobweb of lies", "count" => 1],
            ["artist_name" => "Harm", "album_title" => "Methodical madness", "count" => 1],
            ["artist_name" => "Harm", "album_title" => "No medical history", "count" => 1],
            ["artist_name" => "Pool", "album_title" => "The last laugh", "count" => 1],
        ];

        $this->assertEquals($result, $expected);
    }
}
