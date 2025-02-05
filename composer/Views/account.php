<?php
session_start();
require_once 'app/Models/User.php';

use Titus\Dolmen\Models\User;

/** @var User $currentUser */
$currentUser = unserialize(base64_decode($_SESSION['user']));
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
    <h1>Votre compte</h1>
    <table>
        <tbody>
        <tr>
            <th>Nom</th>
            <td><?=$currentUser->getName()?></td>
        </tr>
        <tr>
            <th>Login</th>
            <td><?=$currentUser->getLogin()?></td>
        </tr>
        <tr>
            <th>Prénom</th>
            <td><?=$currentUser->getFirstname()?></td>
        </tr>
        <tr>
            <th>Email</th>
            <td><?=$currentUser->getEmail()?></td>
        </tr>
        <tr>
            <th>Telephone</th>
            <td><?=$currentUser->getTel()?></td>
        </tr>
        </tbody>
    </table>
    <p><a href="register.php?action=edit">Modifier mon profil</a></p>
    <p><a href="connexion.php">Retour</a></p>

</main>
<?php include 'app/Views/templates/footer.php'; ?>

<script src="../../public/assets/js/burger.js"></script>
</body>
</html>