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

            // Get the PDO connection from the database instance
            $conn = $this->db->getConnection();

            // Prepare and execute the query
            $stmt = $conn->prepare($query);
            $stmt->execute([$userId]);

            // Fetch all results as an associative array
            $orders = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            error_log("Nombre de commandes trouvées: " . count($orders));

            // For each order, get its prints
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
        try {
            $query = "
                SELECT p.title_print, p.state_print
                FROM print p
                JOIN print_order po ON p.id_print = po.id_print
                WHERE po.id_order = ?
            ";

            // Get the PDO connection
            $conn = $this->db->getConnection();

            // Prepare and execute the query
            $stmt = $conn->prepare($query);
            $stmt->execute([$orderId]);

            // Return all prints for this order
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);

        } catch (\PDOException $e) {
            error_log("Erreur dans getOrderPrints: " . $e->getMessage());
            return [];
        }
    }

    public function createOrder($data)
    {
        try {
            $query = "
                INSERT INTO `order` 
                (status, total_amount, created_at, id_user) 
                VALUES (?, ?, ?, ?)
            ";

            $conn = $this->db->getConnection();
            $stmt = $conn->prepare($query);

            $result = $stmt->execute([
                $data['status'] ?? 'en cours',
                $data['total_amount'],
                date('Y-m-d'),
                $data['user_id']
            ]);

            if ($result) {
                return $conn->lastInsertId();
            }

            return false;

        } catch (\PDOException $e) {
            error_log("Erreur dans createOrder: " . $e->getMessage());
            return false;
        }
    }

    public function addPrintToOrder($orderId, $printId)
    {
        try {
            $query = "
                INSERT INTO print_order 
                (id_order, id_print) 
                VALUES (?, ?)
            ";

            $conn = $this->db->getConnection();
            $stmt = $conn->prepare($query);
            return $stmt->execute([$orderId, $printId]);

        } catch (\PDOException $e) {
            error_log("Erreur dans addPrintToOrder: " . $e->getMessage());
            return false;
        }
    }
}