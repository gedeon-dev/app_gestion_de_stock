<?php
namespace Fabrication;


use PDO;
use PDOException;

class Fabrication{

    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function enr_fabrication(int $sn, string $date, int $id_a, int $id_c)
    {
        try{
            $query = $this->pdo->prepare('INSERT INTO fabrications (sn, date_fabrication, id_article, id_client) VALUES (?, ?, ?,?)');
        $query->execute([$sn, $date, $id_a, $id_c]);
        return $this->pdo->lastInsertId();
        }catch(PDOException $e){
            return $e;
        }
    }

    public function sn_verifie(int $sn) : ?string
    {
        if(strlen((string)$sn) > 6 ||  strlen((string)$sn) < 6){
            return "la taille du numero de serie ne correspond pas";
        }
        $query = $this->pdo->prepare('SELECT * FROM fabrications WHERE sn = ?');
        $query->execute([$sn]);
        $result = $query->fetchAll();
        if($result){
            return "le numero de serie existe dans la base de donnee";
        }
        return null;
    }

    public function count_id_fab(int $first_id, int $last_id) : int
    {
        $query = $this->pdo->prepare("SELECT COUNT(idF) as count FROM fabrications WHERE idF BETWEEN :id1 AND :id2");
        $query->execute([
            'id1' => $first_id,
            'id2' => $last_id
        ]);
        $pages = (int)$query->fetch()->count;
        if($pages){
            return $pages;
        }
        return null;
    }
}