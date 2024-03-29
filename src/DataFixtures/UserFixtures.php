<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $admin = new User();
        $admin->setEmail('admin@example.com');
        $adminPassword = $this->hasher->hashPassword($admin, 'admin1234');
        $admin->setPassword($adminPassword);
        $admin->setRoles(['ROLE_ADMIN']);
        $manager->persist($admin);

        $utils = new Utils();

        for ($i = 0; $i < 10; $i++) {
            $user = new User();
            $utils->setIdentity($user);
            $userPassword = $this->hasher->hashPassword($user, $user->getFirstname() . '1234');
            $user->setPassword($userPassword);
            $manager->persist($user);
        }

        $manager->flush();
    }
}