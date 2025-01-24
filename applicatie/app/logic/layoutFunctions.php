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
    $currentPage = basename($_SERVER['PHP_SELF']);

    // Define menu items for the left and right sections
    $leftMenuItems = [
        "index.php" => "Home",
        "cart.php" => "Cart",
    ];
    $rightMenuItems = [
        "login.php" => $isLoggedIn ? "Logout" : "Login",
    ];

    if (!$isLoggedIn) {
        $rightMenuItems["register.php"] = "Register";
    }
    else {
        $leftMenuItems["orders.php"] = "Orders";
        $leftmenuItems["profile.php"] = "Profile";
    }

    echo <<<HTML
<header class="mb-4">
    <nav class="navbar navbar-expand-lg navbar-danger bg-danger">
        <div class="container">
            <a class="navbar-brand text-white" href="index.php">Pizzeria Sole Machina 🍕</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
HTML;

    // Add left menu items
    foreach ($leftMenuItems as $file => $name) {
        $activeClass = $file === $currentPage ? "active" : "";
        echo "<li class='nav-item'><a class='nav-link text-white $activeClass' href='$file'>$name</a></li>";
    }

    // Add right menu items
    echo <<<HTML
                </ul>
                <ul class="navbar-nav ms-auto">
HTML;

    foreach ($rightMenuItems as $file => $name) {
        $activeClass = $file === $currentPage ? "active" : "";
        $url = ($file === "login.php" && $isLoggedIn) ? "../logic/logout.php" : $file;
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
<footer class="bg-dark text-white text-center py-3">
    <p>&copy; 2025 Pizzeria Sole Machina 🍕. All rights reserved.</p>
    <a href="privacy-policy.php" class="text-white text-decoration-underline">Privacy Policy</a>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
HTML;
}
?>
