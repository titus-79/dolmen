<?php
// network_test.php
namespace Titus\Dolmen\Tests;

require_once __DIR__ . '/../vendor/autoload.php';

echo "Test de connexion réseau...\n\n";

// Test ping mariadb
echo "Test ping mariadb:\n";
system("ping -c 1 mariadb");
echo "\n";

// Test telnet sur le port MySQL
echo "Test de connection au port MySQL:\n";
$connection = @fsockopen("mariadb", 3306);
if ($connection) {
    echo "Connexion au port 3306 réussie!\n";
    fclose($connection);
} else {
    echo "Échec de la connexion au port 3306\n";
}

// Test de connexion PDO direct
echo "\nTest de connexion PDO:\n";
try {
    $dsn = "mysql:host=mariadb;dbname=dolmen;charset=utf8mb4";
    $username = 'root';
    $password = 'p@ssw0rd';

    echo "Tentative de connexion avec:\n";
    echo "DSN: $dsn\n";
    echo "Username: $username\n";

    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    echo "Connexion PDO réussie!\n";

    $result = $pdo->query("SHOW DATABASES")->fetchAll(PDO::FETCH_COLUMN);
    echo "\nBases de données disponibles:\n";
    print_r($result);

} catch (PDOException $e) {
    echo "Erreur PDO: " . $e->getMessage() . "\n";
}