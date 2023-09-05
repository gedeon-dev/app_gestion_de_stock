<?php

namespace Parametre;

use PDO;
use PDOException;

/**
 * Parametre
 * 
 * enregistre un parametre
 * 
 * @author Gedeon.dev
 * @author Gedeon.dev <gedeonmitoumona@gmail.com>
 * 
 * @version 1.0
 * 
 * @copyright ECEI appli enregistrement
 * 
 * 
 * @var PDO $pdo stock un PDOStatment
 */

class Parametre{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }
    
    /**
     * enregistrement_parametre
     *
     * @param  string $nom
     * @param  float $val
     * @param  int $tolerance
     * @param  int $id_unite
     * @param  int $id_produit
     * @return bool
     */
    
    public function enregistrement_parametre(string $nom, float $val, float $tolerance, int $id_unite, int $id_article) : string
    {
        try{
            $query = $this->pdo->prepare(
                'INSERT INTO parametres (nom_parametre, valeur_theorique, tolerance, id_unite, id_article)
                VALUES (?, ?, ?, ?, ?)'
            );
            $query->execute([
                $nom,
                $val,
                $tolerance,
                $id_unite,
                $id_article
            ]);
            return 'true';
        }catch(PDOException $e){
            return $e;
        }
    }


    public function update_parametre(string $nom, float $val, float $tolerance, int $id_unite, int $id_article) : bool
    {
        try{
            $query1 = $this->pdo->prepare('UPDATE parametres SET nom_parametre = :nom, valeur_theorique = :val, tolerance = :tol, id_unite = :unite WHERE id_article = :article');
            $query1->execute([
                'nom' => $nom,
                'val' => $val,
                'tol' => $tolerance,
                'unite' => $id_unite,
                'article' => $id_article
            ]);
            return true;
        }catch(PDOException $e){
            return false;
        }
    }
}