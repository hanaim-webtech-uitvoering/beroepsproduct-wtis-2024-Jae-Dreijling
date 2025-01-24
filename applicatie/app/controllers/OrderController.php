<?php
require_once __DIR__ . '/../logic/orderFunctions.php';

class OrderController {
    public function displayCart() {
        $cart = getCart();
        require_once __DIR__ . '/../views/cart.php';
    }

    public function displayOrderSummary() {
        if (!isCartNotEmpty()) {
            $_SESSION['error_message'] = "Uw winkelwagentje is leeg. Voeg items toe voordat u doorgaat.";
            header('Location: /menu');
            exit;
        }

        $cart = getCart();
        require_once __DIR__ . '/../views/order_summary.php';
    }

    public function displayOrders() {
        // Only personnel allowed
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Personnel') {
            header('Location: /login');
            exit;
        }

        try {
            $orders = Order::getAllOrders();
        } catch (Exception $e) {
            $errorMessage = $e->getMessage();
        }

        require_once __DIR__ . '/../views/ordersOverview.php';
    }
}
