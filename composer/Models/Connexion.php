<?php

namespace Titus\Dolmen\Models;

class Connexion
{
    const string SERVER_NAME = "docker-lamp-mariadb-1";
    const string USERNAME = "root";
    const string PASSWORD = "p@ssw0rd";
    const string DB_NAME = 'dolmen';
    private static Connexion|null $instance = null;
    private \PDO|null $conn = null;

    public static function getInstance():Connexion
    {
        if (self::$instance === null) {
            try {
                self::$instance = new Connexion();
            } catch (\PDOException $e) {
                echo $e->getMessage();
            }
        }
        return self::$instance;
    }

    protected function __construct()
    {
        $this->conn = new \PDO(
            "mysql:host=" . self::SERVER_NAME . ";dbname=" . self::DB_NAME,
            self::USERNAME,
            self::PASSWORD
        );
        $this->conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $this->conn->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
    }

    public function getConn():\PDO
    {
        return $this->conn;
    }

}