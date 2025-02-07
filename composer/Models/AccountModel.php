<?php

namespace Titus\Dolmen\Models;

class Account extends User
{
    public function updateLastConnection(): bool
    {
        try {
            $conn = Connexion::getInstance()->getConn();
            $stmt = $conn->prepare("
                UPDATE users 
                SET last_conn = NOW() 
                WHERE id_user = ?
            ");
            return $stmt->execute([$this->getId()]);
        } catch (\PDOException $e) {
            error_log("Erreur lors de la mise à jour de la dernière connexion : " . $e->getMessage());
            return false;
        }
    }

    public function getAddresses(): array
    {
        try {
            $conn = Connexion::getInstance()->getConn();
            $stmt = $conn->prepare("
                SELECT a.* 
                FROM adress a
                JOIN order_adress oa ON a.id_adress = oa.id_adress
                JOIN `order` o ON oa.id_order = o.id_order
                WHERE o.id_user = ?
                GROUP BY a.id_adress
            ");
            $stmt->execute([$this->getId()]);
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log("Erreur lors de la récupération des adresses : " . $e->getMessage());
            return [];
        }
    }

    public function getStats(): array
    {
        try {
            $conn = Connexion::getInstance()->getConn();
            $stmt = $conn->prepare("
                SELECT 
                    COUNT(o.id_order) as total_orders,
                    SUM(o.total_amount) as total_spent,
                    MAX(o.created_at) as last_order_date
                FROM `order` o
                WHERE o.id_user = ?
            ");
            $stmt->execute([$this->getId()]);
            return $stmt->fetch(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log("Erreur lors de la récupération des statistiques : " . $e->getMessage());
            return [
                'total_orders' => 0,
                'total_spent' => 0,
                'last_order_date' => null
            ];
        }
    }
}