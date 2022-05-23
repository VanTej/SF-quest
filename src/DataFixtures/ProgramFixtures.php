<?php

namespace App\DataFixtures;

use App\Entity\Program;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ProgramFixtures extends Fixture implements DependentFixtureInterface
{
    const SERIES = [
        ['title' => 'Drôle', 'synopsis' => 'Portrait de jeunes artistes du stand-up en quête de succès.', 'category' => 'category_Comédie'],
        ['title' => 'Lupin', 'synopsis' => 'Gentleman cambrioleur obsédé par sa soif de vengeance.', 'category' => 'category_Aventure'],
        ['title' => 'Chasseurs de Trolls', 'synopsis' => 'Jimmy trouve une amulette magique qui le transforme en chasseur de Trolls.', 'category' => 'category_Animation'],
        ['title' => 'Le jeu de la Dame', 'synopsis' => 'Une joueuse exceptionnelle dans les années 50 parmi les hommes.', 'category' => 'category_Aventure'],
        ['title' => 'Sex education', 'synopsis' => 'Des lycéens anglais découvrent le sexe sans tabou, ou presque !', 'category' => 'category_Comédie'],
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::SERIES as $serie) {
            $program = new Program();
            $program->setTitle($serie['title']);
            $program->setSynopsis($serie['synopsis']);
            $program->setCategory($this->getReference($serie['category']));
            $manager->persist($program);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            CategoryFixtures::class,
        ];
    }
}
