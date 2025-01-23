<?php
require_once '../config/db_connectie.php';

class Order {

    public static function addItem($orderId, $menuItemId, $quantity) {
        $db = Database::connect();
        $stmt = $db->prepare("INSERT INTO order_items (order_id, menu_item_id, quantity) VALUES (?, ?, ?)");
        $stmt->execute([$orderId, $menuItemId, $quantity]);
    }

    public static function submit($orderId, $address) {
        $db = Database::connect();
        $stmt = $db->prepare("UPDATE orders SET address = ?, status = 'submitted' WHERE id = ?");
        $stmt->execute([$address, $orderId]);
    }

    public static function updateStatus($orderId, $status) {
        $db = Database::connect();
        $stmt = $db->prepare("UPDATE orders SET status = ? WHERE id = ?");
        $stmt->execute([$status, $orderId]);
    }    

    public static function getByCustomerId($customerId) {
        $db = Database::connect();
        $stmt = $db->prepare("SELECT * FROM orders WHERE customer_id = ?");
        $stmt->execute([$customerId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public static function getAll() {
        $db = Database::connect();
        $stmt = $db->query("SELECT * FROM orders");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } 
    
    public function getOrderDetails($userId) {
        $query = $this->db->prepare("SELECT m.name, o.quantity, m.price 
                                     FROM orders o 
                                     JOIN menu m ON o.menu_id = m.id 
                                     WHERE o.user_id = ?");
        $query->execute([$userId]);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
    
}
?>
