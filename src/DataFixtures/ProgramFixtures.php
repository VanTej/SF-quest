<?php

namespace App\DataFixtures;

use App\Entity\Program;
use App\Service\Slugify;
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

    public function __construct(private Slugify $slugify)
    {
    }

    public function load(ObjectManager $manager): void
    {
        foreach (self::SERIES as $serie) {
            $program = new Program();
            $program->setTitle($serie['title']);
            $slug = $this->slugify->generate($serie['title']);
            $program->setSlug($slug);
            $program->setSynopsis($serie['synopsis']);
            $program->setCategory($this->getReference($serie['category']));
            $this->addReference($serie['title'], $program);
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
