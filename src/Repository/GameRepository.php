<?php

namespace App\Repository;

use App\Entity\Game;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Game|null find($id, $lockMode = null, $lockVersion = null)
 * @method Game|null findOneBy(array $criteria, array $orderBy = null)
 * @method Game[]    findAll()
 * @method Game[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GameRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Game::class);
    }

    /**
     * Función que recupera la última partida de dos jugadores (para lo cual deben haber realizado un turno al menos uno de ellos), y en caso de que no esté finalizada devuelve su id.
     *
     * @param [type] $playerOne
     * @param [type] $playerTwo
     * @return string id de la partida pendiente o "" si no hay ninguna en curso
     */
    public function findPendingGame($playerOne, $playerTwo){

        $conn = $this->getEntityManager()->getConnection();
        $result = "";

        $sql = "
            SELECT IF(g.is_finished = 0, g.id, NULL) as id
            FROM game g
            JOIN turn t ON t.game_id = g.id
            LEFT JOIN player p1 ON t.player_id = p1.id AND p1.name = :playerOne
            LEFT JOIN player p2 ON t.player_id = p2.id AND p2.name = :playerTwo
            WHERE (p1.id IS NOT NULL or p2.id IS NOT NULL)
            ORDER BY g.datetime DESC 
            LIMIT 1
        ";
       
        $stmt = $conn->prepare($sql);
       
        $stmt->execute(array('playerOne' => $playerOne, 'playerTwo' => $playerTwo));
        $array_result = $stmt->fetch();

        if(is_array($array_result) && count($array_result) > 0 && $array_result["id"] != NULL){
            $result = $array_result['id']; 
        }
        return $result;
    }

    // /**
    //  * @return Game[] Returns an array of Game objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Game
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
