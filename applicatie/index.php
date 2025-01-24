<?php
session_start();

// Autoload helper functions and classes
require_once __DIR__ . '/app/config/db_connectie.php';
// require_once __DIR__ . '/app/helpers/Session.php';
// require_once __DIR__ . '/app/helpers/Validation.php';

// Define routes
$requestUri = $_SERVER['REQUEST_URI'];
switch ($requestUri) {
    case '/menu':
        require_once __DIR__ . '/app/controllers/MenuController.php';
        $controller = new MenuController();
        $controller->displayMenu();
        break;

    case '/cart':
        require_once __DIR__ . '/app/controllers/OrderController.php';
        $controller = new OrderController();
        $controller->displayCart();
        break;

    case '/order_summary':
        require_once __DIR__ . '/app/controllers/OrderController.php';
        $controller = new OrderController();
        $controller->displayOrderSummary();
        break;

    case '/orders':
        require_once __DIR__ . '/app/controllers/OrderController.php';
        $controller = new OrderController();
        $controller->displayOrders();
        break;

    case '/profile':
        require_once __DIR__ . '/app/controllers/UserController.php';
        $controller = new UserController();
        $controller->displayProfile();
        break;

    case '/login':
        require_once __DIR__ . '/app/controllers/UserController.php';
        $controller = new UserController();
        $controller->displayLogin();
        break;

    case '/register':
        require_once __DIR__ . '/app/controllers/UserController.php';
        $controller = new UserController();
        $controller->displayRegister();
        break;
    
    case '/logout':
        session_destroy();
        header('Location: /login');
        exit;

    default:
        header('Location: /menu');
        exit;
}
