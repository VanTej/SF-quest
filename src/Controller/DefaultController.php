<?php

namespace App\Controller;

use App\Repository\SeasonRepository;
use App\Repository\EpisodeRepository;
use App\Repository\ProgramRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class DefaultController extends AbstractController

{
    #[Route('/', name: 'app_index')]
    public function index(ProgramRepository $programRepository, SeasonRepository $seasonRepository, EpisodeRepository $episodeRepository): Response
    {
        $programs = $programRepository->findAll();
        $seasons = $seasonRepository->findAll();
        $episodes = $episodeRepository->findAll();
        $lastPrograms = $programRepository->findBy([], ['id' => 'DESC'], 3);
        return $this->render('home/index.html.twig', [
            'nbPrograms' => count($programs),
            'nbSeasons' => count($seasons),
            'nbEpisodes' => count($episodes),
            'lastPrograms' => $lastPrograms
        ]);
    }

    public function navbarTop(CategoryRepository $categoryRepository): Response
    {
        return $this->render('includes/_navbartop.html.twig', [
            'categories' => $categoryRepository->findBy([], ['id' => 'DESC'])
        ]);
    }
}
