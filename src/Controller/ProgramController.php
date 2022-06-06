<?php

namespace App\Controller;

use App\Entity\Season;
use App\Entity\Comment;
use App\Entity\Episode;
use App\Entity\Program;
use App\Service\Slugify;
use App\Form\CommentType;
use App\Form\ProgramType;
use Symfony\Component\Mime\Email;
use App\Repository\CommentRepository;
use App\Repository\ProgramRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

#[Route('/program', name: 'program_')]
class ProgramController extends AbstractController
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

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, MailerInterface $mailer, ProgramRepository $programRepository, Slugify $slugify): Response
    {
        $this->denyAccessUnlessGranted('ROLE_CONTRIBUTOR', null, 'Vous n\'avez pas les droits pour accéder à cette page.');
        $program = new Program();
        $form = $this->createForm(ProgramType::class, $program);
        $form->handleRequest($request);
        $user = $this->getUser();

        if ($form->isSubmitted() && $form->isValid()) {
            $slug = $slugify->generate($program->getTitle());
            $program->setSlug($slug);
            $program->setAuthor($user);
            $programRepository->add($program, true);

            $this->addFlash('success', 'Votre série est bien enregistrée !');

            $email = (new Email())
                ->from($this->getParameter('mailer_from'))
                ->to('your_email@example.com')
                ->subject('Une nouvelle série vient d\'être publiée !')
                ->html($this->renderView('program/newProgramEmail.html.twig', [
                    'program' => $program,
                ]));


            $mailer->send($email);

            return $this->redirectToRoute('program_index');
        }

        return $this->renderForm('program/new.html.twig', [
            'formProgram' => $form,
        ]);
    }
    /**
     * Require ROLE_CONTRIBUTOR for all the actions of this controller
     */
    #[IsGranted('ROLE_CONTRIBUTOR')]
    #[Route('/{slug}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Program $program, ProgramRepository $programRepository, Slugify $slugify): Response
    {
        if (!($this->getUser() == $program->getAuthor()) && !(in_array('ROLE_ADMIN', $this->getUser()->getRoles()))) {
            throw new AccessDeniedException('Seuls le créateur de cette page et l\'admin peuvent accéder à cette page');
        }
        $form = $this->createForm(ProgramType::class, $program);
        $form->handleRequest($request);
        $user = $this->getUser();

        if ($form->isSubmitted() && $form->isValid()) {
            $slug = $slugify->generate($program->getTitle());
            $program->setSlug($slug);
            $program->setAuthor($program->getAuthor());
            $programRepository->add($program, true);

            $this->addFlash('success', 'Votre série est bien modifiée !');

            return $this->redirectToRoute('program_index');
        }

        return $this->renderForm('program/edit.html.twig', [
            'formProgram' => $form,
        ]);
    }

    #[Route('/{slug}', methods: ['GET'], name: 'show')]
    public function show(Program $program): Response
    {
        $seasons = $program->getSeasons();
        $actors = $program->getActors();
        return $this->render('program/show.html.twig', [
            'program' => $program,
            'seasons' => $seasons,
            'actors' => $actors,
        ]);
    }

    #[Route('/{programId<\d+>}/season/{seasonId<\d+>}', methods: ['GET'], name: 'season_show')]
    #[Entity('program', options: ['id' => 'programId'])]
    #[Entity('season', options: ['id' => 'seasonId'])]
    public function showSeason(Program $program, Season $season): Response
    {
        $episodes = $season->getEpisodes();
        return $this->render('program/season_show.html.twig', [
            'program' => $program,
            'season' => $season,
            'episodes' => $episodes,
        ]);
    }

    #[Route('/{programId<\d+>}/season/{seasonId<\d+>}/episode/{episodeId<\d+>}', methods: ['GET', 'POST'], name: 'episode_show')]
    #[Entity('program', options: ['id' => 'programId'])]
    #[Entity('season', options: ['id' => 'seasonId'])]
    #[Entity('episode', options: ['id' => 'episodeId'])]
    public function showEpisode(Program $program, Season $season, Episode $episode, CommentRepository $commentRepository, Request $request): Response
    {
        $comments = $episode->getComments();
        $user = $this->getUser();

        $comment = new Comment($episode, $user);
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commentRepository->add($comment, true);

            $this->addFlash('success', 'Votre commentaire est bien publié !');

            return $this->redirectToRoute('program_episode_show', [
                'programId' => $program->getId(),
                'seasonId' => $season->getId(),
                'episodeId' => $episode->getId(),
            ],
            Response::HTTP_SEE_OTHER);
        }

        return $this->render('program/episode_show.html.twig', [
            'program' => $program,
            'season' => $season,
            'episode' => $episode,
            'comments' => $comments,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/comment/{id}', name: 'comment_delete', methods: ['POST'])]
    #[Entity('comment', options: ['id' => 'id'])]
    public function deleteComment(Request $request, Comment $comment, CommentRepository $commentRepository): Response
    {
        $episode = $comment->getEpisode();
        $season = $episode->getSeason();
        $program = $season->getProgram();

        if (!($this->getUser() == $comment->getAuthor()) && !(in_array('ROLE_ADMIN', $this->getUser()->getRoles()))) {
            throw new AccessDeniedException('Seuls le créateur de ce commentaire et l\'admin peuvent le supprimer');
        }

        if ($this->isCsrfTokenValid('delete' . $comment->getId(), $request->request->get('_token'))) {
            $commentRepository->remove($comment, true);
        }

        return $this->redirectToRoute('program_episode_show', ['programId' => $program->getId(), 'seasonId' => $season->getId(), 'episodeId' => $episode->getId()], Response::HTTP_SEE_OTHER);
    }
}
