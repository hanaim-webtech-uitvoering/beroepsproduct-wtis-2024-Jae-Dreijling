<?php

class Order {
    private $db 

    //TO-DO see if i can establish a global connection instead of per model to avoid re-delcaring the db_connectie declaration of the connection

    public function __construct() {
        $this->db = require __DIR__ . '/../config/db_connectie.php';
    }

    // Get detailed order items by user ID
    public function getOrderDetails($username) {
        $stmt = $this->db->prepare(
            "SELECT p.name, pop.quantity, p.price 
            FROM Pizza_Order_Product pop
            JOIN Product p ON pop.product_name = p.name
            JOIN Pizza_Order po ON pop.order_id = po.order_id
            WHERE po.client_username = ?"
        );
        $stmt->execute([$username]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public static function addItem($orderId, $productName, $quantity) {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
    
        if (isset($_SESSION['cart'][$productName])) {
            $_SESSION['cart'][$productName]['quantity'] += $quantity;
        } else {
            // Assuming a function getProductByName exists to get product details
            $product = Menu::getProductByName($productName);
            $_SESSION['cart'][$productName] = [
                'name' => $productName,
                'price' => $product['price'],
                'quantity' => $quantity
            ];
        }
    }
    
    public static function removeItem($productName) {
        if (isset($_SESSION['cart'][$productName])) {
            unset($_SESSION['cart'][$productName]);
        }
    }
    
    public static function getCartItems() {
        return $_SESSION['cart'] ?? [];
    }
    

    // Submit an order with address
    public static function submit($orderId, $address) {
        $db = Database::connect();
        $stmt = $db->prepare("UPDATE Pizza_Order SET address = ?, status = 1 WHERE order_id = ?");
        $stmt->execute([$address, $orderId]);
    }

    // Update the status of an order
    public static function updateStatus($orderId, $status) {
        $db = Database::connect();
        $stmt = $db->prepare("UPDATE Pizza_Order SET status = ? WHERE order_id = ?");
        $stmt->execute([$status, $orderId]);
    }

    // Get orders by customer username
    public static function getByCustomerUsername($username) {
        $db = Database::connect();
        $stmt = $db->prepare("SELECT * FROM Pizza_Order WHERE client_username = ?");
        $stmt->execute([$username]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get all orders
    public static function getAll() {
        $db = Database::connect();
        $stmt = $db->query("SELECT * FROM Pizza_Order");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
