<?php
namespace UserAdd;

use \PDO;
use PDOException;

/**
 * Adduser
 * 
 * ajoute un utilisateur dans la base de donees
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
class Adduser{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    
    /**
     * register
     *
     * @param  string $user
     * @param  string $password
     * @param  string $role
     * @return bool
     */
    public function register(string $user, string $password, string $role): string
    { 
        if($password === null){
            try{
                $query = $this->pdo->prepare('INSERT INTO utilisateur(utilisateur, role) VALUES (:user, :role)');
                $query->execute([
                    "user" => $user,
                    "role" => $role
                ]);
                return 'true';
            }catch(PDOException $e){
                return $e;
            }
        }
        else{
            try{
                $password = password_hash($password, PASSWORD_DEFAULT);
                $query = $this->pdo->prepare('INSERT INTO utilisateur(utilisateur, password, role) VALUES (:user, :pswd, :role)');
                $query->execute([
                    "user" => $user,
                    "pswd" => $password,
                    "role" => $role
                ]);
                return 'true';
            }catch(PDOException $e){
                return $e;
            }
        }
    }
}