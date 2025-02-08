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
private string $name;
private string $login;
private string $passwordHash;
private ?string $status = null; //  todo verifier doublon avec group
private string $firstname;
private string $email;
private string $tel;
private string $ip;
private ?\DateTime $lastConn = null; // todo
private \DateTime $createdAt;
private ?\DateTime $updateAt = null; // todo
private ?array $groups = null;

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt): User
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getUpdateAt(): \DateTime
    {
        return $this->updateAt;
    }

    public function setUpdateAt(?\DateTime $updateAt): User
    {
        $this->updateAt = $updateAt;
        return $this;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): User
    {
        $this->id = $id;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): User
    {
        $this->name = $name;
        return $this;
    }

    public function getLogin(): string
    {
        return $this->login;
    }

    public function setLogin(string $login): User
    {
        $this->login = $login;
        return $this;
    }

    public function getPasswordHash(): string
    {
        return $this->passwordHash;
    }

    public function setPasswordHash(string $passwordHash): User
    {
        $this->passwordHash = $passwordHash;
        return $this;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(?string $status): User
    {
        $this->status = $status;
        return $this;
    }

    public function getFirstname(): string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): User
    {
        $this->firstname = $firstname;
        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): User
    {
        $this->email = $email;
        return $this;
    }

    public function getTel(): string
    {
        return $this->tel;
    }

    public function setTel(string $tel): User
    {
        $this->tel = $tel;
        return $this;
    }

    public function getIp(): string
    {
        return $this->ip;
    }

    public function setIp(string $ip): User
    {
        $this->ip = $ip;
        return $this;
    }

    public function getLastConn(): ?\DateTime
    {
        return $this->lastConn;
    }

    public function setLastConn(?\DateTime $lastConn): self
    {
        $this->lastConn = $lastConn;
        return $this;
    }

    public function getGroups(): ?array
    {
        return $this->groups;
    }

    public function setGroups(?array $groups): void
    {
        $this->groups = $groups;
    }

    public function addGroup(Group $group): User
    {
        $this->groups[] = $group;
        return $this;
    }

    /**
     * @throws \DateMalformedStringException
     */
    public static function hydrate(array $properties): User
    {
        $user = new User;

        // Propriétés obligatoires
        $user->setId($properties['id_user'])
            ->setName($properties['name_user'])
            ->setLogin($properties['login_user'])
            ->setPasswordHash($properties['password_hash_user'])
            ->setFirstname($properties['firstname_user'])
            ->setEmail($properties['email_user']);

        // Propriétés optionnelles avec vérification
        if (isset($properties['status_user'])) {
            $user->setStatus($properties['status_user']);
        }

        if (isset($properties['tel_user'])) {
            $user->setTel($properties['tel_user']);
        }

        if (isset($properties['ip_user'])) {
            $user->setIp($properties['ip_user']);
        }

        // Gestion des dates avec vérification
        if (!empty($properties['last_conn'])) {
            try {
                $user->setLastConn(new \DateTime($properties['last_conn']));
            } catch (\Exception $e) {
                error_log("Erreur lors de la conversion de last_conn: " . $e->getMessage());
                $user->setLastConn(null);
            }
        }

        if (!empty($properties['created_at'])) {
            try {
                $user->setCreatedAt(new \DateTime($properties['created_at']));
            } catch (\Exception $e) {
                error_log("Erreur lors de la conversion de created_at: " . $e->getMessage());
                $user->setCreatedAt(new \DateTime()); // Date actuelle par défaut
            }
        } else {
            $user->setCreatedAt(new \DateTime()); // Date actuelle par défaut
        }

        if (!empty($properties['update_at'])) {
            try {
                $user->setUpdateAt(new \DateTime($properties['update_at']));
            } catch (\Exception $e) {
                error_log("Erreur lors de la conversion de update_at: " . $e->getMessage());
                $user->setUpdateAt(null);
            }
        }

        return $user;
    }

    public function save(): bool
    {
        try {
            $conn = Connexion::getInstance()->getConn();

            // Start transaction
            $conn->beginTransaction();

            if ($this->id !== null) {
                // Update existing user
                $sql = "UPDATE users SET 
                    name_user = :name,
                    firstname_user = :firstname,
                    email_user = :email,
                    tel_user = :tel,
                    update_at = :update_at";

                $params = [
                    ':name'      => $this->name,
                    ':firstname' => $this->firstname,
                    ':email'     => $this->email,
                    ':tel'       => $this->tel,
                    ':update_at' => (new DateTime())->format('Y-m-d H:i:s')
                ];
                // Only update password if it's set
                if (isset($this->passwordHash)) {
                    $sql .= ", password_hash_user = :password";
                    $params[':password'] = $this->passwordHash;
                }

                $sql .= " WHERE id_user = :id";
                $params[':id'] = $this->id;

                $stmt = $conn->prepare($sql);
                $result = $stmt->execute($params);
            } else {
                // Insert new user
                $sql = "INSERT INTO users (
                    name_user, 
                    login_user, 
                    password_hash_user, 
                    firstname_user, 
                    email_user, 
                    tel_user, 
                    ip_user, 
                    created_at
                ) VALUES (
                    :name,
                    :login,
                    :password,
                    :firstname,
                    :email,
                    :tel,
                    :ip,
                    :created_at
                )";

                $stmt = $conn->prepare($sql);

                $result = $stmt->execute([
                    ':name'       => $this->name,
                    ':login'      => $this->login,
                    ':password'   => $this->passwordHash,
                    ':firstname'  => $this->firstname,
                    ':email'      => $this->email,
                    ':tel'        => $this->tel,
                    ':ip'         => $_SERVER['REMOTE_ADDR'],
                    ':created_at' => (new DateTime())->format('Y-m-d H:i:s')
                ]);

                if ($result) {
                    $this->id = (int)$conn->lastInsertId();

                    // Add user to Member group
                    $memberGroup = Group::findByName('Member');
                    if ($memberGroup) {
                        $userGroup = new UserGroup($this->id, $memberGroup->getId());
                        $result = $userGroup->save();
                    }
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
        if ($this->groups === null) {
            $conn = Connexion::getInstance()->getConn();
            $stmt = $conn->prepare(
                "SELECT g.name FROM `groups` g 
             JOIN users_groups ug ON g.id_group = ug.id_group 
             WHERE ug.id_user = ?"
            );
            $stmt->execute([$this->id]);
            $this->groups = $stmt->fetchAll(\PDO::FETCH_COLUMN);
        }

        return in_array($roleName, $this->groups);
    }

    public static function findByLogin(string $login): ?User
    {
        try {
            $conn = Connexion::getInstance()->getConn();
            $stmt = $conn->prepare("SELECT * FROM users WHERE login_user = ?");
            $stmt->execute([$login]);

            if ($userData = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                return self::hydrate($userData);
            }

            return null;
        } catch (\PDOException $e) {
            // Log l'erreur
            error_log("Erreur lors de la recherche d'utilisateur : " . $e->getMessage());
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
                error_log("dans s'abonner" );
                // S'abonner ou réabonner
                $success = NewsletterSubscription::subscribe($userId, $data['email']) && $success;
                if (!NewsletterSubscription::subscribe($userId, $data['email'])) {
                    error_log("Échec de l'abonnement newsletter pour l'utilisateur $userId");
                    // Gérer l'erreur potentielle
                }
            } else {
                // Se désabonner
                error_log("dans se désabonner" );
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