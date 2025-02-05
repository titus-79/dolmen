<?php

namespace Titus\Dolmen\Models;

use Model\Connexion;
use Models\PDOExeption;
use PDO;
use PDOException;

require_once "app/Models/Connexion.php";
class Group
{
    const GROUP_ADMIN = 'Admin';
    const GROUP_MEMBER = 'Member';
    private int $id;
    private string $name;

    public static function findByName(string $name): ?Group
    {
        $group = null;

        try {
            $conn = Connexion::getInstance()->getConn();
            $stt = $conn->prepare("SELECT * FROM groups WHERE name = ?");
            $stt->bindParam(1, $name);
            $stt->execute();
            $group = self::hydrate($stt->fetch(PDO::FETCH_ASSOC));
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
        return $group;
    }

    public function getName(): string
    {
        return $this->name;
    }
    public function setName(string $name)
    {
        $this->name = $name;
    }
    public function getId(): int
    {
        return $this->id;
    }
    public function setId(int $id): Group
    {
        $this->id = $id;
        return $this;
    }

    public static function hydrate(array $properties): Group
    {
        $group = new Group();
        $group->setId($properties['id_group']);
        $group->setName($properties['name']);
        return $group;
    }

    public function save()
    {
        try {
            $conn = Connexion::getInstance()->getConn();
            $stt = $conn->prepare("INSERT INTO `groups` (`name`) VALUES (?)");
            $stt->bindParam(1, $this->name);
            $stt->execute();
            $this->id = $conn->lastInsertId();
        } catch (PDOExeption $e) {
            echo $e->getMessage();
        }
    }




}