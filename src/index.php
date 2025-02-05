<?php
echo "<h1>Environnement Docker</h1>";
echo "<p>PHP version: " . phpversion() . "</p>";

// Test de connexion à la base de données avec débogage détaillé
echo "<h2>Informations de connexion :</h2>";
$dbName = getenv('DB_NAME');
$dbUser = getenv('DB_USER');
$dbPassword = getenv('DB_PASSWORD');

echo "<pre>";
echo "Variables de connexion :\n";
echo "DB_NAME = " . ($dbName ?: 'non défini') . "\n";
echo "DB_USER = " . ($dbUser ?: 'non défini') . "\n";
echo "DB_PASSWORD est " . ($dbPassword ? 'défini' : 'non défini') . "\n";
echo "</pre>";

try {
    echo "<p>Tentative de connexion à MariaDB...</p>";
    $dsn = "mysql:host=mariadb;dbname=$dbName";
    echo "<p>DSN : $dsn</p>";

    $connexion = new PDO($dsn, $dbUser, $dbPassword, array(
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ));
    echo "<p style='color: green'>Connexion à la base de données réussie !</p>";

    // Vérifions la connexion avec une requête simple
    $version = $connexion->query('SELECT VERSION()')->fetchColumn();
    echo "<p>Version de MariaDB : $version</p>";
} catch(PDOException $e) {
    echo "<p style='color: red'>Erreur de connexion détaillée : " . $e->getMessage() . "</p>";
    echo "<p>Code d'erreur : " . $e->getCode() . "</p>";
}

// Affichage des variables d'environnement système pour le débogage
echo "<h2>Toutes les variables d'environnement :</h2>";
echo "<pre>";
print_r(getenv());
echo "</pre>";

phpinfo();
?>