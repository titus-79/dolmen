<?php

namespace Titus\Dolmen\Models;

class NewsletterSubscription
{
    private ?int $id = null;
    private int $userId;
    private string $email;
    private bool $isActive;
    private \DateTime $subscriptionDate;

    public static function subscribe(int $userId, string $email): bool
    {
        try {
            $conn = Connexion::getInstance()->getConn();
            $stmt = $conn->prepare("
                INSERT INTO newsletter_subscriptions (id_user, email, is_active)
                VALUES (?, ?, true)
                ON DUPLICATE KEY UPDATE is_active = true
            ");
            return $stmt->execute([$userId, $email]);
        } catch (\PDOException $e) {
            error_log("Erreur lors de l'inscription à la newsletter : " . $e->getMessage());
            return false;
        }
    }

    public static function unsubscribe(int $userId): bool
    {
        try {
            $conn = Connexion::getInstance()->getConn();
            $stmt = $conn->prepare("
                UPDATE newsletter_subscriptions 
                SET is_active = false 
                WHERE id_user = ?
            ");
            return $stmt->execute([$userId]);
        } catch (\PDOException $e) {
            error_log("Erreur lors de la désinscription de la newsletter : " . $e->getMessage());
            return false;
        }
    }

    public static function getAllActiveSubscribers(): array
    {
        try {
            $conn = Connexion::getInstance()->getConn();
            $stmt = $conn->prepare("
                SELECT ns.*, u.firstname_user, u.name_user 
                FROM newsletter_subscriptions ns
                JOIN users u ON ns.id_user = u.id_user
                WHERE ns.is_active = true
            ");
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log("Erreur lors de la récupération des abonnés : " . $e->getMessage());
            return [];
        }
    }
}