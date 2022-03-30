<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $this->createUser("demo", "demo", $manager);
        $manager->flush();
    }

    private function createUser(String $name, String $password, ObjectManager $manager) {
        $user = new User();
        $pass = $this->hasher->hashPassword($user, $password);
        $email = $name . "@example.com";

        $user
            ->setName($name)
            ->setPassword($pass)
            ->setEmail($email);

        $manager->persist($user);
    }
}
