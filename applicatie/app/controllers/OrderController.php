<?php
require_once '../models/Order.php';

class OrderController {
    public function addItemToOrder($orderId, $menuItemId, $quantity) {
        Order::addItem($orderId, $menuItemId, $quantity);
        header('Location: ../views/order_summary.php');
    }

    public function submitOrder($orderId, $address) {
        Order::submit($orderId, $address);
        header('Location: ../views/order_confirmation.php');
    }

    public function getCustomerOrders($customerId) {
        return Order::getByCustomerId($customerId);
    }
    
    public function getAllOrders() {
        return Order::getAll();
    }

    public function showOrderSummary() {
        $orderModel = new Order();
        $orderDetails = $orderModel->getOrderDetails($_SESSION['user_id']);
        $totalPrice = array_reduce($orderDetails, function ($carry, $item) {
            return $carry + ($item['price'] * $item['quantity']);
        }, 0);

        require_once __DIR__ . '/../views/order_summary.php';
    }
    
    
}


?>

