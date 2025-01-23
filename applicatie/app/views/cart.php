<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <link href="/public/css/styles.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1 class="my-4 text-center">Shopping Cart</h1>
        <?php if (!empty($cart)): ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Total</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cart as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['name']); ?></td>
                            <td><?= htmlspecialchars($item['quantity']); ?></td>
                            <td>$<?= htmlspecialchars($item['price']); ?></td>
                            <td>$<?= htmlspecialchars($item['quantity'] * $item['price']); ?></td>
                            <td>
                                <form method="POST" action="/public/index.php">
                                    <input type="hidden" name="product_name" value="<?= htmlspecialchars($item['name']); ?>">
                                    <button type="submit" name="remove_from_cart" class="btn btn-danger btn-sm">Remove</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div class="text-end">
                <p><strong>Total:</strong> $<?= $cart_total; ?></p>
                <a href="/app/views/checkout.php" class="btn btn-success">Proceed to Checkout</a>
            </div>
        <?php else: ?>
            <p class="text-center">Your cart is empty.</p>
        <?php endif; ?>
    </div>
</body>
</html>
