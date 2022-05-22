<?php

declare(strict_types=1);

namespace App\QueryBuilder;

use DateTime;
use DateTimeImmutable;
use DateTimeZone;
use App\Entity\{Album, Artist, Profile, User};
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\Types;

class ListenQueryBuilder extends BaseQueryBuilder {

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

        $this->andWhere("l.date BETWEEN :_start AND :_end");
        $this->setParameter("_start", $start, Types::DATETIME_IMMUTABLE);
        $this->setParameter("_end", $end, Types::DATETIME_IMMUTABLE);
        return $this;
    }

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

    public function filterByProfile(Profile $profile): static
    {
        $this->andWhere("l.profile_id = :profile");
        $this->setParameter("profile", $profile->getId());
        return $this;
    }

    public function filterByArtist(Artist $artist): static
    {
        $this->andWhere("l.artist_id = :artist");
        $this->setParameter("artist", $artist->getId());
        return $this;
    }

    public function filterByAlbum(Album $album): static
    {
        $this->andWhere("l.album_id = :album");
        $this->setParameter("album", $album->getId());
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
