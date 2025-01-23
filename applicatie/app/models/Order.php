<?php

class Order {
    private $db;

    public function __construct() {
        $this->db = require '/../config/db_connectie.php';
    }

    // Get detailed order items by user ID
    public function getOrderDetails($userId) {
        $stmt = $this->db->prepare(
            "SELECT m.name, oi.quantity, m.price 
            FROM order_items oi 
            JOIN menu m ON oi.menu_item_id = m.id 
            JOIN orders o ON oi.order_id = o.id 
            WHERE o.customer_id = ?"
        );
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Add item to an order
    public static function addItem($orderId, $menuItemId, $quantity) {
        $db = Database::connect();
        $stmt = $db->prepare("INSERT INTO order_items (order_id, menu_item_id, quantity) VALUES (?, ?, ?)");
        $stmt->execute([$orderId, $menuItemId, $quantity]);
    }

    // Submit an order with address
    public static function submit($orderId, $address) {
        $db = Database::connect();
        $stmt = $db->prepare("UPDATE orders SET address = ?, status = 'submitted' WHERE id = ?");
        $stmt->execute([$address, $orderId]);
    }

    // Update the status of an order
    public static function updateStatus($orderId, $status) {
        $db = Database::connect();
        $stmt = $db->prepare("UPDATE orders SET status = ? WHERE id = ?");
        $stmt->execute([$status, $orderId]);
    }

    // Get orders by customer ID
    public static function getByCustomerId($customerId) {
        $db = Database::connect();
        $stmt = $db->prepare("SELECT * FROM orders WHERE customer_id = ?");
        $stmt->execute([$customerId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get all orders
    public static function getAll() {
        $db = Database::connect();
        $stmt = $db->query("SELECT * FROM orders");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
