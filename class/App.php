<?php
namespace App;
require_once "Auth.php";

use \PDO;
use Auth\Auth;


/**
 * App
 * 
 * 
 * fait appel a PDO et a Auth
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
 * @var Auth $auth stock Auth
 */
class App{
    public static $pdo;
    public static $auth;    
    /**
     * getPDO
     *
     * @return PDO
     */
    public static function getPDO(): PDO
    {
        if(!self::$pdo){
            self::$pdo = new PDO("mysql:dbname=productionecei;host=localhost", "production", "production@2022", [
                PDO::ATTR_ERRMODE =>  PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
            ]);
        }
        return self::$pdo;
    }
    
    /**
     * getAuth
     *
     * @return Auth
     */
    public static function getAuth(): Auth
    {
        if(!self::$auth){
            self::$auth = new Auth(self::getPDO());
        }
        return self::$auth;
    }

    public static function url(string $param, $value) : string
    {
        return http_build_query(array_merge($_GET, [$param => $value]));
    }
}