<?php

namespace Titus\Dolmen\Models;

use DateTime;
use PDOException;
use Titus\Dolmen\Models\Connexion;
use Titus\Dolmen\Models\UserGroup;
use Titus\Dolmen\Models\Group;


class User
{
    private ?int $id = null;
    private string $lastName;
    private string $firstName;
    private string $username;
    private string $passwordHash;
    private string $email;
    private ?string $phone = null;
    private ?string $ipAddress = null;
    private ?\DateTime $lastLogin = null;
    private \DateTime $createdAt;
    private ?\DateTime $updatedAt = null;
    private ?int $groupId = null;
    private ?string $groupName = null;

    // Getters and setters with new names
    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;
        return $this;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;
        return $this;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;
        return $this;
    }

    public function getPasswordHash(): string
    {
        return $this->passwordHash;
    }

    public function setPasswordHash(string $passwordHash): self
    {
        $this->passwordHash = $passwordHash;
        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;
        return $this;
    }

    public function getIpAddress(): ?string
    {
        return $this->ipAddress;
    }

    public function setIpAddress(?string $ipAddress): self
    {
        $this->ipAddress = $ipAddress;
        return $this;
    }

    public function getLastLogin(): ?\DateTime
    {
        return $this->lastLogin;
    }

    public function setLastLogin(?\DateTime $lastLogin): self
    {
        $this->lastLogin = $lastLogin;
        return $this;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTime $updatedAt): self
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    public function getGroupId(): ?int
    {
        return $this->groupId;
    }

    public function setGroupId(?int $groupId): self
    {
        $this->groupId = $groupId;
        return $this;
    }

    public function getGroupName(): ?string
    {
        return $this->groupName;
    }

    public function setGroupName(?string $groupName): self
    {
        $this->groupName = $groupName;
        return $this;
    }

    /**
     * @throws \DateMalformedStringException
     */
    public static function hydrate(array $properties): User
    {
        $user = new User;

        // Propriétés obligatoires
        $user->setId($properties['id'] ?? null)
            ->setLastName($properties['last_name'] ?? '')
            ->setFirstName($properties['first_name'] ?? '')
            ->setUsername($properties['username'] ?? '')
            ->setPasswordHash($properties['password_hash'] ?? '')
            ->setEmail($properties['email'] ?? '')
            ->setPhone($properties['phone'] ?? null)
            ->setIpAddress($properties['ip_address'] ?? null)
            ->setGroupId($properties['group_id'] ?? null);

        // Propriétés optionnelles avec vérification
        if (!empty($properties['last_login'])) {
            try {
                $user->setLastLogin(new \DateTime($properties['last_login']));
            } catch (\Exception $e) {
                error_log("Error converting last_login: " . $e->getMessage());
                $user->setLastLogin(null);
            }
        }

        if (!empty($properties['created_at'])) {
            try {
                $user->setCreatedAt(new \DateTime($properties['created_at']));
            } catch (\Exception $e) {
                error_log("Error converting created_at: " . $e->getMessage());
                $user->setCreatedAt(new \DateTime());
            }
        } else {
            $user->setCreatedAt(new \DateTime());
        }

        if (!empty($properties['updated_at'])) {
            try {
                $user->setUpdatedAt(new \DateTime($properties['updated_at']));
            } catch (\Exception $e) {
                error_log("Error converting updated_at: " . $e->getMessage());
                $user->setUpdatedAt(null);
            }
        }

        return $user;
    }

    public function save(): bool
    {
        try {
            $conn = Connexion::getInstance()->getConn();
            $conn->beginTransaction();

            if ($this->id !== null) {
                // Update existing user
                $sql = "UPDATE `user` SET 
                    last_name = :lastName,
                    first_name = :firstName,
                    email = :email,
                    phone = :phone,
                    updated_at = :updatedAt";

                $params = [
                    ':lastName' => $this->lastName,
                    ':firstName' => $this->firstName,
                    ':email' => $this->email,
                    ':phone' => $this->phone,
                    ':updatedAt' => (new DateTime())->format('Y-m-d H:i:s')
                ];

                // Only update password if it's set
                if (isset($this->passwordHash)) {
                    $sql .= ", password_hash = :password";
                    $params[':password'] = $this->passwordHash;
                }

                // Update group if provided
                if ($this->groupId !== null) {
                    $sql .= ", group_id = :groupId";
                    $params[':groupId'] = $this->groupId;
                }

                $sql .= " WHERE id = :id";
                $params[':id'] = $this->id;

                $stmt = $conn->prepare($sql);
                $result = $stmt->execute($params);
            } else {
                // Insert new user
                $sql = "INSERT INTO `user` (
                    last_name, 
                    first_name, 
                    username, 
                    password_hash, 
                    email, 
                    phone, 
                    ip_address, 
                    group_id,
                    created_at
                ) VALUES (
                    :lastName,
                    :firstName,
                    :username,
                    :password,
                    :email,
                    :phone,
                    :ipAddress,
                    :groupId,
                    :createdAt
                )";

                $stmt = $conn->prepare($sql);

                $result = $stmt->execute([
                    ':lastName' => $this->lastName,
                    ':firstName' => $this->firstName,
                    ':username' => $this->username,
                    ':password' => $this->passwordHash,
                    ':email' => $this->email,
                    ':phone' => $this->phone,
                    ':ipAddress' => $_SERVER['REMOTE_ADDR'] ?? null,
                    ':groupId' => $this->groupId ?? 3, // Default to Member (ID 3)
                    ':createdAt' => (new DateTime())->format('Y-m-d H:i:s')
                ]);

                if ($result) {
                    $this->id = (int)$conn->lastInsertId();
                }
            }

            if ($result) {
                $conn->commit();
                return true;
            } else {
                $conn->rollBack();
                return false;
            }
        } catch (PDOException $e) {
            if (isset($conn)) {
                $conn->rollBack();
            }
            error_log("Error saving user: " . $e->getMessage());
            return false;
        }
    }

    public function hasRole(string $roleName): bool
    {
        if ($this->groupName === null && $this->groupId !== null) {
            // Fetch the group name if needed
            try {
                $conn = Connexion::getInstance()->getConn();
                $stmt = $conn->prepare(
                    "SELECT name FROM `user_group` WHERE id = ?"
                );
                $stmt->execute([$this->groupId]);
                $this->groupName = $stmt->fetchColumn();
            } catch (PDOException $e) {
                error_log("Error fetching group name: " . $e->getMessage());
            }
        }

        return $this->groupName === $roleName;
    }

    public static function findByUsername(string $username): ?User
    {
        try {
            $conn = Connexion::getInstance()->getConn();
            $stmt = $conn->prepare("
                SELECT u.*, g.name as group_name 
                FROM `user` u
                LEFT JOIN `user_group` g ON u.group_id = g.id
                WHERE u.username = ?
            ");
            $stmt->execute([$username]);

            if ($userData = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                $user = self::hydrate($userData);
                $user->setGroupName($userData['group_name']);
                return $user;
            }

            return null;
        } catch (PDOException $e) {
            error_log("Error finding user by username: " . $e->getMessage());
            throw $e;
        }
    }

    public static function getAllUsers(): array
    {
        try {
            $conn = Connexion::getInstance()->getConn();
            $stmt = $conn->prepare("
                SELECT u.*, GROUP_CONCAT(g.name) as group_names
                FROM users u
                LEFT JOIN users_groups ug ON u.id_user = ug.id_user
                LEFT JOIN `groups` g ON ug.id_group = g.id_group
                GROUP BY u.id_user
                ORDER BY u.created_at DESC
            ");
            $stmt->execute();
            $users = [];

            while ($userData = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                $user = self::hydrate($userData);
                // Conversion de la chaîne des groupes en tableau
                $groupNames = $userData['group_names'] ? explode(',', $userData['group_names']) : [];
                $user->setGroups($groupNames);
                $users[] = $user;
            }

            return $users;
        } catch (\PDOException $e) {
            error_log("Erreur lors de la récupération des utilisateurs : " . $e->getMessage());
            return [];
        }
    }

    public static function updateUser($userId, array $data): bool
    {
        try {
            $conn = Connexion::getInstance()->getConn();
            $conn->beginTransaction();

            // Mise à jour des informations de base
            $sql = "UPDATE users SET 
                name_user = :name,
                firstname_user = :firstname,
                email_user = :email,
                tel_user = :tel,
                update_at = NOW()
            WHERE id_user = :id";

            $stmt = $conn->prepare($sql);
            $success = $stmt->execute([
                ':name' => $data['name'],
                ':firstname' => $data['firstname'],
                ':email' => $data['email'],
                ':tel' => $data['tel'],
                ':id' => $userId
            ]);

            // Gestion de l'abonnement à la newsletter
//            $newsletterSubscription = isset($data['newsletter_subscription']) ? true : false;
            $newsletterSubscription = !empty($data['newsletter_subscription']);

            // Point d'arrêt
            xdebug_break();

// Log détaillé
            error_log("Données de mise à jour : " . print_r($data, true));
            error_log("Statut newsletter : " . ($newsletterSubscription ? 'Abonné' : 'Désabonné'));

            if ($newsletterSubscription) {
                error_log("dans s'abonner");
                // S'abonner ou réabonner
                $success = NewsletterSubscription::subscribe($userId, $data['email']) && $success;
                if (!NewsletterSubscription::subscribe($userId, $data['email'])) {
                    error_log("Échec de l'abonnement newsletter pour l'utilisateur $userId");
                    // Gérer l'erreur potentielle
                }
            } else {
                // Se désabonner
                error_log("dans se désabonner");
                $success = NewsletterSubscription::unsubscribe($userId) && $success;
            }

            // Si un nouveau mot de passe est fourni
            if (!empty($data['password'])) {
                $sql = "UPDATE users SET password_hash_user = :password WHERE id_user = :id";
                $stmt = $conn->prepare($sql);
                $success = $stmt->execute([
                        ':password' => password_hash($data['password'], PASSWORD_BCRYPT),
                        ':id' => $userId
                    ]) && $success;
            }

            // Mise à jour du rôle
            if (isset($data['role'])) {
                // 1. Suppression de tous les rôles existants
                $stmt = $conn->prepare("DELETE FROM users_groups WHERE id_user = ?");
                $success = $stmt->execute([$userId]) && $success;

                // 2. Ajout du nouveau rôle
                $group = Group::findByName($data['role']);
                if ($group) {
                    $stmt = $conn->prepare("INSERT INTO users_groups (id_user, id_group) VALUES (?, ?)");
                    $success = $stmt->execute([$userId, $group->getId()]) && $success;
                } else {
                    // Si le rôle n'est pas trouvé, on met l'utilisateur comme membre par défaut
                    $memberGroup = Group::findByName('Member');
                    if ($memberGroup) {
                        $stmt = $conn->prepare("INSERT INTO users_groups (id_user, id_group) VALUES (?, ?)");
                        $success = $stmt->execute([$userId, $memberGroup->getId()]) && $success;
                    }
                }
            }

            if ($success) {
                $conn->commit();
                return true;
            } else {
                $conn->rollBack();
                return false;
            }
        } catch (\PDOException $e) {
            error_log("Erreur lors de la mise à jour de l'utilisateur : " . $e->getMessage());
            if (isset($conn)) {
                $conn->rollBack();
            }
            return false;
        }
    }

    public static function deleteUser($userId): bool
    {
        try {
            $conn = Connexion::getInstance()->getConn();
            $conn->beginTransaction();

            // Suppression des relations dans users_groups
            $stmt = $conn->prepare("DELETE FROM users_groups WHERE id_user = ?");
            $stmt->execute([$userId]);

            // Suppression de l'utilisateur
            $stmt = $conn->prepare("DELETE FROM users WHERE id_user = ?");
            $success = $stmt->execute([$userId]);

            if ($success) {
                $conn->commit();
                return true;
            } else {
                $conn->rollBack();
                return false;
            }
        } catch (\PDOException $e) {
            if (isset($conn)) {
                $conn->rollBack();
            }
            error_log("Erreur lors de la suppression de l'utilisateur : " . $e->getMessage());
            return false;
        }
    }

    public static function findById($id): ?User
    {
        try {
            $conn = Connexion::getInstance()->getConn();
            $stmt = $conn->prepare("
                SELECT u.*, GROUP_CONCAT(g.name) as group_names
                FROM users u
                LEFT JOIN users_groups ug ON u.id_user = ug.id_user
                LEFT JOIN `groups` g ON ug.id_group = g.id_group
                WHERE u.id_user = ?
                GROUP BY u.id_user
            ");
            $stmt->execute([$id]);

            if ($userData = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                $user = self::hydrate($userData);
                $groupNames = $userData['group_names'] ? explode(',', $userData['group_names']) : [];
                $user->setGroups($groupNames);
                return $user;
            }

            return null;
        } catch (\PDOException $e) {
            error_log("Erreur lors de la recherche de l'utilisateur : " . $e->getMessage());
            return null;
        }
    }

    public function isSubscribedToNewsletter(): bool
    {
        try {
            $conn = Connexion::getInstance()->getConn();
            $stmt = $conn->prepare("
            SELECT is_active 
            FROM newsletter_subscriptions 
            WHERE id_user = ? 
            LIMIT 1
        ");
            $stmt->execute([$this->id]);

            $result = $stmt->fetch(\PDO::FETCH_ASSOC);

            return $result ? (bool)$result['is_active'] : false;
        } catch (\PDOException $e) {
            error_log("Erreur lors de la vérification de l'abonnement newsletter : " . $e->getMessage());
            return false;
        }
    }


}