<?php

namespace App\Controller;

use App\Entity\Matches;
use App\Entity\Standings;
use App\Entity\Team;
use App\Form\StandingsType;
use App\Repository\StandingsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/standings')]
class StandingsController extends AbstractController
{


    #[Route('/{id}', name: 'app_standings_order', methods: ['GET'])]
    public function order(EntityManagerInterface $entityManager,StandingsRepository $standingsRepository, Standings $standing){
        $teams=$entityManager->getRepository(Team::class)->findAll();
        foreach($teams as $key){
            $key->setGoalsscored(0);
            $key->setPoints(0);
            $key->setGoalstaken(0);
            $key->setWins(0);
            $key->setLoses(0);
            $key->setTies(0);

            $entityManager->flush();
        }
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
        $comp1= function($a,$b){
            if($a->getPoints()==$b->getPoints()){
                return ($a->getGoalsscored()-$a->getGoalstaken()>$b->getGoalsscored()-$b->getGoalstaken()) ? -1:1;

            }
            return ($a->getPoints()>$b->getPoints()) ? -1:1;

        };
        usort($teams,$comp1);

        $entityManager->flush();
        return $this->render('standings/show.html.twig',[
            'standing' => $standing,
            'controller_name'=>'StandingsController',
            'teams'=>$teams,



        ]);
    }
    #[Route('/', name: 'app_standings_index', methods: ['GET'])]
    public function index(StandingsRepository $standingsRepository,EntityManagerInterface $entityManager): Response
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
            'teams'=>$standing->getTeams(),
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
