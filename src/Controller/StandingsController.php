<?php

namespace App\Controller;

use App\Entity\Standings;
use App\Form\StandingsType;
use App\Repository\StandingsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/standings')]
class StandingsController extends AbstractController
{
    #[Route('/', name: 'app_standings_index', methods: ['GET'])]
    public function index(StandingsRepository $standingsRepository): Response
    {
        return $this->render('standings/index.html.twig', [
            'standings' => $standingsRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_standings_new', methods: ['GET', 'POST'])]
    public function new(Request $request, StandingsRepository $standingsRepository): Response
    {
        $standing = new Standings();
        $form = $this->createForm(StandingsType::class, $standing);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $standingsRepository->save($standing, true);

            return $this->redirectToRoute('app_standings_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('standings/new.html.twig', [
            'standing' => $standing,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_standings_show', methods: ['GET'])]
    public function show(Standings $standing): Response
    {
        return $this->render('standings/show.html.twig', [
            'standing' => $standing,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_standings_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Standings $standing, StandingsRepository $standingsRepository): Response
    {
        $form = $this->createForm(StandingsType::class, $standing);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $standingsRepository->save($standing, true);

            return $this->redirectToRoute('app_standings_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('standings/edit.html.twig', [
            'standing' => $standing,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_standings_delete', methods: ['POST'])]
    public function delete(Request $request, Standings $standing, StandingsRepository $standingsRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$standing->getId(), $request->request->get('_token'))) {
            $standingsRepository->remove($standing, true);
        }

        return $this->redirectToRoute('app_standings_index', [], Response::HTTP_SEE_OTHER);
    }
}
