<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Game;

class GameService
{

    private $em;
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * Función para recuperar la última partida entre dos jugadores, en caso de que no esté finalizada
     *
     * @param [type] $playerOne
     * @param [type] $playerTwo
     * @return array [code => '', message => '', data => []]
     */
    public function retrieveGame($playerOne, $playerTwo){
        if(empty($playerOne) || empty($playerTwo)){
            $response = ['code' => 500, 'message' => 'No se han recibido los nombres de los dos jugadores', 'data' => []];
        }

        //Comprobamos si esos jugadores cuentan con una partida pendiente de terminar
        $game_id = $this->em->getRepository(Game::class)->findPendingGame($playerOne, $playerTwo);
        
        if(!empty($game_id)){
            $response = ['code' => 200, 'message' => 'Partida recuperada', 'data' => $game_id];
        }else{
            $response = ['code' => 200, 'message' => 'No hay partidas pendientes', 'data' => []];  
        }

        return $response;
    }

    /**
     * Función para obtener una partida a través de su ID y validar que pertenezca a los usuarios actuales. Si no se encuentra o la ID llega vacía, se crea una nueva.
     *
     * @param [type] $playerOne
     * @param [type] $playerTwo
     * @param [type] $gameId
     * @return Game  $game
     */
    public function getGame($playerOne, $playerTwo, $gameId = null){
        $game = null;

        if($gameId){
            $game = $this->em->getRepository(Game::class)->findOneById($gameId);
            //Si se encuentra la partida, comprobamos que sea de esos jugadores
            if($game){
                $areAllowed = $this->em->getRepository(Game::class)->arePlayersAllowed($playerOne, $playerTwo, $gameId);
            }
        }
        
        return $game;  
    }

}