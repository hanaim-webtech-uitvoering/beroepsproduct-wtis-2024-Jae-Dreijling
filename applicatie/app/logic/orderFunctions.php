<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../data/orderData.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? null;

    switch ($action) {
        case 'add_to_cart':
            handleAddToCart();
            break;
        case 'remove_from_cart':
            handleRemoveFromCart();
            break;   
        case 'order_cart':
            handleOrder();
            break;      
        case 'next':
            handleNext();
            break;   
        case 'previous':
            handlePrevious();
            break;   

        // Future case: 'place_order', 'remove_item', etc.
        default:
            redirectToError("Ongeldige actie.", "/menu");
    }
}

function handleAddToCart() {
    if (!isset($_POST['product_name'], $_POST['quantity'])) {
        redirectToError("Vul alle vereiste velden in.", "/menu");
    }

    $productName = htmlspecialchars($_POST['product_name']);
    $quantity = intval($_POST['quantity']);

    if ($quantity < 1) {
        redirectToError("De hoeveelheid moet minimaal 1 zijn.", "/menu");
    }

    try {
        addToCart($productName, $quantity);

        // Set success message in the session
        $_SESSION['success_message'] = "Product succesvol toegevoegd aan het winkelwagentje.";
        header("Location: /menu");
        exit;
    } catch (Exception $e) {
        redirectToError("Er is een fout opgetreden: " . $e->getMessage(), "/menu");
    }
}

function handleRemoveFromCart() {
    if (!isset($_POST['product_name'])) {
        redirectToError("Productnaam ontbreekt.", "/cart");
    }

    $productName = htmlspecialchars($_POST['product_name']);

    try {
        removeFromCart($productName);

        // Set success message in the session
        $_SESSION['success_message'] = "Product succesvol verwijderd uit het winkelwagentje.";
        header("Location: /cart");
        exit;
    } catch (Exception $e) {
        redirectToError("Er is een fout opgetreden: " . $e->getMessage(), "/cart");
    }
}

function handleOrder() {
    if (!isset($_SESSION['username'], $_POST['address'])) {
        redirectToError("User not logged in or address not provided.", "/cart");
    }

    $clientUsername = $_SESSION['username'];
    $clientName = $_SESSION['user_full_name'] ?? 'Unknown User';
    $address = htmlspecialchars($_POST['address']);
    $cart = getCart();

    if (empty($cart)) {
        redirectToError("Your cart is empty. Add items before ordering.", "/menu");
    }

    try {
        $orderId = placeOrder($clientUsername, $clientName, $address);

        $_SESSION['success_message'] = "Order successfully placed with Order ID: $orderId";
        echo "<script>alert('Order placed successfully!');</script>";
        header("Location: /menu");
        exit;
    } catch (Exception $e) {
        redirectToError("An error occurred while placing the order: " . $e->getMessage(), "/cart");
    }
}

function handleNext() {
    if (!isset($_POST['order_id'], $_POST['current_status'])) {
        redirectToError("Order ID or current status missing.", "/ordersOverview");
    }

    $orderId = intval($_POST['order_id']);
    $currentStatus = intval($_POST['current_status']);

    try {
        // Increment the status value
        $newStatus = $currentStatus + 1;

        // Ensure status does not exceed the maximum value
        if ($newStatus > 3) {
            redirectToError("Cannot move to the next step. Status already at maximum.", "/ordersOverview");
        }

        Order::updateOrderStatus($orderId, $newStatus);

        $_SESSION['success_message'] = "Order status updated successfully.";
        header("Location: /ordersOverview");
        exit;
    } catch (Exception $e) {
        redirectToError("Error updating order status: " . $e->getMessage(), "/ordersOverview");
    }
}

function handlePrevious() {
    if (!isset($_POST['order_id'], $_POST['current_status'])) {
        redirectToError("Order ID or current status missing.", "/ordersOverview");
    }

    $orderId = intval($_POST['order_id']);
    $currentStatus = intval($_POST['current_status']);

    try {
        // Decrement the status value
        $newStatus = $currentStatus - 1;

        // Ensure status does not go below the minimum value
        if ($newStatus < 0) {
            redirectToError("Cannot move to the previous step. Status already at minimum.", "/ordersOverview");
        }

        Order::updateOrderStatus($orderId, $newStatus);

        $_SESSION['success_message'] = "Order status updated successfully.";
        header("Location: /ordersOverview");
        exit;
    } catch (Exception $e) {
        redirectToError("Error updating order status: " . $e->getMessage(), "/ordersOverview");
    }
}


function addToCart(string $productName, int $quantity): void {
    $productDetails = Order::getProductDetails($productName);

    if ($productDetails) {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        if (isset($_SESSION['cart'][$productName])) {
            $_SESSION['cart'][$productName]['quantity'] += $quantity;
        } else {
            $_SESSION['cart'][$productName] = [
                'product_name' => $productDetails['product_name'],
                'quantity' => $quantity,
                'price' => $productDetails['price'],
            ];
        }
    } else {
        throw new Exception("Product not found.");
    }
}

// Remove a product from the cart
function removeFromCart(string $productName): void {
    if (isset($_SESSION['cart'][$productName])) {
        unset($_SESSION['cart'][$productName]);
    } else {
        throw new Exception("Product niet gevonden in het winkelwagentje.");
    }
}

// Check if the cart is not empty
function isCartNotEmpty(): bool {
    return !empty($_SESSION['cart']);
}

// Get the current cart
function getCart(): array {
    return $_SESSION['cart'] ?? [];
}

// Place an order
function placeOrder(string $clientUsername, string $clientName, string $address): int {
    $cart = getCart();

    if (empty($cart)) {
        throw new Exception("Cannot place an order with an empty cart.");
    }

    // Add order to the database
    $orderId = Order::addOrder($clientUsername, $clientName, $address, $cart);

    // Clear the cart after the order is placed
    unset($_SESSION['cart']);

    return $orderId;
}

function redirectToError(string $message, string $location) {
    $_SESSION['error_message'] = $message;
    header("Location: $location");
    exit;
}
