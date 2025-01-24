<?php
require_once __DIR__ . '/../logic/layoutFunctions.php';

$statusLabels = ['Nieuw', 'In de oven', 'Onderweg', 'Afgeleverd'];

$title = "Orders | Pizzeria Sole Machina 🍕";


createHead($title);
createHeader("All Orders");
?>

<div class="container my-4">
    <h1 class="text">All Orders</h1>

    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="alert alert-success">
            <?= htmlspecialchars($_SESSION['success_message']) ?>
        </div>
        <?php unset($_SESSION['success_message']); ?>
    <?php endif; ?>

    <?php if (isset($errorMessage)): ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($errorMessage) ?>
        </div>
    <?php endif; ?>

    <table class="table table-bordered table-striped">
        <thead class="table-danger">
            <tr>
                <th>Order ID</th>
                <th>Client Name</th>
                <th>Products</th>
                <th>Status</th>
                <th>Date/Time</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($orders)): ?>
                <?php foreach ($orders as $order): ?>
                    <?php $formattedDateTime = (new DateTime($order['datetime']))->format('Y-m-d H:i'); ?>
                    <tr>
                        <td><?= htmlspecialchars($order['order_id']) ?></td>
                        <td><?= htmlspecialchars($order['client_name']) ?></td>
                        <td><?= htmlspecialchars($order['products']) ?></td>
                        <td><?= htmlspecialchars($statusLabels[$order['status']] ?? 'Unknown') ?></td>
                        <td><?= htmlspecialchars($formattedDateTime) ?></td>
                        <td>
                            <form method="POST" action="/app/logic/orderFunctions.php" class="d-inline-block">
                                <input type="hidden" name="order_id" value="<?= htmlspecialchars($order['order_id']) ?>">
                                <input type="hidden" name="current_status" value="<?= htmlspecialchars($order['status']) ?>">
                                <button type="submit" name="action" value="previous" class="btn btn-warning btn-sm" <?= $order['status'] <= 0 ? 'disabled' : '' ?>>
                                    Previous Step
                                </button>
                            </form>
                            <form method="POST" action="/app/logic/orderFunctions.php" class="d-inline-block">
                                <input type="hidden" name="order_id" value="<?= htmlspecialchars($order['order_id']) ?>">
                                <input type="hidden" name="current_status" value="<?= htmlspecialchars($order['status']) ?>">
                                <button type="submit" name="action" value="next" class="btn btn-success btn-sm" <?= $order['status'] >= 3 ? 'disabled' : '' ?>>
                                    Next Step
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" class="text-center">No orders found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php
createFooter();
?>
