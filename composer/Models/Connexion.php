<?php

namespace Titus\Dolmen\Models;

class Connexion
{
    // Méthode statique pour récupérer les paramètres de configuration
    private static function getConfig(string $key, string $default = ''): string
    {
        // Utilisez $_ENV ou getenv() pour récupérer les variables d'environnement
        return $_ENV[$key] ?? getenv($key) ?? $default;
    }

    // Utilisation de la méthode statique pour les paramètres
    private static function getServerName(): string
    {
        return self::getConfig('DB_HOST', 'mariadb-1');
    }

    private static function getUsername(): string
    {
        return self::getConfig('DB_USER', 'root');
    }

    private static function getPassword(): string
    {
        return self::getConfig('DB_PASSWORD', 'p@ssw0rd');
    }

    private static function getDbName(): string
    {
        return self::getConfig('DB_NAME', 'dolmen');
    }

    private static ?Connexion $instance = null;
    private ?\PDO $conn = null;

    public static function getInstance(): Connexion
    {
        if (self::$instance === null) {
            try {
                self::$instance = new Connexion();
            } catch (\PDOException $e) {
                // Gestion plus robuste de l'erreur
                error_log("Erreur de connexion à la base de données : " . $e->getMessage());
                throw $e;
            }
        }
        return self::$instance;
    }

    protected function __construct()
    {
        try {
            // Configuration de la connexion PDO avec les méthodes statiques
            $this->conn = new \PDO(
                "mysql:host=" . self::getServerName() . ";dbname=" . self::getDbName(),
                self::getUsername(),
                self::getPassword(),
                [
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                    \PDO::ATTR_EMULATE_PREPARES => false
                ]
            );
        } catch (\PDOException $e) {
            // Journalisation détaillée de l'erreur
            error_log("Échec de la connexion à la base de données : " . $e->getMessage());
            throw $e;
        }
    }

    public function getConn(): \PDO
    {
        return $this->conn;
    }
}