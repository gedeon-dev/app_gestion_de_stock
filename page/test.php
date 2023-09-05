<?php
$pdo = new PDO("mysql:dbname=ecei;host=127.0.0.1", "root", "", [
    PDO::ATTR_ERRMODE =>  PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
]);
$value = "348A1294GE";
$k = "id";
$query = $pdo->prepare("SELECT * FROM articles WHERE reference = ?");
$query->execute([$value]);
$result = $query->fetch();
var_dump($result->{$k});