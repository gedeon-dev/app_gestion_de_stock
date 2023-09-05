<?php
header('content-type: text/csv');
header('content-Disposition: attachment; filename="rapport_fabrication.csv');
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . "class" . DIRECTORY_SEPARATOR . "app.php";
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . "class" . DIRECTORY_SEPARATOR . "DB" . DIRECTORY_SEPARATOR . "Join.php";
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . "class" . DIRECTORY_SEPARATOR . "DB" . DIRECTORY_SEPARATOR . "Select.php";

use App\App;
use DB\Join\Join;
use DB\Select\Select;

$state1 = new Select(App::getPDO());
$state2 = new Join(App::getPDO());

$fabrications = $state1->just_select("fabrications");


echo '"'."Reference". '";' . '"' ."Numero de serie". '";' . '"' ."Client". '";' . '"' ."Date". '"'  . "\n";
foreach($fabrications as $fabrication){
    echo '"'.$state2->get_reference("fabrications", $fabrication->id_article). '";' . '"' .$fabrication->sn. '";' . '"' .$state2->get_client($fabrication->id_client). '";' . '"' .$fabrication->date_fabrication. '"'  . "\n";    
}