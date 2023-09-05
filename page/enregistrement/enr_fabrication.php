<?php
error:
require_once dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . "class" . DIRECTORY_SEPARATOR . "App.php";
require_once dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . "class" . DIRECTORY_SEPARATOR . "DB" . DIRECTORY_SEPARATOR . "Select.php";
require_once dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . "class" . DIRECTORY_SEPARATOR . "DB" . DIRECTORY_SEPARATOR . "enregistrement" . DIRECTORY_SEPARATOR . "Fabrication.php";
require_once dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . "class" . DIRECTORY_SEPARATOR . "element" . DIRECTORY_SEPARATOR . "Form.php";

use Formulaire\Form;
use App\App;
use Fabrication\Fabrication;
use DB\Select\Select;

$pdo = new Fabrication(App::getPDO());
$state2 = new Select(App::getPDO());
?>

<?php
$error = null;
$reference = '';
$clients = $state2->get_name_client();
if(isset($_GET['id'])){
$reference = $state2->get_ref((int)$_GET['id']) ?? null;
}
if(!empty($_POST)){
    $sn = (int)$_POST['sn'];
    $date = htmlentities($_POST['date']);
    $number = htmlentities($_POST['nbr']);
    $ref = htmlentities($_POST['ref']);
    $client = htmlentities($_POST['client']);
    $id_a = (int)($_GET['id'] ?? $state2->get_id_article($ref));
    $id_c = $state2->get_id_client($client);
    for($i = 0; $i < $number; $i++){
        $error = $pdo->sn_verifie($sn);
        if($error){
            break;
        }
        $enr = $pdo->enr_fabrication($sn, $date, $id_a, $id_c);
        $sn += 1;
    }
    if(!$error){
        header("Location:../fabrication.php?sn=$sn&number=$number");
    }
}
?>

<?php
require_once dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . "inc" . DIRECTORY_SEPARATOR . "header.php";
?>

<?php if($error) : ?>
    <div class="danger">
        <?= $error ?>
    </div>
<?php endif; ?>

<form action="#" method="post">
    <div class="form-content">
        <div class="form-container">
            <div class="form-content">
                <?= Form::input("nombre de fabrication", "number", "nbr") ?>
            </div>
        </div>
    </div>
    <div class="form-content">
        <div class="form-container">
            <div class="form-content">
                <?= Form::input("reference", "texte", "ref", $reference)?>
            </div>
        </div>
    </div>
    <div class="form-container">
            <div class="form-content">
                <?= Form::input("numero de serie", "text", "sn") ?>
            </div>
        </div>
    </div>
    <div class="form-container">
            <div class="form-content">
                <select name="client" id="">
                    <?php foreach($clients as $client_name) : ?>
                        <option value="<?= $client_name->nom ?>"><?= $client_name->nom ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </div>
    <div class="form-container">
            <div class="form-content">
                <?= Form::input("date", "date", "date") ?>
            </div>
        </div>
    </div>
    <button>envoyer</button>
</form>