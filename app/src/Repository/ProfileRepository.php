<?php

namespace App\Repository;

use DateTimeImmutable;
use DateTimeZone;
use App\Entity\{Profile, User};
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Query\Parameter;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Profile|null find($id, $lockMode = null, $lockVersion = null)
 * @method Profile|null findOneBy(array $criteria, array $orderBy = null)
 * @method Profile[]    findAll()
 * @method Profile[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProfileRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, Connection $connection)
    {
        $this->conn = $connection;
        parent::__construct($registry, Profile::class);
    }

    public function getOrCreate(string $name, User $user, ?array $defaults): Profile {
        $lookup = ["name" => $name, "user" => $user];
        $profile = $this->findOneBy($lookup);

        if (!$profile) {
            $now = new DateTimeImmutable("now", new DateTimeZone("UTC"));

            $parameters = [
                "name" => $name,
                "userid" => $user->getId(),
                "ispublic" => $defaults["isPublic"] ?? false,
                //"created" => new Parameter($now, Types::DATETIMETZ_IMMUTABLE)
            ];

            $this->conn->createQueryBuilder()
                ->insert("Profile")
                ->setValue("name", ":name")
                ->setValue("user_id", ":userid")
                ->setValue("is_public", ":ispublic")
                ->setValue("created", ":created")
                ->setParameters($parameters)
                ->setParameter("created", $now, Types::DATETIMETZ_IMMUTABLE)
                ->execute();

            $profile = $this->findOneBy($lookup);
        }

        return $profile;
    }

    // /**
    //  * @return Profile[] Returns an array of Profile objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Profile
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
