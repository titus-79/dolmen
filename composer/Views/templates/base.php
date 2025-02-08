<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'Chasseur de Dolmens') ?></title>
    <link rel="stylesheet" href="/assets/styles/normalize.css">
    <link rel="stylesheet" href="/assets/styles/styles.css">
    <link rel="icon" type="image/x-icon" sizes="16x16" href="/assets/images/Icones/chasseur_dolmen.svg">

</head>
<body>
<header>
    <?php require __DIR__ . '/partials/navbar.php'; ?>
</header>

<main>
    <?php
    // Vérifions que $content est défini avant de l'utiliser
    if (isset($content)):
        echo $content;
    else:
        echo "Aucun contenu n'a été défini.";
    endif;
    ?></main>

<footer>
    <?php require __DIR__ . '/partials/footer.php'; ?>
</footer>

<script src="/assets/js/burger.js"></script>
</body>
</html>