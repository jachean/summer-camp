<?php

namespace App\Controller;

use App\Entity\Player;
use App\Entity\Team;
use App\Form\PlayerType;
use App\Repository\PlayerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/player')]
class PlayerController extends AbstractController
{
    #[Route('/', name: 'app_player_index', methods: ['GET'])]
    public function index(PlayerRepository $playerRepository,EntityManagerInterface $entityManager): Response
    {
     $this->genplayers($entityManager);
        $teams=$entityManager->getRepository(Team::class)->findAll();
        foreach($teams as $team){

                foreach($team->getPlayers()as $previousplayer){
                    foreach($team->getPlayers()as$player){
                        if($player!=$previousplayer&&$player->getShirtnumber()==$previousplayer->getShirtnumber()){
                            $player->setShirtnumber($previousplayer->getShirtnumber()+1);
                            $entityManager->persist($player);
                        }

                    }


                }
                //dd($team->getPlayers());
                $entityManager->flush();
        }
//        $teams=$entityManager->getRepository(Team::class)->findAll();
//         foreach($teams as $team){
//             foreach($team->getPlayers()as $player){
//                 $team->removePlayer($player);
//                 $entityManager->persist($player);
//             }
//             $entityManager->flush();
//         }

        return $this->render('player/index.html.twig', [
            'players' => $playerRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_player_new', methods: ['GET', 'POST'])]
    public function new(Request $request, PlayerRepository $playerRepository): Response
    {
        $player = new Player();
        $form = $this->createForm(PlayerType::class, $player);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $playerRepository->save($player, true);

            return $this->redirectToRoute('app_player_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('player/new.html.twig', [
            'player' => $player,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_player_show', methods: ['GET'])]
    public function show(Player $player): Response
    {
        return $this->render('player/show.html.twig', [
            'player' => $player,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_player_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Player $player, PlayerRepository $playerRepository): Response
    {
        $form = $this->createForm(PlayerType::class, $player);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $playerRepository->save($player, true);

            return $this->redirectToRoute('app_player_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('player/edit.html.twig', [
            'player' => $player,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_player_delete', methods: ['POST'])]
    public function delete(Request $request, Player $player, PlayerRepository $playerRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$player->getId(), $request->request->get('_token'))) {
            $playerRepository->remove($player, true);
        }

        return $this->redirectToRoute('app_player_index', [], Response::HTTP_SEE_OTHER);
    }
    public function genplayers(EntityManagerInterface $entityManager){
        $teams=$entityManager->getRepository(Team::class)->findAll();
        $faker=Factory::create();
        foreach($teams as $team){
            if(sizeof($team->getPlayers())<11){
                $player=new Player();
                $player->setName($faker->name);
                $player->setAge(rand(16,38));
                $player->setRole("Goalkeeper");
                $player->setShirtnumber(1);
                $player->setTeam($team);
                $entityManager->persist($player);
                for($x=0;$x<4;$x++){
                    $player=new Player();
                    $player->setName($faker->name);
                    $player->setAge(rand(16,38));
                    $player->setRole("Defender");
                    $player->setShirtnumber(rand(12,20));
                    $player->setTeam($team);
                    $entityManager->persist($player);
                }
                for($x=0;$x<4;$x++){
                    $player=new Player();
                    $player->setName($faker->name);
                    $player->setAge(rand(16,38));
                    $player->setRole("Midfielder");
                    $player->setShirtnumber(rand(21,30));
                    $player->setTeam($team);
                    $entityManager->persist($player);
                }
                for($x=0;$x<2;$x++){
                    $player=new Player();
                    $player->setName($faker->name);
                    $player->setAge(rand(16,38));
                    $player->setRole("Striker");
                    $player->setShirtnumber(rand(2,11));
                    $player->setTeam($team);
                    $entityManager->persist($player);
                }
                $player=new Player();
                $player->setName($faker->name);
                $player->setAge(rand(43,68));
                $player->setRole("coach");
                $player->setTeam($team);
                $entityManager->persist($player);
            }
            $entityManager->flush();
        }

    }
}
