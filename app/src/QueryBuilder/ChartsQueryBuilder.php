<?php

declare(strict_types=1);

namespace App\QueryBuilder;

class ChartsQueryBuilder extends ListenQueryBuilder {
    public function artists(): static
    {
        $alias = $this->alias;
        $artistTable = self::TABLE_NAMES["artist"];

        // join artist table if not yet joined
        $isJoined = in_array($artistTable, array_keys($this->joins));
        if (!$isJoined) {
            $this->innerJoin($alias, $artistTable, "a", "$alias.artist_id = a.id");
        }

        $otherAlias = $this->joins[$artistTable];
        $this
            ->select(
                "$otherAlias.name AS artist_name",
                "COUNT(*) AS count"
            )
            ->groupBy("$otherAlias.name")
            ->orderBy("count", "DESC")
            ->addOrderBy("artist_name", "ASC");

        return $this;
    }

    public function albums(): static
    {
        $alias = $this->alias;
        $artistTable = self::TABLE_NAMES["artist"];
        $albumTable = self::TABLE_NAMES["album"];

        // join artist table if not yet joined
        $isJoined = in_array($artistTable, array_keys($this->joins));
        if (!$isJoined) {
            $this->innerJoin($alias, $artistTable, "a", "$alias.artist_id = a.id");
        }

        // join album table if not yet joined
        $isJoined = in_array($albumTable, array_keys($this->joins));
        if (!$isJoined) {
            $this->innerJoin($alias, $albumTable, "al", "$alias.album_id = al.id");
        }

        $artistAlias = $this->joins[$artistTable];
        $albumAlias = $this->joins[$albumTable];
        $this->
            select(
                "$artistAlias.name AS artist_name",
                "$albumAlias.title AS album_title",
                "COUNT(*) AS count"
            )
            ->andWhere("$albumAlias.title IS NOT NULL")
            ->groupBy("artist_name", "album_title")
            ->orderBy("count", "DESC")
            ->addOrderBy("artist_name", "ASC")
            ->addOrderBy("album_title", "ASC");

            return $this;
    }

    public function tracks(): static
    {
        $alias = $this->alias;
        $artistTable = self::TABLE_NAMES["artist"];
        $trackTable = self::TABLE_NAMES["track"];

        // join artist table if not yet joined
        $isJoined = in_array($artistTable, array_keys($this->joins));
        if (!$isJoined) {
            $this->innerJoin($alias, $artistTable, "a", "$alias.artist_id = a.id");
        }

        // join track table if not yet joined
        $isJoined = in_array($trackTable, array_keys($this->joins));
        if (!$isJoined) {
            $this->innerJoin($alias, $trackTable, "t", "$alias.track_id = t.id");
        }

        $artistAlias = $this->joins[$artistTable];
        $trackAlias = $this->joins[$trackTable];
        $this->
            select(
                "$artistAlias.name AS artist_name",
                "$trackAlias.title AS track_title",
                "COUNT(*) AS count"
            )
            ->groupBy("artist_name", "track_title")
            ->orderBy("count", "DESC")
            ->addOrderBy("artist_name", "ASC")
            ->addOrderBy("track_title", "ASC");

            return $this;
    }
}
