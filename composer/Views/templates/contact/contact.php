<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../../../../public/assets/styles/normalize.css">
    <link rel="stylesheet" href="../../../../public/assets/styles/styles.css">
    <link rel="stylesheet" href="../../../../public/assets/styles/form.css">
    <link rel="icon" type="image/x-icon" sizes="16x16" href="../../../../public/assets/images/Icones/chasseur_dolmen.svg">
    <title>Contact</title>
</head>
<body>
<header>
<?php
include 'app/Views/templates/navbar.php';
?>

</header>
<main>
    <h1>Contactez-moi</h1>
    <form action="/ma-page-de-traitement" method="post">
        <ul>
            <li>
                <label for="name">Nom&nbsp;:</label>
                <input type="text" id="name" name="user_name" />
            </li>
            <li>
                <label for="mail">E-mail&nbsp;:</label>
                <input type="email" id="mail" name="user_mail" />
            </li>
            <li>
                <label for="msg">Message&nbsp;:</label>
                <textarea id="msg" name="user_message"></textarea>
            </li>
        </ul>
        <div class="button">
            <button type="submit">Envoyer le message</button>
        </div>
    </form>

</main>
<?php
include 'app/Views/templates/footer.php';
?>

<script src="../../../../public/assets/js/burger.js"></script>
</body>
</html>