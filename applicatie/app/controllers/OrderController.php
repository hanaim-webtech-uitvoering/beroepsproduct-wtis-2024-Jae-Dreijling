<?php

require_once __DIR__ . '/../models/Order.php';

class OrderController {

    public function showCart() {
        $order = new Order();
        $cartItems = $order->getCartItems('TO-DO'); // Replace 'TODO' with actual username from session or input
        require '/../app/views/cart.php';
    }
    

    public function addItemToOrder($orderId, $productName, $quantity) {
        Order::addItem($orderId, $productName, $quantity);
        header('Location: ../views/order_summary.php');
        exit;
    }
    

    public function addToCart() {
        session_start();
    
        $menuItemId = $_POST['product_name'];
        $quantity = $_POST['quantity'];
    
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
    
        // Assume you fetch product details from the database based on $menuItemId
        $product = Menu::getItemById($menuItemId);
    
        $cartItem = [
            'id' => $product['id'],
            'name' => $product['product_name'],
            'price' => $product['price'],
            'quantity' => $quantity,
        ];
    
        $_SESSION['cart'][] = $cartItem;
    
        header('Location: /cart');
        exit();
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
