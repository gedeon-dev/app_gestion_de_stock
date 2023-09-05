<?php
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . "inc" . DIRECTORY_SEPARATOR . "session.php";
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . "class" . DIRECTORY_SEPARATOR . "App.php";
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . "class" . DIRECTORY_SEPARATOR . "DB" . DIRECTORY_SEPARATOR . "Select.php";

use App\App;
use DB\Select\Select;

$user = App::getAuth()->user();
$state1 = new Select(App::getPDO());
?>


<?php
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . "inc" . DIRECTORY_SEPARATOR . "header.php";

//page actuelle
$pages = null;
$page = (int)($_GET['p'] ?? 1);
if(isset($_GET['q'])){
    $articles = $state1->select("articles", $page, "reference", $_GET['q'], "type");
    //renvoie le noombre de pages
    $pages = $state1->count_page("articles", "id", "reference", $_GET['q'], null, null, "type");
}
else{
    $articles = $state1->select("articles", $page);
    //renvoie le noombre de pages
    $pages = $state1->count_page('articles', 'id');
}
?>

<?php if(isset($_GET['erreur'])) : ?>
    <div class="danger">
        vous n'avez pas les droits
    </div>
<?php endif; ?>


<?php require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . "inc" . DIRECTORY_SEPARATOR . "menu.php";?>

<form action="#" class="form-search">
    <input type="search" name="q" id="" value="<?= htmlentities($_GET['q'] ?? null) ?>">
    <button>rechercer</button>
</form>
<table>
    <thead>
        <tr>
            <th>reference</th>
            <th>designation</th>
            <th>type de produit</th>
            <th>date</th>
        </tr>
    </thead>
    <tbody>
        <?php if(is_array($articles)) : ?>
            <?php foreach($articles as $article) : ?>
                <tr>
                    <td><a href="parametre.php?id=<?= $article->id ?>"><?= $article->reference ?></a></td>
                    <td><?= $article->designation ?></td>
                    <td><?= $article->type ?></td>
                    <td><?= $article->date ?></td>
                </tr>
            <?php endforeach; ?> 
        <?php endif; ?>  
    </tbody>
</table>

<div>
    <?php if($pages > 1 && $page > 1) : ?>
    <a href="?<?= App::url("p", $page - 1) ?>" class="previous">page precedente</a>
    <?php endif; ?>

    <?php if($pages > 1 && $page < $pages) : ?>
            <a href="?<?= App::url("p", $page + 1) ?>" class="next">page suivante</a>
    <?php endif; ?>
</div>
<?php if($user->role === 'admin') : ?>
    <p class="p"><a href="enregistrement/enr_article.php" class="add">ajoutez un article</a></p>
<?php endif; ?>
<?php
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . "inc" . DIRECTORY_SEPARATOR . "footer.php";
?>