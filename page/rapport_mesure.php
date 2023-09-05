<?php
header('content-type: text/csv');
header('content-Disposition: attachment; filename="rapport_mesure.csv');

require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . "class" . DIRECTORY_SEPARATOR . "DB" . DIRECTORY_SEPARATOR . "Unite.php";
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . "class" . DIRECTORY_SEPARATOR . "app.php";
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . "class" . DIRECTORY_SEPARATOR . "DB" . DIRECTORY_SEPARATOR . "Join.php";
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . "class" . DIRECTORY_SEPARATOR . "DB" . DIRECTORY_SEPARATOR . "Select.php";

use App\App;
use DB\Join\Join;
use DB\Select\Select;
use Unite\Unite;


$state1 = new Select(App::getPDO());
$state2 = new Join(App::getPDO());

$mesures = $state1->just_select("mesures");


echo '"'."References". '";' . '"' ."Numero de serie". '";' . '"' ."Parametres". '";'  . '"' ."Valeurs". '";'  . '"' ."Unite". '";'  . '"' ."testeur". '";'  . '"' ."Client". '";'  . '"' ."Date". '"'  . "\n";
foreach($mesures as $mesure){
    $idF = $state2->get_fabrication($mesure->id_fabrication);
    $idP = $state2->get_parametres_by_idP($mesure->id_parametre);
    $id_article = (int)$idF->id_article; 
    $id_sn = $idF->sn;
    $id_client = $idF->id_client;
    $ref = $state2->get_reference("fabrications", $id_article);
    $param = $idP[0]->nom_parametre;
    $unite = Unite::unite(App::getPDO(), $idP[0]->id_unite);
    $client = $state2->get_client($id_client);
    echo '"'."$ref". '";' . '"' ."$id_sn". '";' . '"' ."$param". '";'  . '"' ."$mesure->mesure". '";'  . '"' ."$unite". '";'  . '"' ."$mesure->testeur". '";'  . '"' ."$client". '";'  . '"' ."$mesure->date". '"'  . "\n";    
}