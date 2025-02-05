<?php
// Le fichier composer/index.php servira de test initial
// Il nous donnera des informations utiles sur la configuration

// Affichage des informations de base
echo "<h1>Test de l'environnement Docker</h1>";

// Test de la configuration PHP
echo "<h2>Version PHP :</h2>";
echo PHP_VERSION;

// Test de la connexion à MariaDB
echo "<h2>Test de connexion à la base de données :</h2>";
try {
    $pdo = new PDO(
        "mysql:host=mariadb;dbname={$_ENV['DB_NAME']}",
        $_ENV['DB_USER'],
        $_ENV['DB_PASSWORD']
    );
    echo "<p style='color: green'>Connexion à la base de données réussie !</p>";

    // Affichage de la version de MariaDB
    $version = $pdo->query('SELECT VERSION()')->fetchColumn();
    echo "<p>Version de MariaDB : $version</p>";
} catch(PDOException $e) {
    echo "<p style='color: red'>Erreur de connexion : " . $e->getMessage() . "</p>";
}

// Affichage des extensions PHP installées
echo "<h2>Extensions PHP installées :</h2>";
echo "<pre>";
print_r(get_loaded_extensions());
echo "</pre>";