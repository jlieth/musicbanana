<?php

declare(strict_types=1);

namespace App\QueryBuilder;

class ChartsQueryBuilder extends ListenQueryBuilder {
    public function artists(): static
    {
        $this->joinArtistTable();
        $artistAlias = $this->joins[self::TABLE_NAMES["artist"]];
        return $this
            ->select(
                "$artistAlias.name AS artist_name",
                "COUNT(*) AS count"
            )
            ->groupBy("$artistAlias.name")
            ->orderBy("count", "DESC")
            ->addOrderBy("artist_name", "ASC");
    }

    public function albums(): static
    {
        $this->joinAlbumTable();
        $this->joinAlbumArtistTable();

        $albumArtistAlias = $this->joins["AlbumArtist"];
        $albumAlias = $this->joins[self::TABLE_NAMES["album"]];
        return $this
            ->select(
                "$albumArtistAlias.name AS artist_name",
                "$albumAlias.title AS album_title",
                "COUNT(*) AS count"
            )
            ->andWhere("$albumAlias.title IS NOT NULL")
            ->groupBy("artist_name", "album_title")
            ->orderBy("count", "DESC")
            ->addOrderBy("artist_name", "ASC")
            ->addOrderBy("album_title", "ASC");
    }

    public function tracks(): static
    {
        $this->joinTrackTable();
        $this->joinArtistTable();

        $artistAlias = $this->joins[self::TABLE_NAMES["artist"]];
        $trackAlias = $this->joins[self::TABLE_NAMES["track"]];
        return $this
            ->select(
                "$artistAlias.name AS artist_name",
                "$trackAlias.title AS track_title",
                "COUNT(*) AS count"
            )
            ->groupBy("artist_name", "track_title")
            ->orderBy("count", "DESC")
            ->addOrderBy("artist_name", "ASC")
            ->addOrderBy("track_title", "ASC");
    }

    public function trackList(): static
    {
        $this->joinTrackTable();
        $this->joinArtistTable();

        $artistAlias = $this->joins[self::TABLE_NAMES["artist"]];
        $trackAlias = $this->joins[self::TABLE_NAMES["track"]];
        return $this
            ->select(
                "$artistAlias.name AS artist_name",
                "$trackAlias.title AS track_title",
                "$trackAlias.tracknumber AS tracknumber",
                "COUNT(*) AS count"
            )
            ->groupBy("artist_name", "track_title", "tracknumber")
            ->orderBy("tracknumber", "ASC")
            ->addOrderBy("count", "DESC")
            ->addOrderBy("track_title", "ASC");
    }
}
