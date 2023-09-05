<?php



namespace ClientAdd;

use PDO;


class Addclient{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function client_add(string $nom) : void
    {
        $query = $this->pdo->prepare('INSERT INTO client (nom) VALUE (?)');
        $query->execute([$nom]);
    }
    public function client_verifie(string $nom) : ?string
    {
        $query = $this->pdo->prepare('SELECT nom FROM client WHERE nom = ?');
        $query->execute([$nom]);
        $client = $query->fetch();
        if($client){
            return "le client $client->nom existe dans la base de donnee";
        }
        return null;
    }
}