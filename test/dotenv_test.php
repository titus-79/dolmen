<?php
// dotenv_test.php
namespace Titus\Dolmen\Tests;

require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;

echo "Test de chargement des variables d'environnement\n\n";

try {
    // Chargement explicite du fichier .env
    $dotenv = Dotenv::createImmutable(__DIR__);
    $dotenv->load();

    echo "Variables d'environnement après chargement de dotenv:\n";
    echo "DB_HOST: " . ($_ENV['DB_HOST'] ?? 'non définie') . "\n";
    echo "DB_NAME: " . ($_ENV['DB_NAME'] ?? 'non définie') . "\n";
    echo "DB_USER: " . ($_ENV['DB_USER'] ?? 'non définie') . "\n";
    echo "DB_PASSWORD: " . ($_ENV['DB_PASSWORD'] ?? 'non définie') . "\n";

    // Test de connexion avec les variables chargées
    $dsn = "mysql:host={$_ENV['DB_HOST']};dbname={$_ENV['DB_NAME']};charset=utf8mb4";
    $pdo = new PDO(
        $dsn,
        $_ENV['DB_USER'],
        $_ENV['DB_PASSWORD'],
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    // Vérifions la structure de la table users
    echo "\nStructure de la table users:\n";
    $stmt = $pdo->query("DESCRIBE users");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    print_r($columns);

    // Vérifions les utilisateurs existants (seulement les colonnes non sensibles)
    echo "\nUtilisateurs existants:\n";
    $stmt = $pdo->query("SELECT id_user, name_user, login_user, firstname_user, email_user FROM users");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    print_r($users);

} catch (Exception $e) {
    echo "Erreur: " . $e->getMessage() . "\n";
}