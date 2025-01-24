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
        redirectToError("Gebruiker niet ingelogd of geen adres gegeven.", "/cart");
    }

    $clientUsername = $_SESSION['username'];
    $clientName = $_SESSION['user_full_name'] ?? 'Onbekend';
    $address = htmlspecialchars($_POST['address']);
    $cart = getCart();

    if (empty($cart)) {
        redirectToError("Je winkelwagentje is leeg. voeg items toe voordat je bestelt.", "/menu");
    }

    try {
        $orderId = placeOrder($clientUsername, $clientName, $address);

        $_SESSION['success_message'] = "Bestelling geplaatst met ID: $orderId";
        echo "<script>alert('bestelling geplaatst!');</script>";
        header("Location: /menu");
        exit;
    } catch (Exception $e) {
        redirectToError("Er is iets misgegaan!: " . $e->getMessage(), "/cart");
    }
}

function handleNext() {
    if (!isset($_POST['order_id'], $_POST['current_status'])) {
        redirectToError("bestelling id of status is niet gevonden.", "/ordersOverview");
    }

    $orderId = intval($_POST['order_id']);
    $currentStatus = intval($_POST['current_status']);

    try {
        $newStatus = $currentStatus + 1;

        if ($newStatus > 3) {
            redirectToError("Kan niet de status verder veranderen. het is al op het einde.", "/ordersOverview");
        }

        Order::updateOrderStatus($orderId, $newStatus);

        $_SESSION['success_message'] = "Bestelling status succesvol verandert.";
        header("Location: /ordersOverview");
        exit;
    } catch (Exception $e) {
        redirectToError("Error updating bestelling status: " . $e->getMessage(), "/ordersOverview");
    }
}

function handlePrevious() {
    if (!isset($_POST['order_id'], $_POST['current_status'])) {
        redirectToError("bestelling id of status is niet gevonden", "/ordersOverview");
    }

    $orderId = intval($_POST['order_id']);
    $currentStatus = intval($_POST['current_status']);

    try {
        $newStatus = $currentStatus - 1;

        if ($newStatus < 0) {
            redirectToError("Kan niet naar de vorige stap. Status al op minimum.", "/ordersOverview");
        }

        Order::updateOrderStatus($orderId, $newStatus);

        $_SESSION['success_message'] = "bestelling status successvol geupdate.";
        header("Location: /ordersOverview");
        exit;
    } catch (Exception $e) {
        redirectToError("Error updating bestelling status: " . $e->getMessage(), "/ordersOverview");
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
        throw new Exception("Product niet gevonden.");
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
        throw new Exception("Ckan geen bestelling plaatsen zonder items in winkelwagentje.");
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
