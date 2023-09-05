<?php
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . "inc" . DIRECTORY_SEPARATOR . "session.php";
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . "class" . DIRECTORY_SEPARATOR . "App.php";
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . "class" . DIRECTORY_SEPARATOR . "DB" . DIRECTORY_SEPARATOR . "Select.php";
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . "class" . DIRECTORY_SEPARATOR . "DB" . DIRECTORY_SEPARATOR . "Join.php";

use App\App;
use DB\Select\Select;
use DB\Join\Join;

$state1 = new Select(App::getPDO());
$state2 = new Join(App::getPDO());
$user = App::getAuth()->user();
?>

<?php
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . "inc" . DIRECTORY_SEPARATOR . "header.php";
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . "inc" . DIRECTORY_SEPARATOR . "menu.php";

$idF = null;
$id_article = null;
$nb_fab = null;

//page actuelle
$pages = null;
$page = (int)($_GET['p'] ?? 1);

if(isset($_GET['sn'], $_GET['number'])){
    $sn = $_GET['sn'];
    $nb_fab = $_GET['number'];
    $interval = $sn - $nb_fab;
    $fabrications = $state1->get_fabrication_with_bettwen($interval, $sn);
}
elseif(isset($_GET['q'])){
    $fabrications = $state1->select("fabrications", $page, "date_fabrication", $_GET['q'], "id_article", "id_client");
    //renvoie le noombre de pages
    $pages = $state1->count_page("fabrications", "idF", "date_fabrication", $_GET['q'], "id_article", "id_client");
}
elseif(isset($_GET['cleint'])){
    $client = (string)$_GET['client'];
    $fabrications = $state1->get_fab_client($client);
    //renvoie le noombre de pages
    $pages = $state1->count_page("fabrications", "idF");
}
else{
    $fabrications = $state1->select("fabrications", $page);
    //renvoie le noombre de pages
    $pages = $state1->count_page("fabrications", "idF");
}

//die();
?>
<form action="#" class="form-search">
    <input type="search" name="q" id="" value="<?= htmlentities($_GET['q'] ?? null) ?>" placeholder="recherche par reference">
    <button>rechercer</button>
</form>
<table>
    <thead>
        <tr>
            <th>reference</th>
            <th>numero de serie</th>
            <th>client</th>
            <th>date</th>
        </tr>
    </thead>
    <tbody>
        <?php if(is_array($fabrications)) : ?>
            <?php foreach($fabrications as $fabrication) : ?>
                <tr>
                    <td><a href="./enregistrement/enr_mesure.php?idA=<?= $fabrication->id_article ?>&f=<?= $fabrication->idF?>&sn=<?= $fabrication->sn?>"><?= $state2->get_reference("fabrications", $fabrication->id_article) ?></a></td>
                    <td><a href="./enregistrement/enr_mesure.php?idA=<?= $fabrication->id_article ?>&f=<?= $fabrication->idF?>&sn=<?= $fabrication->sn?>"><?= $fabrication->sn ?></a></td>
                    <td><?= $state2->get_client($fabrication->id_client)?></td>
                    <td><?= $fabrication->date_fabrication ?></td>
                    <?php $id_article = $fabrication->id_article; $idF = $fabrication->idF; $sn = $fabrication->sn; ?>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>   
    </tbody>
</table>


<?php if($pages) : ?>
    <?php if($pages > 1 && $page > 1) : ?>
        <a href="?<?= App::url("p", $page - 1) ?>" class="previous">page precedente</a>
    <?php endif; ?>

    <?php if($pages > 1 && $page < $pages) : ?>
        <a href="?<?= App::url("p", $page + 1) ?>" class="next">page suivante</a>
    <?php endif; ?>
<?php endif; ?>


<?php if(isset($_GET['sn'], $_GET['number'])) : ?>
    <p class="p"><a href="./enregistrement/enr_mesure.php?idA=<?= $id_article ?>&f=<?= $idF?>&fab=<?= $nb_fab ?>&sn=<?= $fabrication->sn?>" class="add">ajouter les mesures</a></p>
<?php else : ?>
    <p class="p"><a href="./enregistrement/enr_fabrication.php" class="add">ajouter une fabrication</a></p>
<?php endif; ?>
<p class="rap"><a href="rapport_fabrication.php">Rapport de fabrication</a></p>

<?php
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . "inc" . DIRECTORY_SEPARATOR . "footer.php";
?>