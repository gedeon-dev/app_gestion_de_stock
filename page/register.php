<?php
if(session_status() === PHP_SESSION_NONE){
    session_start();
}
session_destroy();
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . "class" . DIRECTORY_SEPARATOR . "element" . DIRECTORY_SEPARATOR . "Form.php";
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . "class" . DIRECTORY_SEPARATOR . "App.php";
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . "class" . DIRECTORY_SEPARATOR . "Adduser.php";

use Formulaire\Form;
use App\App;
use UserAdd\Adduser;
?>

<?php
$pdo = new Adduser(App::getPDO());
$error = false;
$user = null;
$password = null;
$role = null;
if(!empty($_POST)){
    $user = htmlentities($_POST['user']);
    $password = htmlentities($_POST['pswd']);
    $role = htmlentities($_POST['role']);
    if($pdo->register($user, $password, $role)){
        header('Location: ../index.php');
        exit();
    }
    else{
        $error = true;
    }
}
?>

<?php
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . "inc" . DIRECTORY_SEPARATOR . "header.php";
?>
<?php if($error) : ?>
    <div class="error">
        echec d'enregistrement
    </div>
<?php endif; ?>


<form action="#" method="post">
    <div class="form-content">
        <div class="form-container">
            <div class="form-content">
                <?= Form::input("user", "text", "user") ?>
            </div>
        </div>
    </div>
    <div class="form-container">
            <div class="form-content">
                <?= Form::input("Mot de passe", "password", "pswd") ?>
            </div>
        </div>
    </div>
    <div class="form-container">
            <div class="form-content">
                <?= Form::input("role", "text", "role") ?>
            </div>
        </div>
    </div>
    <button>envoyer</button>
</form>
