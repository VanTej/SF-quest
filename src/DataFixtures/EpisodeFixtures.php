<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Episode;
use App\Service\Slugify;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{
    const SERIES = ['Drôle', 'Lupin', 'Chasseurs de Trolls', 'Le jeu de la Dame', 'Sex education'];

    public function __construct(private Slugify $slugify)
    {
    }
    
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i = 0; $i < 5; $i++) {
            for ($k = 1; $k <= 5; $k++) {
                for ($j = 1; $j <= 20; $j++) {
                    $episode = new Episode();

                    $episode->setTitle($faker->sentence(4, true));
                    $slug = $this->slugify->generate($episode->getTitle());
                    $episode->setSlug($slug);
                    $episode->setNumber($j);
                    $episode->setSynopsis($faker->paragraphs(3, true));

                    $episode->setSeason($this->getReference(self::SERIES[$i] . ' S' . $k));

                    $manager->persist($episode);
                }
            }
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            SeasonFixtures::class,
        ];
    }
}
