<?php

namespace Models;

use PDOException;

class UserGroup
{
    private int $idGroup;
    private int $idUser;

    public function __construct(int $idGroup, int $idUser)
    {
        $this->idGroup = $idGroup;
        $this->idUser = $idUser;
    }

    public function getIdGroup(): int
    {
        return $this->idGroup;
    }

    public function getIdUser(): int
    {
        return $this->idUser;
    }

    public function setIdGroup(int $idGroup): UserGroup
    {
        $this->idGroup = $idGroup;
        return $this;
    }

    public function setIdUser(int $idUser): UserGroup
    {
        $this->idUser = $idUser;
        return $this;

    }

    public function save(): bool
    {
        try {
            $conn =Connexion::getInstance()->getConn();
            $stt = $conn->prepare("INSERT INTO users_groups (id_user, id_group) VALUES (:idGroup, :idUser)");
            $stt->bindParam(":idGroup", $this->idGroup);
            $stt->bindParam(":idUser", $this->idUser);
            $stt->execute();

        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    return true;
    }

}