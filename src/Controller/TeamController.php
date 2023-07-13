<?php

namespace App\Controller;

use App\Entity\Matches;
use App\Entity\Team;
use App\Form\TeamType;
use App\Repository\TeamRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/team')]
class TeamController extends AbstractController
{


    #[Route('/stats', name: 'app_team_stats', methods: ['GET'])]
    public function stats(EntityManagerInterface $entityManager) {
        $matches = $entityManager->getRepository(Matches::class)->findAll();
        foreach($matches as $key){
            $firstteam=$key->getTeam1();
            $secondteam=$key->getTeam2();
            $team1points =$firstteam->getPoints();
            $team2points =$secondteam->getPoints();
            $team1gs=$firstteam->getGoalsscored();
            $team2gs=$secondteam->getGoalsscored();
            $team1gt=$firstteam->getGoalstaken();
            $team2gt=$secondteam->getGoalstaken();
            $team1wins=$firstteam->getWins();
            $team2wins=$secondteam->getWins();
            $team1lose=$firstteam->getLoses();
            $team2lose=$secondteam->getLoses();
            $team1ties=$firstteam->getTies();
            $team2ties=$secondteam->getTies();
            $team1gs+=$key->getScore1();
            $team1gt+=$key->getScore2();
            $team2gs+=$key->getScore2();
            $team2gt+=$key->getScore1();

            if($key->getScore1()>$key->getScore2()){

                    $team1wins+=1;
                    $team2lose+=1;
                 $team1points+=3;
            }
            elseif ($key->getScore1()==$key->getScore2()){

                $team1ties+=1;
                $team2ties+=1;
                $team1points+=1;
                $team2points+=1;

            }else {

                $team1lose+=1;
                $team2wins+=1;
                $team2points += 3;
            }
            $firstteam->setPoints( $team1points);
            $secondteam->setPoints($team2points);
            $firstteam->setGoalsscored($team1gs);
            $firstteam->setGoalstaken($team1gt);
            $secondteam->setGoalsscored($team2gs);
            $secondteam->setGoalstaken($team2gt);
            $firstteam->setWins($team1wins);
            $firstteam->setLoses($team1lose);
            $firstteam->setTies($team1ties);
            $secondteam->setWins($team2wins);
            $secondteam->setLoses($team2lose);
            $secondteam->setTies($team2ties);
            $entityManager->flush();
        }
        return $this->redirectToRoute('app_team_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/resetstats', name: 'app_team_resetstats', methods: ['GET'])]
    public function resetstats(EntityManagerInterface $entityManager) {
        $teams = $entityManager->getRepository(Team::class)->findAll();
        foreach($teams as $key){
                $key->setGoalsscored(0);
                $key->setPoints(0);
                $key->setGoalstaken(0);
                $key->setWins(0);
                $key->setLoses(0);
                $key->setTies(0);

            $entityManager->flush();
        }

        return $this->redirectToRoute('app_team_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/', name: 'app_team_index', methods: ['GET'])]
    public function index(TeamRepository $teamRepository): Response
    {
        return $this->render('team/index.html.twig', [
            'teams' => $teamRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_team_new', methods: ['GET', 'POST'])]
    public function new(Request $request, TeamRepository $teamRepository): Response
    {
        $team = new Team();
        $form = $this->createForm(TeamType::class, $team);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $teamRepository->save($team, true);

            return $this->redirectToRoute('app_team_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('team/new.html.twig', [
            'team' => $team,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_team_show', methods: ['GET'])]
    public function show(Team $team): Response
    {
        return $this->render('team/show.html.twig', [
            'team' => $team,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_team_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Team $team, TeamRepository $teamRepository): Response
    {
        $form = $this->createForm(TeamType::class, $team);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $teamRepository->save($team, true);

            return $this->redirectToRoute('app_team_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('team/edit.html.twig', [
            'team' => $team,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_team_delete', methods: ['POST'])]
    public function delete(Request $request, Team $team, TeamRepository $teamRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$team->getId(), $request->request->get('_token'))) {
            $teamRepository->remove($team, true);
        }

        return $this->redirectToRoute('app_team_index', [], Response::HTTP_SEE_OTHER);
    }


}
