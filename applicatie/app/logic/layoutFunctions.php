<?php
function createHead($title = "Pizzeria Sole Machina 🍕") {
    echo <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>$title</title>
    <link href="/public/css/styles.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
HTML;
}

function createHeader($pageTitle) {
    $isLoggedIn = isset($_SESSION['username']);
    $currentPage = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
    $role = $_SESSION['role'] ?? null;

    $leftMenuItems = [];
    $rightMenuItems = [
        "login" => $isLoggedIn ? "Logout" : "Login",
    ];

    if ($isLoggedIn) {
        if ($role === 'Personnel') {
            // Menu items for personnel
            $leftMenuItems = [
                "ordersOverview" => "Order Overview",
                "profile" => "Profile",
            ];
        } else {
            // Menu items for clients
            $leftMenuItems = [
                "menu" => "Menu",
                "cart" => "Cart",
                "orders" => "My Orders",
                "profile" => "Profile",
            ];
        }
    } else {
        // menu items for not logged in users
        $leftMenuItems["cart"] = "Cart";
        $rightMenuItems["register"] = "Register";
    }

    echo <<<HTML
<header class="mb-4">
    <nav class="navbar navbar-expand-lg navbar-danger bg-danger">
        <div class="container">
            <a class="navbar-brand text-white" href="/menu">Pizzeria Sole Machina 🍕</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
HTML;

    foreach ($leftMenuItems as $route => $name) {
        $activeClass = $route === $currentPage ? "active" : "";
        echo "<li class='nav-item'><a class='nav-link text-white $activeClass' href='/$route'>$name</a></li>";
    }

    echo <<<HTML
                </ul>
                <ul class="navbar-nav ms-auto">
HTML;

    foreach ($rightMenuItems as $route => $name) {
        $activeClass = $route === $currentPage ? "active" : "";
        $url = ($route === "login" && $isLoggedIn) ? "/logout" : "/$route";
        echo "<li class='nav-item'><a class='nav-link text-white $activeClass' href='$url'>$name</a></li>";
    }

    echo <<<HTML
                </ul>
            </div>
        </div>
    </nav>
</header>
HTML;
}



function createFooter() {
    echo <<<HTML
<footer class="bg-dark text-white text-center py-3 mt-auto">
    <p>&copy; 2025 Pizzeria Sole Machina 🍕. All rights reserved.</p>
    <a href="privacy-policy.php" class="text-white text-decoration-underline">Privacy Policy</a>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
HTML;
}

?>
