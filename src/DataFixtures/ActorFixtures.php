<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Actor;
use App\Entity\Program;
use App\Repository\ProgramRepository;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ActorFixtures extends Fixture implements DependentFixtureInterface
{
    const SERIES = ['Drôle', 'Lupin', 'Chasseurs de Trolls', 'Le jeu de la Dame', 'Sex education'];

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i = 0; $i < 10; $i++) {
            $actor = new Actor();
            $actor->setName($faker->name());
            $manager->persist($actor);
            for($j = 0; $j <=3; $j++) {
                $actor->addProgram($this->getReference(self::SERIES[rand(0,4)]));
            }
        }
        $manager->flush();
    }
    
    public function getDependencies()
    {
        return [
            ProgramFixtures::class,
        ];
    }
}
