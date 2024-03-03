<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Enum\GenderEnum;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        // Create a regular user
        $user = new User();
        $user->setEmail('user@example.com');
        $user->setUsername('user');
        $user->setGender(GenderEnum::FEMALE);
        $user->setPassword($this->passwordHasher->hashPassword($user, 'userpassword'));
//        $user->setRoles(['ROLE_USER']);
        $manager->persist($user);

        // Create an admin user
        $admin = new User();
        $admin->setEmail('admin@example.com');
        $admin->setUsername('admin');
        $admin->setGender(GenderEnum::MALE);
        $admin->setPassword($this->passwordHasher->hashPassword($admin, 'adminpassword'));
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setIsVerified(true);
        $manager->persist($admin);

        $manager->flush();
    }
}
