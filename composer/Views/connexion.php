<?php
    require_once 'app/Models/Connexion.php';
    require_once "app/Models/User.php";

use Titus\Dolmen\Models\Connexion;
use Titus\Dolmen\Models\User;

session_start();
//$id_session = session_id();

    if (!empty($_POST['login_user']) && !empty($_POST['password_hash_user'])) {
        //var_dump($_POST);
        $conn = Connexion::getInstance()->getConn();

        $login = $_POST['login_user'];
        $userPassword = $_POST['password_hash_user'];


        try {
            $stt = $conn->prepare("SELECT * FROM `users` where `login_user` = ?");
            $stt->bindParam(1, $login);
            $stt->execute();

            $dbhash = null;
            $userArray = [];
            if ($stt->rowCount() === 1) {
                $userArray = $stt->fetch();
                $dbhash = $userArray['password_hash_user'];
            }
            if (password_verify($userPassword, $dbhash)) {
                $user = User::hydrate($userArray);
                $_SESSION['user'] = base64_encode(serialize($user));
//                $user2 = unserialize(base64_decode($_SESSION['user']));
                header('location: connexion.php');
                exit;
            }


        } catch (\PDOException $e) {
            echo $e->getMessage();
        }
    }
    ?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../../public/assets/styles/normalize.css">
    <link rel="stylesheet" href="../../public/assets/styles/styles.css">
    <link rel="icon" type="image/x-icon" sizes="16x16" href="../../public/assets/images/Icones/chasseur_dolmen.svg">
    <title>Compte</title>
</head>
<body>
<header>
    <?php
    include 'app/Views/templates/navbar.php';

    ?>

</header>
<main>
<?php
//var_dump($_POST);
//
// if($id_session){
//                echo 'ID de session (récupéré via session_id()) : <br>'
//                .$id_session. '<br>';
//            }
//            echo '<br><br>';
//            if(isset($_COOKIE['PHPSESSID'])){
//                echo 'ID de session (récupéré via $_COOKIE) : <br>'
//                .$_COOKIE['PHPSESSID'];
//            }
//
//        ?>
    <?php
    if (isset($_SESSION['user'])) {
        $user = unserialize(base64_decode($_SESSION['user']));
        //var_dump($user->getGroups());
//        var_dump($user);
        ?>
    <h3>Bonjour <?= $user->getFirstname()?></h3>
    <h4>Vous etes connecté</h4>

    <p><a href="account.php">Voir mon profil</a></p>
    <p><a href="register.php?action=edit">Modifier mon profil</a></p>
    <p><a href="logout.php">Se déconnecter</a></p>
</main>
<?php
include 'footer.php';
?>

    <?php
    } else {

    ?>
    <form action="connexion.php" method="post">
    <h1>Login</h1>
        <label for="login_user" > Login </label>
        <input type="text" name="login_user" id="login_user"  placeholder="Login" autofocus>
        <label for="password_hash_user" > Password </label>
        <input type="password" name="password_hash_user" id="password_hash_user"  placeholder="Password">
        <input type="submit"  value="Se connecter">
    </form>
    <a href="register.php">Créer votre compte</a>
    &nbsp;/
    <button type="button" id="RecoverPassword">Mot de passe perdu?</button>

?>

</main>
<?php
include 'app/Views/templates/footer.php';
?>

<script src="../../public/assets/js/burger.js"></script>
<?php
}
?>
</body>
</html>