<?php
// views/templates/portfolio/index.php
?>
<!DOCTYPE html>
<html>
<head>
    <!-- Dépendances React -->
    <script src="https://unpkg.com/react@17/umd/react.production.min.js"></script>
    <script src="https://unpkg.com/react-dom@17/umd/react-dom.production.min.js"></script>
    <script src="https://unpkg.com/babel-standalone@6/babel.min.js"></script>

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <!-- Vos styles -->
    <link rel="stylesheet" href="/assets/styles/portfolio.css">
</head>
<body>
<div class="portfolio-container">
    <header class="page-header">
        <h1 class="page-title">Collection de Dolmens</h1>
        <p class="header-description">
            Découvrez une collection unique de dolmens photographiés à travers la France et l'Europe.
        </p>
    </header>

    <!-- Conteneur pour le composant React -->
    <div id="portfolio-root" class="portfolio-content">
        <!-- Les données seront injectées ici -->
        <script>
            // Injection des données PHP dans une variable globale
            window.portfolioData = <?= json_encode($albums, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;
        </script>
    </div>
</div>

<!-- Importation de vos composants React -->
<script type="module" src="/assets/js/portfolio-init.js"></script>
</body>
</html>