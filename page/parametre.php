<?php
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . "inc" . DIRECTORY_SEPARATOR . "session.php";
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . "class" . DIRECTORY_SEPARATOR . "App.php";
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . "class" . DIRECTORY_SEPARATOR . "DB" . DIRECTORY_SEPARATOR . "Select.php";
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . "class" . DIRECTORY_SEPARATOR . "DB" . DIRECTORY_SEPARATOR . "Unite.php";
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . "class" . DIRECTORY_SEPARATOR . "DB" . DIRECTORY_SEPARATOR . "Join.php";

use App\App;
use DB\Select\Select;
use Unite\Unite;
use DB\Join\Join;


$user = App::getAuth()->user();
$state1 = new Select(App::getPDO());
$state2 = new Join(App::getPDO());
?>


<?php
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . "inc" . DIRECTORY_SEPARATOR . "header.php";

$page = (int)($_GET['p'] ?? 1);
$error = null;
$id = null;
if(isset($_GET['id'])){
    $id = (int)$_GET['id'];
    $article = $state1->select_only("articles", $id);
    $parametres = $state2->get_parametres($id);
}
if($parametres === null){
    $error = "cet article n'a pas de parmetres";
}
?>

<?php require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . "inc" . DIRECTORY_SEPARATOR . "menu.php"; ?>

<?php if($error) : ?>
    <div class="danger">
        <?= $error ?>
    </div>
<?php endif; ?>
<h1><?= $article->reference ?> : <?= $article->designation ?></h1>
<?php if($user->role === "admin") : ?>
    <a href="enregistrement/enr_article.php?prod=<?= $article->id ?>&modif=1">modifier produit</a>
<?php endif; ?>
<a href="enregistrement/enr_fabrication.php?id=<?= $article->id ?>">ajout fabrication</a>
<table>
    <thead>
        <tr>
            <td>nom parametre</td>
            <td>valeur theorique</td>
            <td>tolerance</td>
        </tr>
    </thead>
    <tbody>
        <?php if($parametres) : ?>
            <?php foreach($parametres as $parametre) : ?>
                <tr>
                    <td>
                        <a href="<?= ($user->role === 'admin' ? "enregistrement/enr_parametre.php?article=$article->id&modif=1" : '') ?>">
                            <?= $parametre->nom_parametre ?>
                        </a>
                    </td>
                    <td style="text-align: right;"><?= $parametre->valeur_theorique ?> <?=  Unite::unite(App::getPDO(), $parametre->id_unite) ?></td>
                    <td><?= $parametre->tolerance ?>%</td>
                </tr>
            <?php endforeach; ?>  
        <?php endif; ?>
    </tbody>
</table>

<?php if($user->role === "admin") : ?>
    <a href="enregistrement/enr_parametre.php?article=<?= $article->id ?>&ajout=1">ajouter un parametre</a>
<?php endif; ?>    
<br>
<a href="article.php">fermer</a>
<?php
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . "inc" . DIRECTORY_SEPARATOR . "footer.php";
?>