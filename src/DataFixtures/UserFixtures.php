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
        $this->addReference($contributor->getEmail(), $contributor);
        $manager->persist($contributor);

        $contributor2 = new User();
        $contributor2->setEmail('contrib2@wildseries.com');
        $contributor2->setRoles(['ROLE_CONTRIBUTOR']);
        $passwordContributor2 = 'toto';
        $hashedPassword = $this->passwordHasher->hashPassword(
            $contributor2,
            $passwordContributor2
        );
        $contributor2->setPassword($hashedPassword);
        $this->addReference($contributor2->getEmail(), $contributor2);
        $manager->persist($contributor2);
        
        $admin = new User();
        $admin->setEmail('admin@wildseries.com');
        $admin->setRoles(['ROLE_ADMIN']);
        $passwordAdmin = 'titi';
        $hashedPassword = $this->passwordHasher->hashPassword(
            $admin,
            $passwordAdmin
        );
        $admin->setPassword($hashedPassword);
        $this->addReference($admin->getEmail(), $admin);
        $manager->persist($admin);


        $manager->flush();
    }
}
