<?php

declare(strict_types=1);

namespace App\QueryBuilder;

use DateInterval;
use DateTime;
use DateTimeImmutable;
use DateTimeZone;
use InvalidArgumentException;
use App\Entity\{Album, Artist, Profile, Track, User};
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\Types;

/**
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 */
class ListenQueryBuilder extends BaseQueryBuilder {
    protected String $alias;

    public function __construct(Connection $conn, String $alias = "l")
    {
        parent::__construct($conn);

        // initialize qb
        $this->alias = $alias;
        $tableName = self::TABLE_NAMES["listen"];
        $this->from($tableName, $this->alias);
    }

    public function all(): static
    {
        $this
            ->joinUserTable()
            ->joinProfileTable()
            ->joinArtistTable()
            ->joinAlbumTable()
            ->joinAlbumArtistTable()
            ->joinTrackTable();

        $alias = $this->alias;
        $userAlias = $this->joins[self::TABLE_NAMES["user"]];
        $profileAlias = $this->joins[self::TABLE_NAMES["profile"]];
        $artistAlias = $this->joins[self::TABLE_NAMES["artist"]];
        $albumAlias = $this->joins[self::TABLE_NAMES["album"]];
        $trackAlias = $this->joins[self::TABLE_NAMES["track"]];
        $albumArtistAlias = $this->joins["AlbumArtist"];

        return $this->select(
            "$alias.date AS timestamp",
            "$userAlias.name as user_name",
            "$profileAlias.name AS profile_name",
            "$profileAlias.is_public AS public",
            "$artistAlias.name AS artist_name",
            "$albumAlias.title AS album_title",
            "$albumArtistAlias.name AS album_artist_name",
            "$trackAlias.title AS track_title"
        )->orderBy("timestamp", "DESC");
    }

    public function year(DateTime $start): static
    {
        $end = clone $start;
        $end->add(new DateInterval("P1Y"));
        return $this->daterange($start, $end);
    }

    public function month(DateTime $start): static
    {
        $end = clone $start;
        $end->add(new DateInterval("P1M"));
        return $this->daterange($start, $end);
    }

    public function week(DateTime $start): static
    {
        $end = clone $start;
        $end->add(new DateInterval("P7D"));
        return $this->daterange($start, $end);
    }

    public function day(DateTime $start): static
    {
        $end = clone $start;
        $end->add(new DateInterval("P1D"));
        return $this->daterange($start, $end);
    }

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function daterange(DateTime $start, ?DateTime $end = null): static
    {
        // set end time to now if no end was given
        $end = $end ?? new DateTime("now", new DateTimeZone("UTC"));

        // make immutable
        $start = DateTimeImmutable::createFromMutable($start);
        $end = DateTimeImmutable::createFromMutable($end);

        $alias = $this->alias;
        $this->andWhere("$alias.date >= :_start AND $alias.date < :_end");
        $this->setParameter("_start", $start, Types::DATETIME_IMMUTABLE);
        $this->setParameter("_end", $end, Types::DATETIME_IMMUTABLE);
        return $this;
    }

    public function filter($obj): static
    {
        if ($obj instanceof Album) return $this->filterByAlbum($obj);
        else if ($obj instanceof Artist) return $this->filterByArtist($obj);
        else if ($obj instanceof Profile) return $this->filterByProfile($obj);
        else if ($obj instanceof Track) return $this->filterByTrack($obj);
        else if ($obj instanceof User) return $this->filterByUser($obj);

        throw new InvalidArgumentException("filtering is only supported for certain entity instances");
    }

    public function filterByUser(User $user): static
    {
        // get all profile ids belonging to this user
        $profileIds = [];
        foreach ($user->getProfiles() as $profile) {
            $profileIds[] = $profile->getId();
        }

        $alias = $this->alias;
        $this->andWhere("$alias.profile_id IN (:profiles)");
        $this->setParameter("profiles", $profileIds, Connection::PARAM_INT_ARRAY);
        return $this;
    }

    public function filterByProfile(Profile $profile): static
    {
        $alias = $this->alias;
        $this->andWhere("$alias.profile_id = :profile");
        $this->setParameter("profile", $profile->getId());
        return $this;
    }

    public function filterByArtist(Artist $artist): static
    {
        $alias = $this->alias;
        $this->andWhere("$alias.artist_id = :artist");
        $this->setParameter("artist", $artist->getId());
        return $this;
    }

    public function filterByAlbum(Album $album): static
    {
        $alias = $this->alias;
        $this->andWhere("$alias.album_id = :album");
        $this->setParameter("album", $album->getId());
        return $this;
    }

    public function filterByAlbumArtist(Artist $artist): static
    {
        $this->joinAlbumTable();
        $albumAlias = $this->joins[self::TABLE_NAMES["album"]];

        $this->andWhere("$albumAlias.artist_id = :artist");
        $this->setParameter("artist", $artist->getId());
        return $this;
    }

    public function filterByTrack(Track $track): static
    {
        $alias = $this->alias;
        $this->andWhere("$alias.track_id = :track");
        $this->setParameter("track", $track->getId());
        return $this;
    }

    public function public(): static
    {
        $this->joinProfileTable();
        $profileAlias = $this->joins[self::TABLE_NAMES["profile"]];
        $this->andWhere("$profileAlias.is_public = :_public");
        $this->setParameter("_public", true);
        return $this;
    }

    protected function joinUserTable(): static
    {
        $alias = $this->alias;
        $userTable = self::TABLE_NAMES["user"];
        $profileTable = self::TABLE_NAMES["profile"];

        // join user table if not yet joined
        $isJoined = in_array($userTable, array_keys($this->joins));
        if (!$isJoined) {
            // profile table needs to be joined
            $this->joinProfileTable();
            $profileAlias = $this->joins[$profileTable];
            $this->innerJoin($alias, $userTable, "u", "$profileAlias.user_id = u.id");
        }

        return $this;
    }

    protected function joinProfileTable(): static
    {
        $alias = $this->alias;
        $profileTable = self::TABLE_NAMES["profile"];

        // join profile table if not yet joined
        $isJoined = in_array($profileTable, array_keys($this->joins));
        if (!$isJoined) {
            $this->innerJoin($alias, $profileTable, "p", "$alias.profile_id = p.id");
        }

        return $this;
    }

    protected function joinArtistTable(): static
    {
        $alias = $this->alias;
        $artistTable = self::TABLE_NAMES["artist"];

        // join artist table if not yet joined
        $isJoined = in_array($artistTable, array_keys($this->joins));
        if (!$isJoined) {
            $this->innerJoin($alias, $artistTable, "a", "$alias.artist_id = a.id");
        }

        return $this;
    }

    protected function joinAlbumTable(): static
    {
        $alias = $this->alias;
        $albumTable = self::TABLE_NAMES["album"];

        // album might not exist, so we're left-joining the album table
        $isJoined = in_array($albumTable, array_keys($this->joins));
        if (!$isJoined) {
            $this->leftJoin($alias, $albumTable, "al", "$alias.album_id = al.id");
            $this->joins[$albumTable] = "al";
        }

        return $this;
    }

    protected function joinAlbumArtistTable(): static
    {
        $alias = $this->alias;
        $artistTable = self::TABLE_NAMES["artist"];
        $albumTable = self::TABLE_NAMES["album"];

        // join album artist (artist table with a different alias)
        $isJoined = in_array("AlbumArtist", array_keys($this->joins));
        if (!$isJoined) {
            // preserve alias of artist table join, if any
            $artistAlias = $this->joins[$artistTable] ?? null;

            // join album table
            $this->joinAlbumTable();
            $albumAlias = $this->joins[$albumTable];

            // left join for album artist with alias aa
            $this->leftJoin($alias, $artistTable, "aa", "$albumAlias.artist_id = aa.id");

            // save alias for album artist "table"
            $this->joins["AlbumArtist"] = "aa";

            // set real alias for artist table again
            $this->joins[$artistTable] = $artistAlias;

            // delete entry in joins if artist alias is null, i.e. the join doesn't exist
            if ($artistAlias === null) unset($this->joins[$artistTable]);
        }

        return $this;
    }

    protected function joinTrackTable(): static
    {
        $alias = $this->alias;
        $trackTable = self::TABLE_NAMES["track"];

        // join track table if not yet joined
        $isJoined = in_array($trackTable, array_keys($this->joins));
        if (!$isJoined) {
            $this->innerJoin($alias, $trackTable, "t", "$alias.track_id = t.id");
        }

        return $this;
    }
}
