<?php
require_once dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . "class" . DIRECTORY_SEPARATOR . "App.php";
require_once dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . "class" . DIRECTORY_SEPARATOR . "DB" . DIRECTORY_SEPARATOR . "Unite.php";
require_once dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . "class" . DIRECTORY_SEPARATOR . "DB" . DIRECTORY_SEPARATOR . "enregistrement" . DIRECTORY_SEPARATOR . "Parametre.php";
require_once dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . "class" . DIRECTORY_SEPARATOR . "element" . DIRECTORY_SEPARATOR . "Form.php";

use App\App;
use Parametre\Parametre;
use Unite\Unite;
use Formulaire\Form;
?>

<?php
$error = false;
$unites = Unite::liste_unite(App::getPDO());
$parametre = new Parametre(App::getPDO());
$nom = null;
$val = null;
$tolerance = null;
$id_u = null;
$id_p = null;
$enr = null;
$nbre = $_GET['nbreP'] ?? 1;

if(!empty($_POST)){
    $id_p = (int)$_GET['article'];
    $id_modif = $_GET['modif'] ?? null;
    if($id_modif){
        $nom = htmlentities($_POST["name1"]);
        $val = htmlentities($_POST["val1"]);
        $tolerance = htmlentities($_POST["tolerance1"]);
        $id_u = htmlentities(htmlentities($_POST["unite1"]));
        $enr = $parametre->update_parametre($nom, $val, $tolerance, $id_u, $id_p);
    }
    for($i = 1; $i <= $nbre; $i++){
        $nom = htmlentities($_POST["name{$i}"]);
        $val = htmlentities($_POST["val{$i}"]);
        $tolerance = htmlentities($_POST["tolerance{$i}"]);
        $id_u = htmlentities(htmlentities($_POST["unite{$i}"]));
        $enr = $parametre->enregistrement_parametre($nom, $val, $tolerance, $id_u, $id_p);
        if(!$enr){
            $error = true;
            break;
        }
    }
    header('Location:../article.php');
}
?>

<?php
require_once dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . "inc" . DIRECTORY_SEPARATOR . "header.php";
?>
<?php if($error) : ?>
    <div class="danger">
        erreur d'enregistrement
    </div>
<?php endif; ?>
<form action="#" method="post">
    <?php for($i = 1; $i <= $nbre; $i++) : ?>
        <div class="form-content">
            <div class="form-container">
                <div class="form-content">
                    <?= Form::input("nom parametre{$i}", "text", "name{$i}") ?>
                </div>
                <div class="form-content">
                    <?= Form::input("valeur{$i}", "text", "val{$i}") ?>
                </div>
            </div>
        </div>
        <div class="form-container">
                <div class="form-content">
                    <?= Form::input("tolerance{$i}", "number", "tolerance{$i}") ?>
                </div>
                <div class="form-content">
                    <label for="">selectionner une unite</label>
                    <select name="unite<?=$i?>" id="">
                        <?php foreach($unites as $unite) : ?>
                            <?= Form::select("$unite->id", "$unite->nom_unite") ?>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>
    <?php endfor; ?>
    <button>envoyer</button>
</form>