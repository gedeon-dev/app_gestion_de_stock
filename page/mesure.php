<?php
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . "inc" . DIRECTORY_SEPARATOR . "session.php";
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . "class" . DIRECTORY_SEPARATOR . "App.php";
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . "class" . DIRECTORY_SEPARATOR . "DB" . DIRECTORY_SEPARATOR . "Select.php";
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . "class" . DIRECTORY_SEPARATOR . "DB" . DIRECTORY_SEPARATOR . "Join.php";
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . "class" . DIRECTORY_SEPARATOR . "DB" . DIRECTORY_SEPARATOR . "Unite.php";

use App\App;
use DB\Select\Select;
use DB\Join\Join;
use Unite\Unite;

$state1 = new Select(App::getPDO());
$state2 = new Join(App::getPDO());
$user = App::getAuth()->user();
?>

<?php
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . "inc" . DIRECTORY_SEPARATOR . "header.php";
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . "inc" . DIRECTORY_SEPARATOR . "menu.php";


//page actuelle
$pages = null;
$page = (int)($_GET['p'] ?? 1);

if(isset($_GET['q'])){
    $mesures = $state1->select("mesures", $page, "mesure", $_GET['q'], "id_article", "id_client");
    //renvoie le noombre de pages
    $pages = $state1->count_page("mesures", "idM", "mesure", $_GET['q']);
}
else{
    $mesures = $state1->select("mesures", $page);
    //renvoie le noombre de pages
    $pages = $state1->count_page("mesures", "idM");
}

?>
<form action="#" class="form-search">
    <input type="search" name="q" id="" value="<?= htmlentities($_GET['q'] ?? null) ?>">
    <button>rechercer</button>
</form>
<table>
    <thead>
        <tr>
            <th>reference</th>
            <th>numero de serie</th>
            <th>parametre</th>
            <th>valeur</th>
            <th>unite</th>
            <th>testeur</th>
            <th>client</th>
            <th>Date</th>
        </tr>
    </thead>
    <tbody>
        <?php if(is_array($mesures)) : ?>
            <?php foreach($mesures as $mesure) : ?>
                <?php
                $idF = $state2->get_fabrication($mesure->id_fabrication);
                $idP = $state2->get_parametres_by_idP($mesure->id_parametre);
                $id_article = (int)$idF->id_article; 
                $id_sn = $idF->sn;
                $id_client = $idF->id_client;
                ?>
                <tr>
                    <td><?= $state2->get_reference("fabrications", $id_article) ?></a></td>
                    <td><?= $id_sn ?></a></td>
                    <td><?= $idP[0]->nom_parametre ?></td>
                    <td><?= $mesure->mesure ?></td>
                    <td><?= Unite::unite(App::getPDO(), $idP[0]->id_unite) ?></td>
                    <td><?= $mesure->testeur ?></td>
                    <td><?= $state2->get_client($id_client) ?></a></td>
                    <td><?= $mesure->date ?></td>
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
<p class="rap"><a href="rapport_mesure.php">Rapport de mesure</a></p>
<?php
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . "inc" . DIRECTORY_SEPARATOR . "footer.php";
?>