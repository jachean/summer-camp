<?php

namespace App\Controller;

use App\Entity\Matches;
use App\Entity\Player;
use App\Entity\Standings;
use App\Entity\Team;
use App\Form\TeamType;
use App\Repository\TeamRepository;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/team')]
class TeamController extends AbstractController
{




    #[Route('/', name: 'app_team_index', methods: ['GET'])]
    public function index(TeamRepository $teamRepository): Response
    {
        return $this->render('team/index.html.twig', [
            'teams' => $teamRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_team_new', methods: ['GET', 'POST'])]
    public function new(Request $request, TeamRepository $teamRepository,EntityManagerInterface $entityManager): Response
    {
        $team = new Team();
        $form = $this->createForm(TeamType::class, $team);
        $form->handleRequest($request);
        $teams=$entityManager->getRepository(Team::class)->findAll();
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
    #[Route('/{id}/matches', name: 'app_team_matches', methods: ['GET'])]
    public function matches(EntityManagerInterface $entityManager,Team $team): Response
    {
        $matches=$entityManager->getRepository(Matches::class)->findAll();
        usort($matches,function($a,$b){

            if(strtotime($a->getDateTime())==strtotime($b->getDateTime())){
                return 0;

            }

            return (strtotime($a->getDateTime())>strtotime($b->getDateTime())) ? -1:1;

        });

        return $this->render('team/matches.html.twig', [
            'matches' => $matches,
            'team'=>$team,
        ]);
    }
    #[Route('/{id}/players', name: 'app_team_players', methods: ['GET'])]
    public function players(EntityManagerInterface $entityManager,Team $team): Response
    {
       // $players=$entityManager->getRepository(Player::class)->findAll();
        return $this->render('team/players.html.twig', [
            //'players' => $players,
            'team'=>$team,
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
//    public function genplayers(EntityManagerInterface $entityManager){
//        $teams=$entityManager->getRepository(Team::class)->findAll();
//        $faker=Factory::create();
//        foreach($teams as $team){
//                if(sizeof($team->getPlayers())<11){
//                    $player=new Player();
//                    $player->setName($faker->name);
//                    $player->setAge(rand(16,38));
//                    $player->setRole("Goalkeeper");
//                    $player->setShirtnumber(1);
//                    $player->setTeam($team);
//                    $entityManager->persist($player);
//                    for($x=0;$x<4;$x++){
//                        $player=new Player();
//                        $player->setName($faker->name);
//                        $player->setAge(rand(16,38));
//                        $player->setRole("Defender");
//                        $player->setShirtnumber(rand(12,20));
//                        $player->setTeam($team);
//                        $entityManager->persist($player);
//                    }
//                    for($x=0;$x<4;$x++){
//                        $player=new Player();
//                        $player->setName($faker->name);
//                        $player->setAge(rand(16,38));
//                        $player->setRole("Midfielder");
//                        $player->setShirtnumber(rand(21,30));
//                        $player->setTeam($team);
//                        $entityManager->persist($player);
//                    }
//                    for($x=0;$x<2;$x++){
//                        $player=new Player();
//                        $player->setName($faker->name);
//                        $player->setAge(rand(16,38));
//                        $player->setRole("Striker");
//                        $player->setShirtnumber(rand(2,11));
//                        $player->setTeam($team);
//                        $entityManager->persist($player);
//                    }
//                    $player=new Player();
//                    $player->setName($faker->name);
//                    $player->setAge(rand(43,68));
//                    $player->setRole("coach");
//                    $player->setTeam($team);
//                    $entityManager->persist($player);
//                }
//            $entityManager->flush();
//        }
//
//    }


}
