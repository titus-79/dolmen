<?php
namespace Titus\Dolmen\Models;

class Order extends BaseModel
{
    protected $table = '`order`';

    public function getUserOrders($userId)
    {
        try {
            $query = "
                SELECT DISTINCT o.*
                FROM `order` o
                WHERE o.id_user = ?
                ORDER BY o.created_at DESC
            ";

            error_log("Exécution de la requête getUserOrders pour userId: " . $userId);
            $orders = $this->db->query($query, [$userId]);
            error_log("Nombre de commandes trouvées: " . count($orders));

            // Pour chaque commande, récupérons ses tirages
            foreach ($orders as &$order) {
                $order['prints'] = $this->getOrderPrints($order['id_order']);
            }

            return $orders;
        } catch (\PDOException $e) {
            error_log("Erreur dans getUserOrders: " . $e->getMessage());
            return [];
        }
    }

    private function getOrderPrints($orderId)
    {
        $query = "
            SELECT p.title_print, p.state_print
            FROM print p
            JOIN print_order po ON p.id_print = po.id_print
            WHERE po.id_order = ?
        ";

        try {
            return $this->db->query($query, [$orderId]);
        } catch (\PDOException $e) {
            error_log("Erreur dans getOrderPrints: " . $e->getMessage());
            return [];
        }
    }

    public function createOrder($data)
    {
        $query = "
            INSERT INTO `order` 
            (status, total_amount, created_at, id_user) 
            VALUES (?, ?, ?, ?)
        ";

        try {
            return $this->db->query($query, [
                $data['status'] ?? 'en cours',
                $data['total_amount'],
                date('Y-m-d'),
                $data['user_id']
            ]);
        } catch (\PDOException $e) {
            error_log("Erreur dans createOrder: " . $e->getMessage());
            return false;
        }
    }

    public function addPrintToOrder($orderId, $printId)
    {
        $query = "
            INSERT INTO print_order 
            (id_order, id_print) 
            VALUES (?, ?)
        ";

        try {
            return $this->db->query($query, [$orderId, $printId]);
        } catch (\PDOException $e) {
            error_log("Erreur dans addPrintToOrder: " . $e->getMessage());
            return false;
        }
    }
}