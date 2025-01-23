<?php

require_once '../models/Order.php';

class OrderController {
    // Add item to an order
    public function addItemToOrder($orderId, $menuItemId, $quantity) {
        Order::addItem($orderId, $menuItemId, $quantity);
        header('Location: ../views/order_summary.php');
        exit;
    }

    // Submit an order with an address
    public function submitOrder($orderId, $address) {
        Order::submit($orderId, $address);
        header('Location: ../views/order_confirmation.php');
        exit;
    }

    // Fetch orders for a specific customer
    public function getCustomerOrders($customerId) {
        return Order::getByCustomerId($customerId);
    }

    // Fetch all orders (e.g., for staff management)
    public function getAllOrders() {
        return Order::getAll();
    }

    // Show the order summary page for a logged-in user
    public function showOrderSummary() {
        $orderModel = new Order();
        $orderDetails = $orderModel->getOrderDetails($_SESSION['user_id']);

        $totalPrice = array_reduce($orderDetails, function ($carry, $item) {
            return $carry + ($item['price'] * $item['quantity']);
        }, 0);

        require_once __DIR__ . '/../views/order_summary.php';
    }
}
