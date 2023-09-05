<?php

namespace DB\Select;

use PDO;

class Select{

    const PER_PAGE = 15;
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function select(string $table, int $page = null, string $col = null, string $search = null, string $col2 = null, string $col3 = null, string $col4 = null) : ?array
    {
        $nb_page = self::PER_PAGE;
        $offeset = ($page-1) * $nb_page;
        $req = "SELECT * FROM $table";
        $query = null;
        if($col2 && $search && $col3 && $col){
            $req .= " WHERE $col LIKE '%{$search}%' OR $col2 LIKE '%{$this->get_by_select("articles", "reference", $search, "id")}%' OR $col3 LIKE '%{$this->get_by_select("client", "nom", $search, "id")}%'";
        }
        elseif($col2 && $search && $col){
            $req .= " WHERE $col LIKE '%{$search}%' OR $col2 LIKE '%{$this->get_by_select("articles", "reference", $search, "id")}%'";
        }
        elseif($col && $search){
            $req .= " WHERE $col LIKE '%{$search}%'";
        }
        elseif($col && $search && $col4){
            $req .= " WHERE $col LIKE '%{$search}%' OR $col4 LIKE '%{$search}%'";
        }
        $req .= " LIMIT $nb_page OFFSET $offeset";
        $query = $this->pdo->prepare("$req");
        $query->execute();
        $result = $query->fetchAll();
        if($result){
            return $result;
        }
        return null;
    }
    

    public function select_only($table, $id)
    {
        $query = $this->pdo->prepare("SELECT * FROM  $table WHERE id = :id");
        $query->execute(['id' => $id]);
        $result = $query->fetch();
        if($result){
            return $result;
        }
        return null;
    }

    public function just_select($table)
    {
        $query = $this->pdo->prepare("SELECT * FROM  $table");
        $query->execute();
        $result = $query->fetchAll();
        if($result){
            return $result;
        }
        return null;
    }

    public function count_page(string $table, string $id, string $col = null, string $search = null, string $col2 = null, string $col3 = null, string $col4 = null) : ?int
    {
        $req = "SELECT COUNT($id) as count FROM $table";
        if($col2 && $search && $col3 && $col){
            $req .= " WHERE $col LIKE '%{$search}%' OR $col2 LIKE '%{$this->get_by_select("articles", "reference", $search, "id")}%' OR $col3 LIKE '%{$this->get_by_select("client", "nom", $search, "id")}%'";
        }
        elseif($col2 && $search && $col){
            $req .= " WHERE $col LIKE '%{$search}%' OR $col2 LIKE '%{$this->get_by_select("articles", "reference", $search, "id")}%'";
        }
        elseif($col && $search){
            $req .= " WHERE $col LIKE '%{$search}%'";
        }
        elseif($col && $search && $col4){
            $req .= " WHERE $col LIKE '%{$search}%' OR $col4 LIKE '%{$search}%'";
        }

        $query = $this->pdo->prepare($req);
        $query->execute();
        $result = (int)$query->fetch()->count;
        $pages = ceil($result / self::PER_PAGE);
        if($pages){
            return $pages;
        }
        return null;
    }
    public function get_id_article(string $article): int
    {
        $req =  $this->pdo->prepare("SELECT id FROM articles where reference = :article");
        $req->execute(['article' => $article]);
        $id = $req->fetch();
        return (int)$id->id;
    }

    public function get_ref(int $id_article): string
    {
        $req =  $this->pdo->prepare("SELECT reference FROM articles where id = :article");
        $req->execute(['article' => $id_article]);
        $ref = $req->fetch();
        return $ref->reference;
    }

    public function get_id_client(string $client)
    {
        $query = $this->pdo->prepare("SELECT id FROM client WHERE nom = :nom");
        $query->execute(['nom' => $client]);
        $id = $query->fetch();
        return (int)$id->id;
    }

    public function get_name_client() : ?array
    {
        $query = $this->pdo->prepare("SELECT nom FROM client");
        $query->execute();
        $result = $query->fetchAll();
        if($result){
            return $result;
        }
        return null;
    }

    public function get_fabrication_with_bettwen(int $val1, int $val2) : ?array
    {
        $query = $this->pdo->prepare("SELECT * FROM fabrications  WHERE sn BETWEEN :val1 AND :val2");
        $query->execute([
            'val1' => $val1,
            'val2' => $val2
        ]);
        $fabrications = $query->fetchAll();
        return $fabrications;
    }

    public function get_fab_client(string $client) : ?array
    {
        $query = $this->pdo->prepare("SELECT * FROM fabrications  WHERE client = :client");
        $query->execute(['client' => $client]);
        $fab_client = $query->fetchAll();
        if($fab_client){
            return $fab_client;
        }
        return null;
    }

    public function get_by_select(string $table, $colum, $value, $colum_id)
    {
        $query = $this->pdo->prepare("SELECT * FROM $table WHERE $colum = ?");
        $query->execute([$value]);
        $result = $query->fetch();
        if($result){
            return $result->{$colum_id};
        }
        return null;
    }


    public function select_M(string $table, int $page = null, string $col = null, string $search = null, string $col2 = null, string $col3 = null) : ?array
    {
        $nb_page = self::PER_PAGE;
        $offeset = ($page-1) * $nb_page;
        $req = "SELECT * FROM $table";
        $parametre = $this->get_by_select("parametres", "nom_parametre", $search, "idP");
        $req .= " WHERE $col LIKE '%{$parametre}%' OR $col2 LIKE '%{$parametre}%' OR $col3 LIKE '%{$this->get_by_select("client", "nom", $search, "id")}%'";
        $req .= " LIMIT $nb_page OFFSET $offeset";
        $query = $this->pdo->prepare("$req");
        $query->execute();
        $result = $query->fetchAll();
        if($result){
            return $result;
        }
        return null;
    }
}