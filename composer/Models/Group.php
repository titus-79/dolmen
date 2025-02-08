<?php

namespace Titus\Dolmen\Models;

use PDO;
use PDOException;

/**
 * Classe Group - Gère les groupes d'utilisateurs
 *
 * Cette classe permet de gérer les groupes d'utilisateurs dans l'application,
 * avec des méthodes pour créer, lire, mettre à jour et supprimer des groupes.
 */
class Group
{
    // Définition des constantes de groupe
    public const GROUP_ADMIN = 'Admin';
    public const GROUP_MEMBER = 'Member';

    // Liste des groupes valides
    private const VALID_GROUPS = [
        self::GROUP_ADMIN,
        self::GROUP_MEMBER
    ];

    private ?int $id = null;
    private string $name;
    private array $users = [];

    /**
     * Trouve un groupe par son nom
     *
     * @param string $name Le nom du groupe à rechercher
     * @return Group|null Le groupe trouvé ou null si non trouvé
     * @throws PDOException En cas d'erreur de base de données
     */
    public static function findByName(string $name): ?Group
    {
        try {
            $conn = Connexion::getInstance()->getConn();
            $stmt = $conn->prepare("SELECT * FROM `groups` WHERE name = ?");
            $stmt->execute([$name]);

            if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                return self::hydrate($row);
            }

            return null;
        } catch (PDOException $e) {
            error_log("Erreur lors de la recherche du groupe : " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Récupère tous les groupes
     *
     * @return array Liste des groupes
     * @throws PDOException En cas d'erreur de base de données
     */
    public static function getAllGroups(): array
    {
        try {
            $conn = Connexion::getInstance()->getConn();
            $stmt = $conn->prepare("SELECT * FROM `groups` ORDER BY name");
            $stmt->execute();

            $groups = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $groups[] = self::hydrate($row);
            }

            return $groups;
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération des groupes : " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Sauvegarde le groupe dans la base de données
     *
     * @return bool True si la sauvegarde a réussi, False sinon
     * @throws PDOException En cas d'erreur de base de données
     */
    public function save(): bool
    {
        try {
            $conn = Connexion::getInstance()->getConn();

            // Si le groupe existe déjà, on le met à jour
            if ($this->id !== null) {
                $stmt = $conn->prepare("UPDATE `groups` SET name = ? WHERE id_group = ?");
                $result = $stmt->execute([$this->name, $this->id]);
            } else {
                // Sinon, on crée un nouveau groupe
                $stmt = $conn->prepare("INSERT INTO `groups` (name) VALUES (?)");
                $result = $stmt->execute([$this->name]);
                if ($result) {
                    $this->id = (int)$conn->lastInsertId();
                }
            }

            return $result;
        } catch (PDOException $e) {
            error_log("Erreur lors de la sauvegarde du groupe : " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Supprime le groupe
     *
     * @return bool True si la suppression a réussi, False sinon
     * @throws PDOException En cas d'erreur de base de données
     */
    public function delete(): bool
    {
        if ($this->id === null) {
            return false;
        }

        try {
            $conn = Connexion::getInstance()->getConn();

            // Suppression des relations users_groups d'abord
            $stmt = $conn->prepare("DELETE FROM users_groups WHERE id_group = ?");
            $stmt->execute([$this->id]);

            // Suppression du groupe ensuite
            $stmt = $conn->prepare("DELETE FROM `groups` WHERE id_group = ?");
            return $stmt->execute([$this->id]);
        } catch (PDOException $e) {
            error_log("Erreur lors de la suppression du groupe : " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Hydrate un groupe à partir d'un tableau de données
     *
     * @param array $properties Les propriétés du groupe
     * @return Group L'instance hydratée
     */
    public static function hydrate(array $properties): Group
    {
        $group = new self();

        if (isset($properties['id_group'])) {
            $group->setId((int)$properties['id_group']);
        }

        if (isset($properties['name'])) {
            $group->setName($properties['name']);
        }

        return $group;
    }

    /**
     * Vérifie si un nom de groupe est valide
     *
     * @param string $name Le nom à vérifier
     * @return bool True si le nom est valide, False sinon
     */
    public static function isValidGroupName(string $name): bool
    {
        return in_array($name, self::VALID_GROUPS, true);
    }

    // Getters et Setters avec typage strict
    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        if (!self::isValidGroupName($name)) {
            throw new \InvalidArgumentException("Nom de groupe invalide : {$name}");
        }
        $this->name = $name;
        return $this;
    }

    /**
     * Obtient les utilisateurs associés à ce groupe
     *
     * @return array Liste des utilisateurs du groupe
     * @throws PDOException En cas d'erreur de base de données
     */
    public function getUsers(): array
    {
        if (empty($this->users) && $this->id !== null) {
            try {
                $conn = Connexion::getInstance()->getConn();
                $stmt = $conn->prepare("
                    SELECT u.* 
                    FROM users u
                    JOIN users_groups ug ON u.id_user = ug.id_user
                    WHERE ug.id_group = ?
                ");
                $stmt->execute([$this->id]);

                while ($userData = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $this->users[] = User::hydrate($userData);
                }
            } catch (PDOException $e) {
                error_log("Erreur lors de la récupération des utilisateurs du groupe : " . $e->getMessage());
                throw $e;
            }
        }

        return $this->users;
    }
}