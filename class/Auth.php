<?php
namespace Auth;
require_once "User.php";
use \PDO;
use User;
use User\User as UserUser;


/**
 * Auth
 * 
 * Cette classe verifier l'authentification d'utilisateur et recupere l'utilisateur
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
 * 
 */
class Auth{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }
    
    /**
     * user
     *
     * @return User\User
     */
    public function user(): ?User\User
    {
        if(session_status() === PHP_SESSION_NONE){
            session_start();
        }
        $id = $_SESSION['auth'] ?? null;
        if($id === null){
            return null;
        }
        $query = $this->pdo->prepare('SELECT * FROM utilisateur WHERE id = ?');
        $query->execute([$id]);
        $user = $query->fetchObject(User\User::class);
        return $user ?? null;
    }
    
    /**
     * login
     *
     * @param  string $user
     * @param  string $passwd
     * @return User\User
     */
    public function login(string $user, string $passwd): ?User\User
    {
        $query = $this->pdo->prepare('SELECT * FROM utilisateur WHERE utilisateur = :username');
        $query->execute(['username' => $user]);
        $user = $query->fetchObject(User\User::class);
        if($user == false){
            return null;
        }


        if(password_verify($passwd, $user->password)){
            if(session_status() === PHP_SESSION_NONE){
                session_start();
            }
            $_SESSION['auth'] = $user->id;
            return $user;
        }
        return null;
    }
}