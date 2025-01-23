<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu</title>
    <link href="/public/css/styles.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1 class="my-4 text-center">Menu</h1>
        <div class="row">
            <?php foreach ($menu as $item): ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($item['name']); ?></h5>
                            <p class="card-text"><?= htmlspecialchars($item['description']); ?></p>
                            <p class="card-text"><strong>$<?= htmlspecialchars($item['price']); ?></strong></p>
                            <form method="POST" action="/public/index.php">
                                <input type="hidden" name="menu_item_id" value="<?= $item['id']; ?>">
                                <input type="number" name="quantity" min="1" max="10" class="form-control mb-2" required>
                                <button type="submit" name="add_to_cart" class="btn btn-primary w-100">Add to Cart</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
