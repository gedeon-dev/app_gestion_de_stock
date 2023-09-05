<?php

namespace DB\Join;

use PDO;

class Join{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function get_reference(string $table, int $id): ?string
    {
        $query = $this->pdo->prepare("SELECT reference FROM articles JOIN $table ON {$table}.id_article = articles.id WHERE id = ?");
        $query->execute([$id]);
        $reference = $query->fetch();
        if($reference){
            return $reference->reference;
        }
        return null;
    }

    public function get_parametres(int $id): ?array
    {
        $query = $this->pdo->prepare("SELECT * FROM parametres JOIN articles ON articles.id = parametres.id_article WHERE id_article = ?");
        $query->execute([$id]);
        $paramtres = $query->fetchAll();
        if($paramtres){
            return $paramtres;
        }
        return null;
    }


    public function get_parametres_by_idP(int $id): ?array
    {
        $query = $this->pdo->prepare("SELECT * FROM parametres JOIN articles ON articles.id = parametres.id_article WHERE idP = ?");
        $query->execute([$id]);
        $paramtres = $query->fetchAll();
        if($paramtres){
            return $paramtres;
        }
        return null;
    }

    public function get_client(int $id) : ?string
    {
        $query = $this->pdo->prepare("SELECT nom FROM client JOIN fabrications ON client.id = fabrications.id_client WHERE id = ?");
        $query->execute([$id]);
        $client = $query->fetch();
        if($client){
            return $client->nom;
        }
        return null;
    }
    public function get_fabrication(int $id)
    {
        $query = $this->pdo->prepare("SELECT * FROM fabrications JOIN mesures ON mesures.id_fabrication = fabrications.idF WHERE idF = ?");
        $query->execute([$id]);
        $fabrications = $query->fetch();
        if($fabrications){
            return $fabrications;
        }
        return null;
    }
}