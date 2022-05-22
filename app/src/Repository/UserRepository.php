<?php

namespace App\Repository;

use App\Entity\User;
use App\QueryBuilder\ListenQueryBuilder;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Connection;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    const ITEMS_PER_PAGE = 10;
    private Connection $connection;

    public function __construct(ManagerRegistry $registry, Connection $connection)
    {
        parent::__construct($registry, User::class);
        $this->connection = $connection;
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newHashedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    private function listenQB($alias = "l"): ListenQueryBuilder {
        return new ListenQueryBuilder($this->connection, $alias);
    }

    public function getListens(User $user, int $page = 1): ListenQueryBuilder {
        $qb = $this->listenQB("l")
            ->select(
                "l.date AS timestamp",
                "p.name AS profile_name",
                "p.is_public AS public",
                "a.name AS artist",
                "t.title AS track"
            )
            ->innerJoin("l", "Profile", "p", "l.profile_id = p.id")
            ->innerJoin("l", "Artist", "a", "l.artist_id = a.id")
            ->innerJoin("l", "Track", "t", "l.track_id = t.id")
            ->orderBy("l.date", "DESC")
            ->filterByUser($user)
            ->page($page);

        return $qb;
    }

    // /**
    //  * @return User[] Returns an array of User objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
