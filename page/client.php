<?php
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . "inc" . DIRECTORY_SEPARATOR . "session.php";
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . "class" . DIRECTORY_SEPARATOR . "element" . DIRECTORY_SEPARATOR . "Form.php";
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . "class" . DIRECTORY_SEPARATOR . "App.php";
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . "class" . DIRECTORY_SEPARATOR . "Adduser.php";
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . "class" . DIRECTORY_SEPARATOR . "Addclient.php";
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . "class" . DIRECTORY_SEPARATOR . "DB" . DIRECTORY_SEPARATOR . "Select.php";

use App\App;
use DB\Select\Select;
use ClientAdd\Addclient;
use Formulaire\Form;

$state1 = new Select(App::getPDO());
$state2 = new Addclient(App::getPDO());
$user = App::getAuth()->user();
?>

<?php
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . "inc" . DIRECTORY_SEPARATOR . "header.php";
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . "inc" . DIRECTORY_SEPARATOR . "menu.php";


//page actuelle
$client_result = null;
$pages = null;
$page = (int)($_GET['p'] ?? 1);

if(isset($_GET['q'])){
    $clients = $state1->select("client", $page, "nom", $_GET['q']);
    //renvoie le noombre de pages
    $pages = $state1->count_page("client", "id", "nom", $_GET['q']);
}
else{
    $clients = $state1->select("client", $page);
    //renvoie le noombre de pages
    $pages = $state1->count_page("client", "id");
}

if(isset($_POST['nomc'])){
    $nom = htmlentities($_POST['nomc']);
    $client_result = $state2->client_verifie($nom);
    if(!$client_result){
        $state2->client_add($nom);
        header('Location: client.php');
    }
}

?>

<?php if($client_result) : ?>
    <div><?= $client_result ?></div>
<?php endif; ?>

<form action="client.php" class="form-search">
    <input type="search" name="q" id="" value="<?= htmlentities($_GET['q'] ?? null) ?>">
    <button>rechercer</button>
</form>

<div class="box">
    <?php if(is_array($clients)) : ?>
        <table class="client">
            <thead>
                <tr>
                    <th>liste des clients</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($clients as $client) : ?>
                    <tr> 
                        <td><a href="fabrication.php?client=<?= $client->nom ?>"><?= $client->nom ?></a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
    <form action="#" method="post">
        <?= Form::input("nom du client", "text", "nomc") ?>
        <button>ajouter client</button>
    </form>
</div>

<?php if($pages) : ?>
    <?php if($pages > 1 && $page > 1) : ?>
        <a href="?<?= App::url("p", $page - 1) ?>">page precedente</a>
    <?php endif; ?>

    <?php if($pages > 1 && $page < $pages) : ?>
        <a href="?<?= App::url("p", $page + 1) ?>">page suivante</a>
    <?php endif; ?>
<?php endif; ?>

<?php
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . "inc" . DIRECTORY_SEPARATOR . "footer.php";
?>