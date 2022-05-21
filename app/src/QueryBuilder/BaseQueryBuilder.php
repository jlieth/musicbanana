<?php

declare(strict_types=1);

namespace App\QueryBuilder;

use Doctrine\DBAL\Query\QueryBuilder;

abstract class BaseQueryBuilder extends QueryBuilder {
    const ITEMS_PER_PAGE = 10;
    const TABLE_NAMES = [
        "album" => "Album",
        "artist" => "Artist",
        "listen" => "Listen",
        "profile" => "Profile",
        "track" => "Track",
        "user" => "usr",
    ];

    protected array $joins = [];

    public function innerJoin($fromAlias, $join, $alias, $condition = null): static
    {
        // keep track of joined tables
        $this->joins[$join] = $alias;

        return parent::innerJoin($fromAlias, $join, $alias, $condition);
    }

    public function page(int $page = 1): static
    {
        $offset = ($page - 1) * self::ITEMS_PER_PAGE;

        $this->setFirstResult($offset);
        $this->setMaxResults(self::ITEMS_PER_PAGE);
        return $this;
    }
}
