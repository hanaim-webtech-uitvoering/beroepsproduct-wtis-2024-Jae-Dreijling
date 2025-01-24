<?php
session_start();

require_once __DIR__ . '/app/config/db_connectie.php';

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

    case '/orders':
        require_once __DIR__ . '/app/controllers/UserController.php';
        $controller = new UserController();
        $controller->displayProfile();
        break;

    case '/profile':
        require_once __DIR__ . '/app/controllers/UserController.php';
        $controller = new UserController();
        $controller->displayProfile();
        break;
    
    case '/ordersOverview':
        require_once __DIR__ . '/app/controllers/OrderController.php';
        $controller = new OrderController();
        $controller->displayOrders();
        break;
        

    case '/login':
        require_once __DIR__ . '/app/controllers/UserController.php';
        $controller = new UserController();
        $controller->displayLogin();
        break;
    
    case '/medewerkerLogin':
        require_once __DIR__ . '/app/controllers/UserController.php';
        $controller = new UserController();
        $controller->displayWorkerLogin();
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
