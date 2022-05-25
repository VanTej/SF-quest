<?php

namespace App\Controller;

use App\Repository\EpisodeRepository;
use App\Repository\ProgramRepository;
use App\Repository\SeasonRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/program', name: 'program_')]
Class ProgramController extends AbstractController

{
    #[Route('/', name: 'index')]
    public function index(ProgramRepository $programRepository): Response
    {
        $programs = $programRepository->findAll();

        return $this->render('program/index.html.twig', [
            'website' => 'Wild Series',
            'programs' => $programs,
        ]);
    }

    #[Route('/{id<\d+>}', methods: ['GET'], name: 'show')]
    public function show(int $id, ProgramRepository $programRepository, SeasonRepository $seasonRepository): Response
    {
        $program = $programRepository->findOneBy(['id' => $id]);
        $seasons = $seasonRepository->findByProgram($program);
        return $this->render('program/show.html.twig', [
            'id' => $id,
            'program' => $program,
            'seasons' => $seasons,
         ]);
    }

    #[Route('/{programId<\d+>}/season/{seasonId<\d+>}', methods: ['GET'], name: 'season_show')]
    public function showSeason(int $programId, int $seasonId, ProgramRepository $programRepository, SeasonRepository $seasonRepository, EpisodeRepository $episodeRepository): Response
    {
        $program = $programRepository->findOneBy(['id' => $programId]);
        $season = $seasonRepository->findOneBy(['program' => $program, 'id' => $seasonId]);
        $episodes = $episodeRepository->findBySeason($season);
        return $this->render('program/season_show.html.twig', [
            'program' => $program,
            'season' => $season,
            'episodes' => $episodes,
         ]);
    }
    
}
