<?php

namespace DB\Insert;

use PDO;
use PDOException;

class Mesure{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function enregistrement_mesure(float $mesure, int $fab, int $param, string $user) : bool
    {
        try{
            $query = $this->pdo->prepare(
                'INSERT INTO mesures (mesure, id_fabrication, id_parametre, testeur, date)
                VALUES (?, ?, ?, ?, CURDATE())'
            );
            $query->execute([
                $mesure,
                $fab,
                $param,
                $user
            ]);
            return true;
        }catch(PDOException $e){
            return false;
        }
    }
}