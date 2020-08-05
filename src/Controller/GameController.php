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

    /**
     * @Route("/game", name="play_game")
     * 
     * Función para cargar el tablero de juego
     *
     */
    public function playGame(HttpFoundationRequest $request, GameService $gameService)
    {   $response = ['code' => 500, 'message' => 'Ha ocurrido un error', 'data' => []];

        $playerOne = $request->get('playerOne');
        $playerTwo= $request->get('playerTwo');
        $gameId = $request->get('gameId');
        
        $game = $gameService->getGame($playerOne, $playerTwo, $gameId);
        
        return $this->render('game/game.html.twig', [
            'playerOne' => $playerOne,
            'playerTwo' => $playerTwo,
            'game' => $game,
        ]);
    }

    /**
     * @Route("/turn", name="process_turn")
     * 
     * Función para procesar un turno (guardado y comprobación de fin de juego)
     *
     */
    public function processTurn(HttpFoundationRequest $request, GameService $gameService)
    {   $response = ['code' => 500, 'message' => 'Ha ocurrido un error', 'data' => []];

        $params = $request->request->all();
        
        $response = $gameService->saveTurn($params);
        $gameId = $response['data'];
        if($response['data'] != []){
            $response = $gameService->checkFinished($response['data'] );
        }       
        $response['data']['game'] = $gameId;
        return new JsonResponse($response);
    }

    /**
     * @Route("/automatic_turn", name="automatic_turn")
     * 
     * Función para jugar el turno de la máquina
     *
     */
    public function automaticTurn(HttpFoundationRequest $request, GameService $gameService)
    {   $response = ['code' => 500, 'message' => 'Ha ocurrido un error', 'data' => []];

        $params = $request->request->all();
        
        $response = $gameService->automaticTurn($params);
       
        return new JsonResponse($response);
    }

}
