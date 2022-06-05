<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $contributor = new User();
        $contributor->setEmail('contrib@wildseries.com');
        $contributor->setRoles(['ROLE_CONTRIBUTOR']);
        $passwordContributor = 'toto';
        $hashedPassword = $this->passwordHasher->hashPassword(
            $contributor,
            $passwordContributor
        );
        $contributor->setPassword($hashedPassword);
        $manager->persist($contributor);
        
        $admin = new User();
        $admin->setEmail('admin@wildseries.com');
        $admin->setRoles(['ROLE_ADMIN']);
        $passwordAdmin = 'titi';
        $hashedPassword = $this->passwordHasher->hashPassword(
            $admin,
            $passwordAdmin
        );
        $admin->setPassword($hashedPassword);
        $manager->persist($admin);


        $manager->flush();
    }
}
