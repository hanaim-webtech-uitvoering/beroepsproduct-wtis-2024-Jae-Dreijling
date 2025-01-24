<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../logic/layoutFunctions.php';
createHead();
createHeader("cart");
?>
<body class="d-flex flex-column min-vh-100">
    <div class="container">
        <h1 class="my-4 text-center">Your Cart</h1>

        <?php if (!empty($cart)): ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Quantity</th>
                        <th>Price (per unit)</th>
                        <th>Total Price</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $total = 0;
                    foreach ($cart as $item): 
                        $itemTotal = $item['price'] * $item['quantity'];
                        $total += $itemTotal;
                    ?>
                        <tr>
                            <td><?= htmlspecialchars($item['product_name']); ?></td>
                            <td><?= $item['quantity']; ?></td>
                            <td>$<?= number_format($item['price'], 2); ?></td>
                            <td>$<?= number_format($itemTotal, 2); ?></td>
                            <td>
                                <form method="POST" action="/cart">
                                <input type="hidden" name="action" value="remove_from_cart">
                                    <input type="hidden" name="product_name" value="<?= htmlspecialchars($item['product_name']); ?>">
                                    <button type="submit" name="remove_from_cart" class="btn btn-danger btn-sm">Remove</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3"><strong>Total:</strong></td>
                        <td><strong>$<?= number_format($total, 2); ?></strong></td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
            <form method="POST" action="/app/logic/orderFunctions.php">
            <div class="mb-3">
                    <label for="address" class="form-label">Delivery Address</label>
                    <input 
                        type="text" 
                        class="form-control" 
                        id="address" 
                        name="address" 
                        value="<?= htmlspecialchars($_SESSION['user_address'] ?? '') ?>" 
                        placeholder="Enter your delivery address" 
                        required>
                </div>
                <input type="hidden" name="action" value="order_cart">
                <button type="submit" name="checkout" class="btn btn-danger w-100">Proceed to Checkout</button>
            </form>
        <?php else: ?>
            <p class="text-center">Your cart is empty. <a href="/menu">Go to the menu</a> to add some items!</p>
        <?php endif; ?>
    </div>
<?php 
createFooter();
?>
