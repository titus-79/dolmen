<?php
namespace Titus\Dolmen\Models;

use PDO;
use PDOException;
use Dotenv\Dotenv;

class Database {
    private static $instance = null;
    private $connection;

    private function __construct() {
        // Chemin vers le fichier .env
        $dotenvPath = __DIR__ . '/../../';

        // Vérification de l'existence du fichier .env
        if (file_exists($dotenvPath . '.env')) {
            $dotenv = Dotenv::createImmutable($dotenvPath);
            $dotenv->load();
        }
        try {
            // Récupération des variables d'environnement
            $host = $_ENV['DB_HOST'] ?? 'mariadb';
            $dbname = $_ENV['DB_NAME'] ?? 'dolmen';
            $username = $_ENV['DB_USER'] ?? 'user';
            $password = $_ENV['DB_PASSWORD'] ?? 'pass';

            $this->connection = new PDO(
                "mysql:host={$host};dbname={$dbname};charset=utf8mb4",
                $username,
                $password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]
            );
        } catch (PDOException $e) {
            error_log("Erreur de connexion : " . $e->getMessage());
            error_log("Détails - Hôte: $host, Base: $dbname, User: $username");
            throw $e;
        }
    }

    // Méthode singleton pour récupérer l'instance de connexion
    public static function getInstance(): self {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    // Méthode pour exécuter des requêtes
    public function query(string $sql, array $params = []) {
        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Erreur de requête SQL : " . $e->getMessage());
            throw $e;
        }
    }

    // Empêche le clonage de l'instance
    private function __clone() {}

    // Fermeture de la connexion
    public function __destruct() {
        $this->connection = null;
    }
}