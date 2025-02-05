<?php
namespace Titus\Dolmen\Models;

class Order extends BaseModel
{
    protected $table = 'order';

    /**
     * Crée une nouvelle commande
     * @param array $data Données de la commande
     * @return int|null ID de la commande créée
     */
    public function createOrder($data)
    {
        $query = "
            INSERT INTO {$this->table} 
            (status, total_amount, created_at, id_user) 
            VALUES (?, ?, ?, ?)
        ";

        $this->db->query($query, [
            $data['status'] ?? 'en cours',
            $data['total_amount'],
            date('Y-m-d'),
            $data['user_id']
        ]);

        return $this->db->lastInsertId();
    }

    /**
     * Ajoute un tirage à une commande
     * @param int $orderId ID de la commande
     * @param int $printId ID du tirage
     */
    public function addPrintToOrder($orderId, $printId)
    {
        $query = "
            INSERT INTO print_order 
            (id_order, id_print) 
            VALUES (?, ?)
        ";

        $this->db->query($query, [$orderId, $printId]);
    }

    /**
     * Récupère les commandes d'un utilisateur
     * @param int $userId ID de l'utilisateur
     * @return array Liste des commandes
     */
    public function getUserOrders($userId)
    {
        $query = "
            SELECT o.*, 
                   p.title_print, 
                   p.state_print
            FROM {$this->table} o
            JOIN print_order po ON o.id_order = po.id_order
            JOIN print p ON po.id_print = p.id_print
            WHERE o.id_user = ?
            ORDER BY o.created_at DESC
        ";

        return $this->db->query($query, [$userId]);
    }
}