<?php

declare(strict_types=1);

namespace App\QueryBuilder;

use Doctrine\DBAL\Query\QueryBuilder;

abstract class BaseQueryBuilder extends QueryBuilder {
    const ITEMS_PER_PAGE = 10;

    public function page(int $page = 1) {
        $offset = ($page - 1) * self::ITEMS_PER_PAGE;

        $this->setFirstResult($offset);
        $this->setMaxResults(self::ITEMS_PER_PAGE);
        return $this;
    }
}
