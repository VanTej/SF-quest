<?php

namespace App\DataFixtures;

use App\Entity\Season;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class SeasonFixtures extends Fixture implements DependentFixtureInterface
{
    const SEASONS = [
        ['number' => 1, 'year' => 2000, 'description' => 'saison 1', 'program' => 'Drôle'],
        ['number' => 1, 'year' => 2001, 'description' => 'saison 1', 'program' => 'Lupin'],
        ['number' => 1, 'year' => 2002, 'description' => 'saison 1', 'program' => 'Chasseurs de Trolls'],
        ['number' => 1, 'year' => 2003, 'description' => 'saison 1', 'program' => 'Le jeu de la Dame'],
        ['number' => 1, 'year' => 2004, 'description' => 'saison 1', 'program' => 'Sex education'],
        ['number' => 2, 'year' => 2005, 'description' => 'saison 2', 'program' => 'Drôle'],
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::SEASONS as $seasonFixture) {
            $season = new Season();
            $season->setNumber($seasonFixture['number']);
            $season->setYear($seasonFixture['year']);
            $season->setDescription($seasonFixture['description']);
            $season->setProgram($this->getReference($seasonFixture['program']));
            $this->addReference($seasonFixture['program'] . ' S' . $seasonFixture['number'], $season);
            $manager->persist($season);
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
