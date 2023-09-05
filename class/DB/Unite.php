<?php
namespace Unite;

use PDO;

class Unite{

    public static function liste_unite(PDO $pdo) : ?array
    {
        $query = $pdo->prepare('SELECT * FROM unites');
        $query->execute();
        $unites = $query->fetchAll();
        if($unites){
            return $unites;
        }
        return null;
    }

    public static function unite(PDO $pdo, $id) : ?string
    {
        $query = $pdo->prepare('SELECT nom_unite FROM unites WHERE id = ?');
        $query->execute([$id]);
        $unite = $query->fetch(PDO::FETCH_ASSOC);
        if($unite){
            return $unite['nom_unite'];
        }
        return null;
    }
    
}