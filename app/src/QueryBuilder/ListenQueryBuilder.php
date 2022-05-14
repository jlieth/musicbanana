<?php

declare(strict_types=1);

namespace App\QueryBuilder;

use Doctrine\DBAL\Connection;
use App\Entity\User;

class ListenQueryBuilder extends BaseQueryBuilder {
    public function filterByUser(User $user) {
        // get all profile ids belonging to this user
        $profileIds = [];
        foreach ($user->getProfiles() as $profile) {
            $profileIds[] = $profile->getId();
        }

        $this->andWhere("l.profile_id IN (:profiles)");
        $this->setParameter("profiles", $profileIds, Connection::PARAM_INT_ARRAY);
        return $this;
    }

    public function public() {
        $this->andWhere("p.is_public = :_public");
        $this->setParameter("_public", true);
        return $this;
    }
}
