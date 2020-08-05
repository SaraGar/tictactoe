<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Game;
use App\Entity\Turn;
use App\Entity\Player;

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

    /**
     * Función para guardar un turno en BD
     *
     * @param [type] $params
     * @return array 
     */
    public function saveTurn($params){
        try {
            
            //Comprobamos los parámetros
            if(!isset($params['player'])  || !isset($params['gameId'])  || !isset($params['row'])  || !isset($params['column']) || $params['player'] == "" || $params['row'] == "" || $params['column'] == "" ){

                $response = ['code' => 500, 'message' => 'No se han recibido los parámetros necesarios', 'data' => []];

            }else{
                //Buscamos o creamos el jugador
                if($params['player']){
                    $player = $this->em->getRepository(Player::class)->findOneByName($params['player']);
                    if(!$player){
                        $player = new Player();
                        $player->setName($params['player']);
                        $this->em->persist($player); 
                    }
                }

                //Buscamos o creamos la partida
                if($params['gameId']){
                    $game = $this->em->getRepository(Game::class)->findOneById($params['gameId']);
                    
                }
                if(!isset($game) || !$game){
                    $game = new Game();
                    $this->em->persist($game);                    
                }

                //Creamos y rellenamos el turno
                $turn = new Turn();
                $turn->setGame($game);
                $turn->setPlayer($player);
                $turn->setRowPosition($params['row']);
                $turn->setColumnPosition($params['column']);
                $this->em->persist($turn); 

                $this->em->flush();

                $response = ['code' => 200, 'message' => 'Guardado con éxito', 'data' => $game->getId()];
            }   
        } catch (\Throwable $th) {
            $response = ['code' => 500, 'message' => 'No se ha podido procesar el turno', 'data' => []];
        }   
        
        return $response;  
    }
    /**
     * Función para comprobar si la partida ha terminado
     *
     * @param [type] $params
     * @return array 
     */
    public function checkFinished($gameId){
        try { 
            
            $game = $this->em->getRepository(Game::class)->findOneById($gameId);
                
            if(!$game){
                $response = ['code' => 500, 'message' => 'No se han recibido los parámetros necesarios', 'data' => []];
            }else{
                $isFinished = 0;
                $winner = $winnerName ="";

                //Si no han llegado a 5 turnos no han podido hacer 3 en raya
                if(count($game->getTurns()) >= 5) {   
                    //Si el tablero está lleno, se termina la partida
                    if(count($game->getTurns()) == 9 ){                  
                        $isFinished = 1;
                    }

                    //Montamos un array auxiliar con las posiciones rellenas
                    $board = $this->getBoard($game);
                    
                    //Comprobamos las filas
                    $winner = $this->checkRows($board);
                    
                    if($winner == ""){
                        //Comprobamos las columnas
                        $winner = $this->checkColumns($board);
                        
                        if($winner == ""){
                            //Comprobamos las diagonales 
                            $winner = $this->checkDiagonals($board);
                            
                        }                        
                    }

                    //Si hemos recibido el ganador, actualizamos la partida finalizada 
                    if($winner != ""){
                        $isFinished = 1;
                        $winnerPlayer = $this->em->getRepository(Player::class)->findOneByName($winner);
                        if($winnerPlayer){
                            $game->setWinner($winnerPlayer);                            
                        }       
                        $winnerName = $winner;                     

                    }
                    $game->setIsFinished($isFinished);
                    
                    $this->em->flush();  
                }                      

                $response = ['code' => 200, 'message' => 'Guardado con éxito', 'data' => ['isFinished' => $isFinished, 'winner' => $winnerName]];
            }   
        } catch (\Throwable $th) {
            $response = ['code' => 500, 'message' => 'No se ha podido procesar el turno', 'data' => []];
        }    
        
        return $response;  
    }

    /**
     * Función para obtener la matriz de las casillas
     *
     * @param [type] $game
     * @return void
     */
    public function getBoard($game){
        $board = [];

        //Montamos la matriz vacía
        for ($i = 1; $i <= 3; $i++) {
            for ($j = 1; $j <= 3; $j++) {
                $board[$i][$j] = null;
            }
        }

        //Completamos la matriz con los valores de los turnos
        foreach($game->getTurns() as $turn){
            $board[$turn->getRowPosition()][$turn->getColumnPosition()] = $turn->getPlayer()->getName();
        }

        return $board;
    }

    /**
     * Función para comprobar si hay tres en raya en alguna columna
     *
     * @param [type] $board
     * @return string Nombre del ganador o "" en su defecto
     */
    public function checkColumns($board){
        //Recorremos las casillas de cada columna
        for ($column = 1; $column <= 3; $column++) {
            $lastPosition = "";
            for($row=1; $row<=3; $row++){
                //Si la posición coincide con la anterior o si estamos en la primera casilla, continuamos. Si estamos en la última casilla (tercera fila) y las casillas coinciden, consideramos que tenemos ganador.
                if($board[$row][$column] == $lastPosition || ($lastPosition == "" && $row == 1)){
                    if($row == 3 && $lastPosition != ""){
                        $winner = $lastPosition;
                        return $winner;
                    }
                    $lastPosition = $board[$row][$column];                 
                }else{
                    break;
                }
            }            
        }

        return "";
    }

    /**
     * Función para comprobar si hay tres en raya en alguna fila
     *
     * @param [type] $board
     * @return string Nombre del ganador o "" en su defecto
     */
    public function checkRows($board){
        //Recorremos cada casilla de la fila
        for ($row = 1; $row <= 3; $row++) {
            $lastPosition = "";
            for($column=1; $column<=3; $column++){  
                //Si la posición coincide con la anterior o si estamos en la primera casilla, continuamos. Si estamos en la última casilla (tercera columna) y las casillas coinciden, consideramos que tenemos ganador. 
                if($board[$row][$column] == $lastPosition || ($lastPosition == "" && $column == 1)){                                              
                    if($column == 3 && $lastPosition != ""){
                        $winner = $lastPosition; 
                        return $winner;
                    }
                    $lastPosition = $board[$row][$column];                
                    
                }else{
                    break;
                }
            }            
        }

        return "";
    }

    /**
     *  Función para comprobar si hay tres en raya en alguna diagonal
     *
     * @param [type] $board
     * @return string Nombre del ganador o "" en su defecto
     */
    public function checkDiagonals($board){

        //Primera diagonal 

        //En esta diagonal, los valores de la posición en la fila y la columna coinciden, por lo que solo tenemos un bucle
        $lastPosition ="";
        for ($count =  1 ; $count <= 3; $count++) {     
            //Si las casillas coinciden hasta llegar a la tercera vuelta, consideramos que tenemos ganador     
            if($board[$count][$count] == $lastPosition || $count == 1){
                if($count == 3 && $lastPosition != ""){
                    $winner = $lastPosition;
                    return $winner;
                }        
                $lastPosition = $board[$count][$count];                   
            }else{
                break;
            }                  
        }

        //Segunda diagonal 

        //En esta diagonal, el valor de la posición de la fila decrece mientras el de la columna crece 
        $column = 1;
        $lastPosition = ""; 
        for ($row =  3 ; $row >=1; $row--) {
             //Si las casillas coinciden hasta llegar a la tercera vuelta, consideramos que tenemos ganador  
            if($board[$row][$column] == $lastPosition || $row == 3){
               
                if($row == 1 && $lastPosition != ""){
                    $winner = $lastPosition;
                    return $winner;
                }  
                $lastPosition = $board[$row][$column];   
                $column++;     

            } else{
               break;
            }   
        }        

        return "";        
    }



}