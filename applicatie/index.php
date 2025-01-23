<?php
session_start();

// Autoload helper functions and classes
require_once __DIR__ . '/app/config/db_connectie.php';
require_once __DIR__ . '/app/helpers/Session.php';
require_once __DIR__ . '/app/helpers/Validation.php';

// Define routes
$requestUri = $_SERVER['REQUEST_URI'];
switch ($requestUri) {
    case '/menu':
        require_once __DIR__ . '/app/controllers/MenuController.php';
        $controller = new MenuController();
        $controller->showMenu();
        break;

    case '/cart':
        require_once __DIR__ . '/app/controllers/OrderController.php';
        $controller = new OrderController();
        $controller->showCart();
        break;

    case '/order_summary':
        require_once __DIR__ . '/app/controllers/OrderController.php';
        $controller = new OrderController();
        $controller->showOrderSummary();
        break;

    case '/profile':
        require_once __DIR__ . '/app/controllers/AuthController.php';
        $controller = new AuthController();
        $controller->profile();
        break;

    case '/login':
        require_once __DIR__ . '/app/controllers/AuthController.php';
        $controller = new AuthController();
        $controller->login();
        break;

    default:
        header('Location: /menu');
        exit;
}
