<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\GameService;
use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;

class GameController extends AbstractController
{
    /**
     * @Route("/game", name="game")
     */
    public function index()
    {
        return $this->render('game/index.html.twig', [
            'controller_name' => 'GameController',
        ]);
    }

    /**
     * @Route("/retrieve_game", name="retrieve_game")
     * 
     * Función para recuperar la id de la última partida de los jugadores, en caso de que no esté finalizada
     * 
     * @return array [code => '', message => '', data => []]
     */
    public function retrieveGame(HttpFoundationRequest $request, GameService $gameService)
    {   $response = ['code' => 500, 'message' => 'Ha ocurrido un error', 'data' => []];

        $playerOne = $request->request->get('playerOne');
        $playerTwo= $request->request->get('playerTwo');
        
        $response = $gameService->retrieveGame($playerOne, $playerTwo);
        
        return new JsonResponse($response);
    }
}
