<?php

/*
en entete: ref et sn
afficher le nom du paramaetre
suivant a la place de envoyer
min / max / valeur (unite de mesure)
*/
require_once dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . "class" . DIRECTORY_SEPARATOR . "DB" . DIRECTORY_SEPARATOR . "Unite.php";
require_once dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . "class" . DIRECTORY_SEPARATOR . "App.php";
require_once dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . "class" . DIRECTORY_SEPARATOR . "DB" . DIRECTORY_SEPARATOR . "Join.php";
require_once dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . "class" . DIRECTORY_SEPARATOR . "DB" . DIRECTORY_SEPARATOR . "enregistrement" . DIRECTORY_SEPARATOR . "Mesure.php";
require_once dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . "class" . DIRECTORY_SEPARATOR . "element" . DIRECTORY_SEPARATOR . "Form.php";
require_once dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . "class" . DIRECTORY_SEPARATOR . "DB" . DIRECTORY_SEPARATOR . "enregistrement" . DIRECTORY_SEPARATOR . "Fabrication.php";


use App\App;
use Fabrication\Fabrication;
use DB\Join\Join;
use Formulaire\Form;
use Unite\Unite;
use DB\Insert\Mesure;

//$state1 = new Select(App::getPDO());
$state = new Join(App::getPDO());
$user = App::getAuth()->user();
$state2 =  new Fabrication(App::getPDO());

$state1 = new Mesure(App::getPDO());

/*echo "<pre>";
print_r($user->id);
echo "</pre>";*/
?>

<?php
require_once dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . "inc" . DIRECTORY_SEPARATOR . "header.php";

$valeur_max = null;
$valeur_min = null;
$id = $_GET['idA'];
$idF = $_GET['f'];
$parametres = $state->get_parametres($id);
$error = null;

$nb_fab = isset($_GET['fab']) ? (int)$_GET['fab'] : null;
$last_sn = (int)$_GET['sn'];
$last_id = (int)$_GET['f'];
$first_id = isset($_GET['fab']) ?  abs($last_id - $nb_fab +1) : null;
$idA = (int)$_GET['idA'];
//pagination
$page = 1;
$pages = null;

if(isset($_GET['fab'])){
    if($_GET['fab'] == 0){
        header("Location: ../mesure.php");
    }
    $pages = $state2->count_id_fab($first_id, $last_id);
    var_dump($pages);
}

if(!empty($_POST)){
    foreach($parametres as $k=>$parametre){
        $val = $_POST["val{$k}"];
        $insert = $state1->enregistrement_mesure($val, $idF, $parametre->idP, $user->utilisateur);
        if(!$insert){
            $error = "donnee pas enregistrer";
            break;
        }
    }
    if(!isset($_GET['fab'])){
        header("Location: ../mesure.php");
    }
    else{
        $nb_fab -= 1;
        $last_id =  $nb_fab ? $last_id - 1 : $last_id;
        $last_sn =  $nb_fab ? $last_sn - 1 : $last_sn;
        header("Location: enr_mesure.php?idA=$idA&f=$last_id&fab=$nb_fab&sn=$last_sn");
    }
}
?>
<h2>numero de serie : <?= $last_sn ?></h2>
<h2>reference : <?= $state->get_reference("fabrications", $idA) ?></h2>
<?php if($error) : ?>
    <div class="danger">
       <?= $error ?>
    </div>
<?php endif; ?>
<form action="#" method="post">
    <?php if(is_array($parametres)) :?>
        <?php foreach($parametres as $k=>$parametre) : ?>
        <div class="form-content">
            <div class="form-container">
                <div class="form-content">
                    <?php
                        $unite = Unite::unite(App::getPDO(), $parametre->id_unite);
                        $valeur_min = (1- ($parametre->tolerance)/100) * $parametre->valeur_theorique;
                        echo Form::input("Min ($unite)", "text", "val_min{$k}", $valeur_min);
                    ?>
                </div>
                <div class="form-content">
                    <?= Form::input("valeur ($unite)", "text", "val{$k}") ?>
                </div>
                <div class="form-content">
                <?php
                    $valeur_max = (1+ ($parametre->tolerance)/100) * $parametre->valeur_theorique;
                    echo Form::input("Max ($unite)", "text", "val_max{$k}", $valeur_max);
                ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
    <?php endif; ?>
    <button>envoyer</button>
</form>
