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
            ->orderBy("count", "DESC");

        return $this;
    }
}
