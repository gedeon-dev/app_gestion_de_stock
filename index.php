<?php
session_start();
require_once "class" . DIRECTORY_SEPARATOR . "element" . DIRECTORY_SEPARATOR . "Form.php";
require_once "class" . DIRECTORY_SEPARATOR .  "App.php";

use Formulaire\Form;
use App\App;

$auth = App::getAuth();
?>

<?php
$error = false;

if($auth->user()){
    header('Location: page/article.php?login=1');
    exit();
}
if(!empty($_POST)){
    $user = $auth->login($_POST['user'], $_POST['passwd']);
    if($user){
        header("Location: page/article.php?login=$user->id");
        exit();
    }
    $error = true;
}
?>

<?php
require_once "inc" . DIRECTORY_SEPARATOR . "header.php";
?>
<?php if($error) : ?>
    <div class="error">
        erreur de connexion
    </div>
<?php endif; ?>
<form action="#" method="post">
    <div class="form-content">
        <div class="form-container">
            <div class="form-content">
                <?= Form::input("Utilisateur", "text", "user") ?>
            </div>
        </div>
    </div>
    <div class="form-container">
            <div class="form-content">
                <?= Form::input("Mot de passe", "password", "passwd") ?>
            </div>
        </div>
    </div>
    <button>envoyer</button>
</form>