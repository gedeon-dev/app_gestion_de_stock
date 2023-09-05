<?php
require_once dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . "class" . DIRECTORY_SEPARATOR . "App.php";
require_once dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . "class" . DIRECTORY_SEPARATOR . "DB" . DIRECTORY_SEPARATOR . "Select.php";
require_once dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . "class" . DIRECTORY_SEPARATOR . "DB" . DIRECTORY_SEPARATOR . "enregistrement" . DIRECTORY_SEPARATOR . "Article.php";
require_once dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . "class" . DIRECTORY_SEPARATOR . "element" . DIRECTORY_SEPARATOR . "Form.php";

use App\App;
use Formulaire\Form;
use Enregistrement\Article\Enregistrement;
use DB\Select\Select;
?>

<?php
$error = null;
$enregistrement = null;
$nbre_parmatre = null;
$user = App::getAuth()->user();
$id = null;
$ref = null;
$desc = null;
$type = null;
if($user === null || $user->role !== 'admin'){
    header('Location: visualiser.php?erreur=1');
    exit();
}
if(!empty($_GET['prod'])){
    $id = $_GET['prod'];
    $pdo = new Select(App::getPDO());
    $articles = $pdo->select_only("articles", $id);
}

if(!empty($_POST)){
    $ref = htmlentities($_POST['ref']);
    $designation = htmlentities($_POST['desc']);
    $type = htmlentities($_POST['type']);
    $nbre_parmatre = htmlentities($_POST['nbr']);
    $pdo = new Enregistrement(App::getPDO());

    if(isset($_GET['modif'])){
        $enregistrement = $pdo->update_article($ref, $designation, $type, $id);
        header("Location:../article.php");
        exit();
    }
    elseif(!isset($_GET['modif'])){
        $enregistrement = $pdo->enregistrement_article($ref, $designation, $type, $id);
        var_dump($enregistrement);
        header("Location: enr_parametre.php?article=$enregistrement&nbreP=$nbre_parmatre");
        exit();
    }
    else{
        $error = "echec d'enregistrement";
    }
}
?>


<?php
require_once dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . "inc" . DIRECTORY_SEPARATOR . "header.php";
?>
<?php if($error) : ?>
    <div class="danger">
        erreur de connexion
    </div>
<?php endif; ?>
<form action="#" method="post" class="form-insert">
    <fieldset>
        <legend>ajoutez un article</legend>
        <div class="form-container">
            <?= Form::input("reference", "text", "ref", $articles->reference ?? null) ?>
        </div>
        <?= Form::input("designation", "text", "desc", $articles->designation ?? null) ?>
    <div class="form-container">
            <div class="form-content">
                <?= Form::input("nombre de parmetre", "number", "nbr") ?>
            </div>
            <div class="form-content">
                <select name="type" id="">
                    <option value="transformateur">transformateur</option>
                    <option value="self">self</option>
                </select>
            </div>
        </div>
    </div>
    </fieldset>
    <button>envoyer</button>
    <input type="reset" value="annuler">
</form>
<li><a href="../article.php">terminer</a></li>