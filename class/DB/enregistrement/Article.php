<?php
namespace Enregistrement\Article;

require_once "article_enr.php";

use PDO;
use Article\Article;
use PDOException;

/**
 * Enregistrement
 * 
 * Cette classe enregistre et recupere un article
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


class Enregistrement{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }
    
    /**
     * enregistrement_article
     *
     * enregistre un article
     * 
     * @param  string $ref
     * @param  string $designation
     * @param  string $type
     * @param  date(string) $date
     * @return string l'id de l'article enregistre
     */
    public function enregistrement_article(string $ref, string $designation, string $type, int $id = null): ?string
    {
        try{
            $query = $this->pdo->prepare('INSERT INTO articles (reference, designation, type, date) VALUES (:ref, :desc, :type, CURDATE())');
            $query->execute([
                "ref" => $ref,
                "desc" => $designation,
                "type" => $type
            ]);
            return $this->pdo->lastInsertId();;
        }catch(PDOException $e){
            return $e;
        }
    }
    
    /**
     * recupere_article
     *
     * cette methode recupere un article
     * 
     * @param  int $id c'est l'id de l'article a selectionner
     * @return Article retourne un tableau d'article sous forme d'objet
     */


    public function recupere_article($id): ?Article
    {
        $query = $this->pdo->prepare('SELECT * FROM articles WHERE id = ?');
        $query->execute([$id]);
        $article = $query->fetchObject(Article::class);
        return $article ?? null;
    }

    public function update_article(string $ref, string $designation, string $type, int $id = null): ?string
    {
        try{
            $query = $this->pdo->prepare('UPDATE articles SET reference = :ref, designation = :desc, type = :type WHERE id = :id');
            $query->execute([
                "ref" => $ref,
                "desc" => $designation,
                "type" => $type,
                "id" => $id
            ]);
            return $id;
        }catch(PDOException $e){
            return $e;
        }
    }
}