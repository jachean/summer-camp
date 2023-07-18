<?php

namespace App\Controller;

use App\Entity\Matches;
use App\Entity\Team;
use App\Form\MatchesType;
use App\Repository\MatchesRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/matches')]
class MatchesController extends AbstractController
{
    #[Route('/', name: 'app_matches_index', methods: ['GET'])]
    public function index(MatchesRepository $matchesRepository,EntityManagerInterface $entityManager): Response
    {
        $matches=$matchesRepository->findAll();
        $teams=$entityManager->getRepository(Team::class)->findAll();
        $existingmatches=$matchesRepository->findAll();
        $nr=$entityManager->getRepository(Matches::class)->count([]);
        $existingTeams=[];
        foreach($existingmatches as $match){
            $existingTeams[$match->getTeam1()->getId()][$match->getTeam2()->getId()]=true;
            $existingTeams[$match->getTeam2()->getId()][$match->getTeam1()->getId()]=true;

        }
        foreach($teams as $team1){
            foreach($teams as $team2){
                if($team1->getName()!=$team2->getName()&&!isset($existingTeams[$team1->getId()][$team2->getId()])){
                    $match= new Matches();
                    $match->setTeam1($team1);
                    $match->setTeam2($team2);
                    $match->setScore1(rand(0,6));
                    $match->setScore2(rand(0,6));
                    $entityManager->persist($match);
                }
            }

        }
        $entityManager->flush();
        return $this->render('matches/index.html.twig', [
            'matches' => $matchesRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_matches_new', methods: ['GET', 'POST'])]
    public function new(Request $request, MatchesRepository $matchesRepository): Response
    {
        $match = new Matches();
        $form = $this->createForm(MatchesType::class, $match);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $matchesRepository->save($match, true);

            return $this->redirectToRoute('app_matches_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('matches/new.html.twig', [
            'match' => $match,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_matches_show', methods: ['GET'])]
    public function show(Matches $match): Response
    {
        return $this->render('matches/show.html.twig', [
            'match' => $match,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_matches_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Matches $match, MatchesRepository $matchesRepository): Response
    {
        $form = $this->createForm(MatchesType::class, $match);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $matchesRepository->save($match, true);

            return $this->redirectToRoute('app_matches_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('matches/edit.html.twig', [
            'match' => $match,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_matches_delete', methods: ['POST'])]
    public function delete(Request $request, Matches $match, MatchesRepository $matchesRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$match->getId(), $request->request->get('_token'))) {
            $matchesRepository->remove($match, true);
        }

        return $this->redirectToRoute('app_matches_index', [], Response::HTTP_SEE_OTHER);
    }
}
