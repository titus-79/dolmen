<!--<!doctype html>-->
<!--<html lang="fr">-->
<!--<head>-->
<!--    <meta charset="UTF-8">-->
<!--    <meta name="viewport"-->
<!--          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">-->
<!--    <meta http-equiv="X-UA-Compatible" content="ie=edge">-->
<!--    <link rel="stylesheet" href="../../../../public/assets/styles/normalize.css">-->
<!--    <link rel="stylesheet" href="../../../../public/assets/styles/styles.css">-->
<!--    <link rel="icon" type="image/x-icon" sizes="16x16" href="../../../../public/assets/images/Icones/chasseur_dolmen.svg">-->
<!--    <script src="https://cdn.tiny.cloud/1/jehcx49a28h8yycvb34znmvoc94y0u3em6jjmfughykmvnck/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>-->
<!---->
<!--    <script>-->
<!--        tinymce.init({-->
<!--            selector: 'textarea',-->
<!--            plugins: [-->
<!--                // Core editing features-->
<!--                'anchor', 'autolink', 'charmap', 'codesample', 'emoticons', 'image', 'link', 'lists', 'media', 'searchreplace', 'table', 'visualblocks', 'wordcount',-->
<!--                // Your account includes a free trial of TinyMCE premium features-->
<!--                // Try the most popular premium features until Nov 19, 2024:-->
<!--                'checklist', 'mediaembed', 'casechange', 'export', 'formatpainter', 'pageembed', 'a11ychecker', 'tinymcespellchecker', 'permanentpen', 'powerpaste', 'advtable', 'advcode', 'editimage', 'advtemplate', 'ai', 'mentions', 'tinycomments', 'tableofcontents', 'footnotes', 'mergetags', 'autocorrect', 'typography', 'inlinecss', 'markdown',-->
<!--                // Early access to document converters-->
<!--                'importword', 'exportword', 'exportpdf'-->
<!--            ],-->
<!--            toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | addcomment showcomments | spellcheckdialog a11ycheck typography | align lineheight | checklist numlist bullist indent outdent | emoticons charmap | removeformat',-->
<!--            tinycomments_mode: 'embedded',-->
<!--            tinycomments_author: 'Author name',-->
<!--            mergetags_list: [-->
<!--                { value: 'First.Name', title: 'First Name' },-->
<!--                { value: 'Email', title: 'Email' },-->
<!--            ],-->
<!--            ai_request: (request, respondWith) => respondWith.string(() => Promise.reject('See docs to implement AI Assistant')),-->
<!--            exportpdf_converter_options: { 'format': 'Letter', 'margin_top': '1in', 'margin_right': '1in', 'margin_bottom': '1in', 'margin_left': '1in' },-->
<!--            exportword_converter_options: { 'document': { 'size': 'Letter' } },-->
<!--            importword_converter_options: { 'formatting': { 'styles': 'inline', 'resets': 'inline',	'defaults': 'inline', } },-->
<!--        });-->
<!--    </script>-->
<!---->
<!--    <title>Administrateur</title>-->
<!--</head>-->
<!--<body>-->
<!--<header>-->
<?php
//include 'navbar.php';
//?>
<!---->
<!--</header>-->
<!--<main>-->
<!--<h1>Interface Administrateur</h1>-->
<!--    <form method="post">-->
<!--        <fieldset>-->
<!--            <label for="rubrique">Quelle pages veut tu modifié ?</label><br>-->
<!--            <select name="rubrique" id="rubrique" required>-->
<!--                <option value="" disabled selected hidden>Choisissez la page à modifier</option>-->
<!--                <option value="about">À propos</option>-->
<!--                <option value="tirage">Tirage</option>-->
<!--                <option value="event">Evènement</option>-->
<!--            </select><br>-->
<!--            <label for="action">Quelle action veut tu faire?</label><br>-->
<!--            <select name="action" id="action" required>-->
<!--                <option value="" disabled selected hidden>Choisissez l'action</option>-->
<!--                <option value="ajout">ajouter un article</option>-->
<!--                <option value="mod">Modifier un article</option>-->
<!--                <option value="supp">Supprimer un arcticle</option>-->
<!--            </select><br>-->
<!--            <label for="titre">Quel est le titre ?</label><br>-->
<!--            <input type="text" id="titre" name="titre"><br>-->
<!--            <label for="mytextarea">Quel est le texte ?</label><br>-->
<!--            <textarea id="mytextarea" name="textarea"></textarea><br>-->
<!--            <label for="file">Choississez une image :</label><br>-->
<!--            <input type="file" id="file" accept="image/png, image/jpeg"><br>-->
<!--            <button type="submit" >Envoyer</button>-->
<!--        </fieldset>-->
<?php
//var_dump($_POST);
//?>
<!--</main>-->
<?php
//include 'footer.php';
//?>
<!---->
<!--<script src="../../../../public/assets/js/burger.js"></script>-->
<!--<script src="../../../../public/assets/js/DBtest.js"></script>-->
<!--</body>-->
<!--</html>-->

<div class="admin-dashboard">
    <div class="admin-header">
        <h1>Tableau de bord administrateur</h1>
        <p>Bienvenue sur l'interface d'administration de Chasseur de Dolmens</p>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <?= htmlspecialchars($_SESSION['success']) ?>
            <?php unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <!-- Statistiques rapides -->
    <div class="stats-grid">
        <div class="stat-card">
            <h3>Utilisateurs</h3>
            <div class="stat-number"><?= $userCount ?></div>
            <a href="/admin/users" class="stat-link">Gérer les utilisateurs</a>
        </div>
        <div class="stat-card">
            <h3>Événements</h3>
            <div class="stat-number"><?= $eventCount ?></div>
            <a href="/admin/events" class="stat-link">Gérer les événements</a>
        </div>
        <div class="stat-card">
            <h3>Portfolio</h3>
            <div class="stat-number"><?= $portfolioCount ?></div>
            <a href="/admin/portfolio" class="stat-link">Gérer le portfolio</a>
        </div>
        <div class="stat-card">
            <h3>Tirages</h3>
            <div class="stat-number"><?= $printsCount ?></div>
            <a href="/admin/prints" class="stat-link">Gérer les tirages</a>
        </div>
    </div>

    <!-- Menu d'administration -->
    <div class="admin-menu">
        <h2>Gestion du site</h2>
        <div class="menu-grid">
            <div class="menu-item">
                <h3>Contenu</h3>
                <ul>
                    <li><a href="/admin/about">Gérer la page À propos</a></li>
                    <li><a href="/admin/portfolio">Gérer le portfolio</a></li>
                    <li><a href="/admin/events">Gérer les événements</a></li>
                    <li><a href="/admin/home">Gérer la page d'accueil</a></li>
                </ul>
            </div>
            <div class="menu-item">
                <h3>Boutique</h3>
                <ul>
                    <li><a href="/admin/prints">Gérer les tirages</a></li>
                    <li><a href="/admin/orders">Gérer les commandes</a></li>
                    <li><a href="/admin/prints/categories">Gérer les catégories</a></li>
                </ul>
            </div>
            <div class="menu-item">
                <h3>Utilisateurs</h3>
                <ul>
                    <li><a href="/admin/users">Gérer les utilisateurs</a></li>
                    <li><a href="/admin/users/roles">Gérer les rôles</a></li>
                </ul>
            </div>
            <div class="menu-item">
                <h3>Configuration</h3>
                <ul>
                    <li><a href="/admin/settings">Paramètres du site</a></li>
                    <li><a href="/admin/backup">Sauvegardes</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>

<style>
    .admin-dashboard {
        padding: 2rem;
        max-width: 1200px;
        margin: 0 auto;
    }

    .admin-header {
        margin-bottom: 2rem;
        text-align: center;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: white;
        padding: 1.5rem;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        text-align: center;
    }

    .stat-number {
        font-size: 2.5rem;
        font-weight: bold;
        color: #007bff;
        margin: 1rem 0;
    }

    .stat-link {
        color: #666;
        text-decoration: none;
    }

    .stat-link:hover {
        color: #007bff;
    }

    .admin-menu {
        background: white;
        padding: 2rem;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .menu-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 2rem;
        margin-top: 1rem;
    }

    .menu-item h3 {
        color: #333;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #eee;
    }

    .menu-item ul {
        list-style: none;
        padding: 0;
    }

    .menu-item ul li {
        margin-bottom: 0.5rem;
    }

    .menu-item ul li a {
        color: #666;
        text-decoration: none;
        display: block;
        padding: 0.5rem;
        border-radius: 4px;
    }

    .menu-item ul li a:hover {
        background: #f8f9fa;
        color: #007bff;
    }

    .alert {
        padding: 1rem;
        margin-bottom: 1rem;
        border-radius: 4px;
    }

    .alert-success {
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }
</style>