<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../logic/layoutFunctions.php';
createHead();
createHeader("menu");
?>
<body class="d-flex flex-column min-vh-100">
    <div class="container">
        <h1 class="my-4 text-center">Menu</h1>
        <?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success text-center">
        <?= htmlspecialchars($_GET['success']); ?>
    </div>
<?php elseif (isset($_GET['error'])): ?>
    <div class="alert alert-danger text-center">
        <?= htmlspecialchars($_GET['error']); ?>
    </div>
<?php endif; ?>
        <div class="row">
            <?php foreach ($menu as $product): ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($product['product_name']); ?></h5>
                            <p class="card-text">
                                <strong>Ingredienten:</strong> <?= htmlspecialchars($product['ingredients']); ?>
                            </p>
                            <p class="card-text">
                                <strong>Prijs: €<?= number_format($product['price'], 2); ?></strong>
                            </p>
                            <form method="POST" action="/app/logic/orderFunctions.php">
                                <input type="hidden" name="action" value="add_to_cart">
                                <input type="hidden" name="product_name" value="<?= htmlspecialchars($product['product_name']); ?>">
                                <input type="number" name="quantity" min="1" max="10" class="form-control mb-2" required>
                                <button type="submit" name="add_to_cart" class="btn btn-danger w-100">Voeg toe aan winkelwagentje</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php 
createFooter();
?>
