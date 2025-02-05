<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../../../public/styles/normalize.css">
    <link rel="stylesheet" href="../../../public/styles/styles.css">
    <link rel="icon" type="image/x-icon" sizes="16x16" href="../../../ressources/images/Icones/chasseur_dolmen.svg">
    <title>Chasseur de Dolmen</title>
</head>
<body>
<header>

<?php
include 'app/Views/templates/navbar.php';
?>

</header>
<main>
    <div id="presentation">
        <video class="bg" autoplay muted loop>
            <source src="../../../ressources/video/DJI_0820%20-%20Trim_2.mp4" type="video/mp4">
            Votre navigateur ne supporte pas l'élément vidéo.
        </video>
        <h1>Chasseur de Dolmens</h1>
    </div>
    <div id="about">
        <img src="../../../ressources/images/Cromlechs%20de%20Mandale,%20Urrugne_1.jpg" alt="photo de l'artiste">
        <div class="content">
            <h2>Sebastien Joffre</h2>
            <p>Lorem projet_dolmen_data ipsum dolor sit amet, consectetur adipisicing elit. Accusantium alias aliquid fugiat laboriosam
                magni minima quidem repellat tempora. Aperiam at aut facilis, itaque recusandae reiciendis similique
                voluptas. Aliquid amet animi dicta distinctio, ducimus error facilis fuga id ipsam maiores mollitia
                nobis odio, odit quam quibusdam soluta sunt suscipit voluptas voluptatum.</p>
        </div>
    </div>
    <div id="portfolio">
        <h2>Portfolio</h2>
        <img src="../../../ressources/images/Tumulus%20du%20Dolmen%20A5.jpg" alt="Portfolio">
    </div>
    <div id="tirage">
        <h2>Tirage</h2>
        <img src="../../../ressources/images/portfolio.jpg" alt="Tirage">
    </div>
    <div id="event">
        <h2>Evènement</h2>
        <img src="../../../ressources/images/tusson.jpg" alt="Tirage">
    </div>
    <div id="contact">
        <h2>Contact</h2>
        <a href="../contact/contact.php">
            <img src="../../../ressources/images/contact.jpg" alt="Tirage">
        </a>

    </div>
</main>
<?php
include 'app/Views/templates/footer.php';
?>

<script src="../../../public/js/burger.js"></script>
<script src="../../../public/js/video.js"></script>
</body>
</html>