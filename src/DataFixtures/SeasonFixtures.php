<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Season;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class SeasonFixtures extends Fixture implements DependentFixtureInterface
{
    const SERIES = ['Drôle', 'Lupin', 'Chasseurs de Trolls', 'Le jeu de la Dame', 'Sex education'];

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i = 0; $i < 5; $i++) {
            for ($j = 1; $j <= 5; $j++) {
                $season = new Season();

                $season->setNumber($j);
                $season->setYear(2000 + $j);
                $season->setDescription($faker->paragraphs(3, true));

                $season->setProgram($this->getReference(self::SERIES[$i]));

                $this->addReference(self::SERIES[$i] . ' S' . $j, $season);
                $manager->persist($season);
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
