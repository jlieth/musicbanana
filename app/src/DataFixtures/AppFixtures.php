<?php

namespace App\DataFixtures;

use DateTime;
use DateTimeZone;
use SQLite3;
use App\Entity\{Album, Artist, Listen, Profile, Track, User};
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 */
class AppFixtures extends Fixture
{
    private static $batchSize = 10000;
    private static $userNames = ["demo", "Alice", "bob", "Carol", "David", "erin", "Frank", "grace", "Heidi", "Ivan"];
    private static $profileNames = ["default", "Spotify", "work", "Phone", "Tidal"];

    private int $dateOffset;
    private UserPasswordHasherInterface $hasher;

    public function __construct(EntityManagerInterface $em, UserPasswordHasherInterface $hasher)
    {
        $this->em = $em;
        $this->hasher = $hasher;
        $this->albumRepository = $em->getRepository(Album::class);
        $this->artistRepository = $em->getRepository(Artist::class);
        $this->listenRepository = $em->getRepository(Listen::class);
        $this->profileRepository = $em->getRepository(Profile::class);
        $this->trackRepository = $em->getRepository(Track::class);

        $this->profiles = [];

        // disable sql logger to save memory
        $em->getConnection()->getConfiguration()->setSQLLogger(null);

        $datasetFile = __DIR__ . "/lastfm-dataset-50-extended-normalized.db";
        $this->dataset = new SQLite3($datasetFile);

        // calculate time offset between most recent listen and now
        $query = "SELECT MAX(timestamp) FROM listens;";
        $dateString = $this->dataset->querySingle($query);
        $maxDate = new DateTime($dateString, new DateTimeZone("UTC"));
        $now = new DateTime("now", new DateTimeZone("UTC"));
        $this->dateOffset = $now->getTimestamp() - $maxDate->getTimestamp();
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function load(ObjectManager $manager): void
    {
        $this->createProfiles();
        $this->createArtists();
        $this->createAlbums();
        $this->createTracks();
        $this->createListens();
    }

    private function createProfiles() {
        echo "Creating users and profiles";

        $profiles = [];

        foreach (self::$userNames as $name) {
            $user = $this->createUser($name, "demo");
            foreach (self::$profileNames as $profileName) {
                $profile = new Profile();
                $profile->setName($profileName);
                $profile->setUser($user);
                $profile->setIsPublic(true);
                $profiles[] = $profile;
                $this->em->persist($profile);
                echo ".";
            }
        }
        $this->em->flush();

        $rows = $this->getUsers();
        $rowNum = 0;
        while ($row = $rows->fetchArray()) {
            $idx = $rowNum % count($profiles);
            $name = $row["name"];
            $this->profiles[$name] = $profiles[$idx];
            $rowNum++;
        }
        echo " done\n";
    }

    private function createArtists() {
        $numRows = $this->getNumArtists();
        echo "Creating artists ($numRows)\n";

        $rows = $this->getArtists();
        $artist = null;
        $curRow = -1;
        while ($row = $rows->fetchArray()) {
            $curRow++;
            $info = "$curRow / $numRows";
            echo $this->progressBar($curRow, $numRows, $info);

            $artist = $this->addArtist($artist, $row);
        }
        echo $this->progressBar($curRow, $numRows, "flushing...  ");
        $this->em->flush();
        echo $this->progressBar($curRow, $numRows, "done         ");
        echo "\n";
    }

    private function createAlbums() {
        $numRows = $this->getNumAlbums();
        echo "Creating albums ($numRows)\n";

        $rows = $this->getAlbums();
        $album = null;
        $curRow = -1;
        while ($row = $rows->fetchArray()) {
            $curRow++;
            $info = "$curRow / $numRows";
            echo $this->progressBar($curRow, $numRows, $info);

            if ($curRow > 0 && $curRow % self::$batchSize == 0) {
                echo $this->progressBar($curRow, $numRows, "flushing...    ");
                $this->em->flush();
                $this->em->clear();

                // get previous album from db again
                if ($album) {
                    $album = $this->albumRepository->find($album->getId());
                }
            }

            $album = $this->addAlbum($album, $row);
        }
        echo $this->progressBar($curRow, $numRows, "flushing...  ");
        $this->em->flush();
        echo $this->progressBar($curRow, $numRows, "done         ");
        echo "\n";
    }

    private function createTracks() {
        $numRows = $this->getNumTracks();
        echo "Creating tracks ($numRows)\n";

        $rows = $this->getTracks();
        $track = null;
        $curRow = -1;
        while ($row = $rows->fetchArray()) {
            $curRow++;
            $info = "$curRow / $numRows";
            echo $this->progressBar($curRow, $numRows, $info);

            if ($curRow > 0 && $curRow % self::$batchSize == 0) {
                echo $this->progressBar($curRow, $numRows, "flushing...    ");
                $this->em->flush();
                $this->em->clear();

                // get previous track from db again
                if ($track) {
                    $track = $this->trackRepository->find($track->getId());
                }
            }

            $track = $this->addTrack($track, $row);
        }
        echo $this->progressBar($curRow, $numRows, "flushing...    ");
        $this->em->flush();
        echo $this->progressBar($curRow, $numRows, "done           ");
        echo "\n";
    }

    private function createListens() {
        $numRows = $this->getNumListens();
        echo "Creating listens ($numRows)\n";

        $rows = $this->getListens();
        $listen = null;
        $curRow = -1;
        while ($row = $rows->fetchArray()) {
            $curRow++;
            $info = "$curRow / $numRows";
            echo $this->progressBar($curRow, $numRows, $info);

            if ($curRow % self::$batchSize == 0) {
                echo $this->progressBar($curRow, $numRows, "flushing...    ");
                $this->em->flush();
                $this->em->clear();

                // get previous listen from db again
                if ($listen) {
                    $listen = $this->listenRepository->find($listen->getId());
                }

                // refresh profiles in array
                foreach ($this->profiles as $name => $profile) {
                    $profile = $this->profileRepository->find($profile->getId());
                    $this->profiles[$name] = $profile;
                }
            }

            $listen = $this->addListen($listen, $row);
        }
        echo $this->progressBar($curRow, $numRows, "flushing...    ");
        $this->em->flush();
        echo $this->progressBar($curRow, $numRows, "done           ");
        echo "\n";
    }

    private function addArtist(?Artist $artist, Array $fields): ?Artist {
        // get info
        $name = $fields["name"];
        $mbid = $fields["mbid"] ?? "";

        // sanity check
        assert($name !== null && $name !== "", "NO NAME");

        // don't add same artist again
        if ($this->isSameArtist($artist, $name)) return $artist;

        // create artist
        $artist = new Artist();
        $artist->setName($name);
        $artist->setMbid($mbid);
        $this->em->persist($artist);
        return $artist;
    }

    private function addAlbum(?Album $album, Array $fields): ?Album {
        // get info
        $title = $fields["title"];
        $mbid = $fields["mbid"] ?? "";
        $artistName = $fields["artist_name"];

        // sanity check
        assert($title !== null && $title !== "", "NO TITLE");
        assert($artistName != null && $artistName != "", "NO ARTIST NAME");

        // don't add same album again
        $isSameAlbum = $this->isSameAlbum($album, $title, $artistName);
        if ($isSameAlbum) return $album;

        // only fetch artist if it's a different one than before
        $artist = isset($album) ? $album->getArtist() : null;
        $isSameArtist = $this->isSameArtist($artist, $artistName);
        if (!$isSameArtist) {
            $artist = $this->artistRepository->findOneBy(["name" => $artistName]);
            assert($artist !== null, "ARTIST NOT FOUND $artistName");
        }

        // create album
        $album = new Album();
        $album->setTitle($title);
        $album->setMbid($mbid);
        $album->setArtist($artist);
        $this->em->persist($album);
        return $album;
    }

    private function addTrack(?Track $track, Array $fields): ?Track {
        // get info
        $title = $fields["title"];
        $mbid = $fields["mbid"] ?? "";
        $length = intval($fields["length"]);
        $tracknumber = intval($fields["tracknumber"]);
        $artistName = $fields["artist_name"];
        $albumTitle = $fields["album_title"];
        $albumArtistName = $fields["album_artist_name"];

        // sanity check
        assert($title !== "", "NO TITLE");
        assert($artistName !== "", "NO ARTIST NAME");

        // don't add same track again
        $isSameTrack = $this->isSameTrack(
            $track, $artistName, $title, $tracknumber, $albumTitle, $albumArtistName
        );
        if ($isSameTrack) return $track;

        // only fetch artist if it's a different one than before
        $artist = isset($track) ? $track->getArtist() : null;
        $isSameArtist = $this->isSameArtist($artist, $artistName);
        if (!$isSameArtist) {
            $artist = $this->artistRepository->findOneBy(["name" => $artistName]);
            assert($artist !== null, "ARTIST NOT FOUND $artistName");
        }

        // only fetch album if it's a different one than before, and title and album artist name are given
        $album = isset($track) && !empty($albumTitle) ? $track->getAlbum() : null;
        $isSameAlbum = $this->isSameAlbum($album, $albumTitle, $albumArtistName);
        if (!$isSameAlbum && !empty($albumTitle) && !empty($albumArtistName)) {
            $albumArtist = $this->artistRepository->findOneBy(["name" => $albumArtistName]);
            $album = $this->albumRepository->findOneBy(["title" => $albumTitle, "artist" => $albumArtist]);
            assert($album !== null, "ALBUM NOT FOUND $albumTitle");
        }

        // create track
        $track = new Track();
        $track->setTitle($title);
        $track->setMbid($mbid);
        $track->setLength($length);
        $track->setTracknumber($tracknumber);
        $track->setArtist($artist);
        $track->setAlbum($album);
        $this->em->persist($track);
        return $track;
    }

    /**
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    private function addListen(?Listen $listen, Array $fields): ?Listen {
        // get info
        $timestamp = $fields["timestamp"];
        $userName = $fields["user_name"];
        $trackTitle = $fields["track_title"];
        $tracknumber = intval($fields["tracknumber"]);
        $artistName = $fields["artist_name"];
        $albumTitle = $fields["album_title"];
        $albumArtistName = $fields["album_artist_name"];

        // sanity check
        assert($timestamp !== null && $timestamp !== "", "NO TIMESTAMP");
        assert($userName !== null && $userName !== "", "NO USER NAME");
        assert($trackTitle !== null && $trackTitle !== "", "NO TRACK TITLE");

        // create date: add offset so listens are more recent
        $date = new DateTime($timestamp, new DateTimeZone("UTC"));
        $unixTime = $date->getTimestamp() + $this->dateOffset;
        $date = new DateTime("now", new DateTimeZone("UTC"));
        $date->setTimestamp($unixTime);

        // get profile from array
        $profile = $this->profiles[$userName];
        assert($profile !== null, "PROFILE FOR $userName NOT IN \$this->pofiles[]");

        // only fetch artist if it's a different one than before
        $artist = isset($listen) ? $listen->getArtist() : null;
        $isSameArtist = $this->isSameArtist($artist, $artistName);
        if (!$isSameArtist) {
            $artist = $this->artistRepository->findOneBy(["name" => $artistName]);
            assert($artist !== null, "ARTIST NOT FOUND $artistName");
        }

        // only fetch album if it's a different one than before, and title and album artist name are given
        $album = isset($listen) && !empty($albumTitle) ? $listen->getAlbum() : null;
        $isSameAlbum = $this->isSameAlbum($album, $albumTitle, $albumArtistName);
        if (!$isSameAlbum && !empty($albumTitle) && !empty($albumArtistName)) {
            $albumArtist = $this->artistRepository->findOneBy(["name" => $albumArtistName]);
            $album = $this->albumRepository->findOneBy(["title" => $albumTitle, "artist" => $albumArtist]);
            assert($album !== null, "ALBUM NOT FOUND $albumTitle");
        }

        // only fetch track if it's a different one than before
        $track = isset($listen) ? $listen->getTrack() : null;
        $isSameTrack = $this->isSameTrack(
            $track, $artistName, $trackTitle, $tracknumber, $albumTitle, $albumArtistName
        );
        if (!$isSameTrack) {
            $criteria = [
                "title" => $trackTitle,
                "artist" => $artist,
                "album" => $album,
                "tracknumber" => $tracknumber
            ];
            $track = $this->trackRepository->findOneBy($criteria);
            assert($track !== null, "TRACK NOT FOUND $artistName - $trackTitle");
        }

        // create listen
        $listen = new Listen();
        $listen->setProfile($profile);
        $listen->setDate($date);
        $listen->setArtist($artist);
        $listen->setTrack($track);
        $listen->setAlbum($album);
        $this->em->persist($listen);
        return $listen;
    }

    private function isSameArtist(?Artist $artist, ?String $name): bool {
        if (!$artist) return false;
        return $artist->getName() === $name;
    }

    private function isSameAlbum(?Album $album, ?String $title, ?String $artistName): bool {
        if (!$album) return false;
        $isSameArtist = $this->isSameArtist($album->getArtist(), $artistName);
        return $isSameArtist && $album->getTitle() === $title;
    }

    private function isSameTrack(
        ?Track $track, String $artistName, String $title, int $tracknumber,
        ?String $albumTitle, ?String $albumArtistName
    ): bool {
        if (!$track) return false;

        $isSameArtist = $this->isSameArtist($track->getArtist(), $artistName);
        $isSameTitle = $track->getTitle() === $title;
        $isSameTracknumber = $track->getTracknumber() === $tracknumber;
        $isSameTrack = $isSameArtist && $isSameTitle && $isSameTracknumber;

        $album = $track->getAlbum();
        if ($album) {
            $isSameAlbum = $this->isSameAlbum($album, $albumTitle, $albumArtistName);
            $isSameTrack = $isSameTrack && $isSameAlbum;
        }

        return $isSameTrack;
    }

    private function getNumArtists(): int {
        $query = "SELECT COUNT(*) FROM artists;";
        $numRows = $this->dataset->querySingle($query);
        return $numRows;
    }

    private function getNumAlbums(): int {
        $query = "SELECT COUNT(*) FROM albums;";
        $numRows = $this->dataset->querySingle($query);
        return $numRows;
    }

    private function getNumTracks(): int {
        $query = "SELECT COUNT(*) FROM tracks;";
        $numRows = $this->dataset->querySingle($query);
        return $numRows;
    }

    private function getNumListens(): int {
        $query = "SELECT COUNT(*) FROM (SELECT * FROM listens GROUP BY user_id, timestamp);";
        $numRows = $this->dataset->querySingle($query);
        return $numRows;
    }

    private function getUsers() {
        $query = "SELECT name FROM users ORDER BY name;";
        $rows = $this->dataset->query($query);
        return $rows;
    }

    private function getArtists() {
        $query = "SELECT name, mbid FROM artists ORDER BY name;";
        $rows = $this->dataset->query($query);
        return $rows;
    }

    private function getAlbums() {
        $query = "SELECT t1.title, t1.mbid, t2.name AS artist_name
                  FROM albums AS t1
                  INNER JOIN artists AS t2
                  ON t1.artist_id = t2.id
                  ORDER BY artist_name, t1.title;
        ";
        $rows = $this->dataset->query($query);
        return $rows;
    }

    private function getTracks() {
        $query = "SELECT
                        t1.title, t1.mbid, t1.length, t1.tracknumber,
                        t2.name AS artist_name,
                        t3.title AS album_title,
                        t4.name AS album_artist_name
                    FROM tracks AS t1
                    INNER JOIN artists AS t2
                    ON t1.artist_id = t2.id
                    LEFT JOIN albums AS t3
                    ON t1.album_id = t3.id
                    LEFT JOIN artists AS t4
                    ON t3.artist_id = t4.id
                    ORDER BY artist_name, album_title, t1.tracknumber, t1.title;
        ";
        $rows = $this->dataset->query($query);
        return $rows;
    }

    private function getListens() {
        $query = "  SELECT
                        t1.timestamp,
                        t2.name AS user_name,
                        t3.title AS track_title, t3.tracknumber,
                        t4.name AS artist_name,
                        t5.title AS album_title,
                        t6.name AS album_artist_name
                    FROM listens AS t1
                    INNER JOIN users AS t2
                    ON t1.user_id = t2.id
                    INNER JOIN tracks as t3
                    ON t1.track_id = t3.id
                    INNER JOIN artists AS t4
                    ON t3.artist_id = t4.id
                    LEFT JOIN albums AS t5
                    ON t3.album_id = t5.id
                    LEFT JOIN artists AS t6
                    ON t5.artist_id = t6.id
                    GROUP BY user_name, timestamp
                    ORDER BY user_name, artist_name, album_title, tracknumber, track_title;
        ";
        $rows = $this->dataset->query($query);
        return $rows;
    }

    private function createUser(String $name, String $password) {
        $user = new User();
        $pass = $this->hasher->hashPassword($user, $password);
        $email = "$name@example.com";

        $user
            ->setName($name)
            ->setPassword($pass)
            ->setEmail($email);

        $this->em->persist($user);
        $this->em->flush();
        return $user;
    }

    // https://gist.github.com/mayconbordin/2860547
    function progressBar($done, $total, $info="", $width=50) {
        $perc = round(($done * 100) / $total);
        $bar = round(($width * $perc) / 100);
        return sprintf("%s%%[%s>%s]%s\r", $perc, str_repeat("=", $bar), str_repeat(" ", $width-$bar), $info);
    }
}
