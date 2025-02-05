<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../../../../public/assets/styles/normalize.css">
    <link rel="stylesheet" href="../../../../public/assets/styles/styles.css">
    <link rel="icon" type="image/x-icon" sizes="16x16" href="../../../../public/assets/images/Icones/chasseur_dolmen.svg">
    <script src="https://cdn.tiny.cloud/1/jehcx49a28h8yycvb34znmvoc94y0u3em6jjmfughykmvnck/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>

    <script>
        tinymce.init({
            selector: 'textarea',
            plugins: [
                // Core editing features
                'anchor', 'autolink', 'charmap', 'codesample', 'emoticons', 'image', 'link', 'lists', 'media', 'searchreplace', 'table', 'visualblocks', 'wordcount',
                // Your account includes a free trial of TinyMCE premium features
                // Try the most popular premium features until Nov 19, 2024:
                'checklist', 'mediaembed', 'casechange', 'export', 'formatpainter', 'pageembed', 'a11ychecker', 'tinymcespellchecker', 'permanentpen', 'powerpaste', 'advtable', 'advcode', 'editimage', 'advtemplate', 'ai', 'mentions', 'tinycomments', 'tableofcontents', 'footnotes', 'mergetags', 'autocorrect', 'typography', 'inlinecss', 'markdown',
                // Early access to document converters
                'importword', 'exportword', 'exportpdf'
            ],
            toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | addcomment showcomments | spellcheckdialog a11ycheck typography | align lineheight | checklist numlist bullist indent outdent | emoticons charmap | removeformat',
            tinycomments_mode: 'embedded',
            tinycomments_author: 'Author name',
            mergetags_list: [
                { value: 'First.Name', title: 'First Name' },
                { value: 'Email', title: 'Email' },
            ],
            ai_request: (request, respondWith) => respondWith.string(() => Promise.reject('See docs to implement AI Assistant')),
            exportpdf_converter_options: { 'format': 'Letter', 'margin_top': '1in', 'margin_right': '1in', 'margin_bottom': '1in', 'margin_left': '1in' },
            exportword_converter_options: { 'document': { 'size': 'Letter' } },
            importword_converter_options: { 'formatting': { 'styles': 'inline', 'resets': 'inline',	'defaults': 'inline', } },
        });
    </script>

    <title>Administrateur</title>
</head>
<body>
<header>
<?php
include 'navbar.php';
?>

</header>
<main>
<h1>Interface Administrateur</h1>
    <form method="post">
        <fieldset>
            <label for="rubrique">Quelle pages veut tu modifié ?</label><br>
            <select name="rubrique" id="rubrique" required>
                <option value="" disabled selected hidden>Choisissez la page à modifier</option>
                <option value="about">À propos</option>
                <option value="tirage">Tirage</option>
                <option value="event">Evènement</option>
            </select><br>
            <label for="action">Quelle action veut tu faire?</label><br>
            <select name="action" id="action" required>
                <option value="" disabled selected hidden>Choisissez l'action</option>
                <option value="ajout">ajouter un article</option>
                <option value="mod">Modifier un article</option>
                <option value="supp">Supprimer un arcticle</option>
            </select><br>
            <label for="titre">Quel est le titre ?</label><br>
            <input type="text" id="titre" name="titre"><br>
            <label for="mytextarea">Quel est le texte ?</label><br>
            <textarea id="mytextarea" name="textarea"></textarea><br>
            <label for="file">Choississez une image :</label><br>
            <input type="file" id="file" accept="image/png, image/jpeg"><br>
            <button type="submit" >Envoyer</button>
        </fieldset>
<?php
var_dump($_POST);
?>
</main>
<?php
include 'footer.php';
?>

<script src="../../../../public/assets/js/burger.js"></script>
<script src="../../../../public/assets/js/DBtest.js"></script>
</body>
</html>