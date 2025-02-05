<?php
session_start();

use Titus\Dolmen\Models\User;

require_once 'app/Models/User.php';

$isModification = isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_SESSION['user']);
$currentUser = null;

if ($isModification) {
    $currentUser = unserialize(base64_decode($_SESSION['user']));
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['name_user']) && !empty($_POST['login_user']) &&
        ($isModification || !empty($_POST['password_hash_user'])) &&
        !empty($_POST['firstname_user']) && !empty($_POST['user_email'])) {

        if ($isModification) {
            $user = $currentUser;
            $user->setName($_POST['name_user']);
            $user->setEmail($_POST['user_email']);
            $user->setFirstname($_POST['firstname_user']);
            $user->setTel($_POST['tel_user']);

            // Ne modifier le mot de passe que s'il est fourni
            if (!empty($_POST['password_hash_user'])) {
                $hash = password_hash($_POST['password_hash_user'], PASSWORD_BCRYPT);
                $user->setPasswordHash($hash);
            }
        } else {
            $user = new User();
            $user->setName($_POST['name_user']);
            $user->setLogin($_POST['login_user']);
            $hash = password_hash($_POST['password_hash_user'], PASSWORD_BCRYPT);
            $user->setPasswordHash($hash);
            $user->setEmail($_POST['user_email']);
            $user->setFirstname($_POST['firstname_user']);
            $user->setTel($_POST['tel_user']);
        }

        if ($user->save()) {
            if ($isModification) {
                $_SESSION['user'] = base64_encode(serialize($user));
                header('Location: connexion.php');
                exit;
            } else {
                header('Location: connexion.php');
                exit;
            }
        } else {
            echo "<p class='error'>Une erreur est survenue lors de l'enregistrement</p>";
        }
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
    <title>enregistrement</title>
</head>
<body>
<header>
    <?php include 'app/Views/templates/navbar.php'; ?>
</header>
<main>
    <h1><?php echo $isModification ? "Modifier" : "Ajouter"; ?> un compte</h1>
    <form action="register.php<?php echo $isModification ? '?action=edit' : ''; ?>" method="post">
        <div>
            <label for="name_user">Nom</label>
            <input type="text" name="name_user" id="name_user"
                   value="<?php echo $isModification ? $currentUser->getName() : ''; ?>"
                   placeholder="Nom" autofocus>
        </div>
        <div>
            <label for="login_user">Login</label>
            <input type="text" name="login_user" id="login_user"
                   value="<?php echo $isModification ? $currentUser->getLogin() : ''; ?>"
                   placeholder="Login" <?php echo $isModification ? 'readonly' : ''; ?>>
        </div>
        <div>
            <label for="password_hash_user">Mot de passe<?php echo $isModification ? ' (laisser vide pour ne pas modifier)' : ''; ?></label>
            <input type="password" name="password_hash_user" id="password_hash_user" placeholder="Password">
        </div>
        <div>
            <label for="firstname_user">Prénom</label>
            <input type="text" name="firstname_user" id="firstname_user"
                   value="<?php echo $isModification ? $currentUser->getFirstname() : ''; ?>"
                   placeholder="Prénom">
        </div>
        <div>
            <label for="user_email">Email</label>
            <input type="email" name="user_email" id="user_email"
                   value="<?php echo $isModification ? $currentUser->getEmail() : ''; ?>"
                   placeholder="Mail">
        </div>
        <div>
            <label for="tel_user">Tel</label>
            <input type="tel" name="tel_user" id="tel_user"
                   value="<?php echo $isModification ? $currentUser->getTel() : ''; ?>"
                   placeholder="Telephone">
        </div>

<!--        todo-->adresse

        <input type="submit" value="<?php echo $isModification ? 'Modifier' : 'Enregistrer'; ?>">
    </form>
    <p><a href="connexion.php">Retour</a></p>
    <?php

    ?>
</main>
<?php include 'app/Views/templates/footer.php'; ?>

<script src="../../public/assets/js/burger.js"></script>
</body>
</html>