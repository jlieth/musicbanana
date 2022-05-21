<?php

declare(strict_types=1);

namespace App\QueryBuilder;

use App\Entity\User;
use Doctrine\DBAL\Connection;

class ListenQueryBuilder extends BaseQueryBuilder {
    public function filterByUser(User $user): static
    {
        // get all profile ids belonging to this user
        $profileIds = [];
        foreach ($user->getProfiles() as $profile) {
            $profileIds[] = $profile->getId();
        }

        $this->andWhere("l.profile_id IN (:profiles)");
        $this->setParameter("profiles", $profileIds, Connection::PARAM_INT_ARRAY);
        return $this;
    }

    public function public(): static
    {
        $profileTable = self::TABLE_NAMES["profile"];

        // join profile table if not yet joined
        $isJoined = in_array($profileTable, array_keys($this->joins));
        if (!$isJoined) {
            $this->innerJoin("l", $profileTable, "p", "l.profile_id = p.id");
        }

        $alias = $this->joins[$profileTable];
        $this->andWhere("$alias.is_public = :_public");
        $this->setParameter("_public", true);
        return $this;
    }
}
