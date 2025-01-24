<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../logic/layoutFunctions.php';
createHead();
createHeader("Profiel");
?>
<body class="d-flex flex-column min-vh-100">
    <div class="container flex-grow-1">
        <h1 class="my-4 text-center">Uw Profiel</h1>

        <?php if (!empty($successMessage)): ?>
            <div class="alert alert-success">
                <?= htmlspecialchars($successMessage) ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($errorMessage)): ?>
            <div class="alert alert-danger">
                <?= htmlspecialchars($errorMessage) ?>
            </div>
        <?php endif; ?>

        <h2 class="my-4">Uw Gegevens</h2>
        <form method="POST" class="mb-4">
            <div class="mb-3">
                <label for="username" class="form-label">Gebruikersnaam</label>
                <input type="text" id="username" class="form-control" value="<?= htmlspecialchars($username) ?>" readonly disabled>
            </div>
            <div class="mb-3">
                <label for="first_name" class="form-label">Voornaam</label>
                <input type="text" id="first_name" name="first_name" class="form-control" value="<?= htmlspecialchars($clientDetails['first_name'] ?? '') ?>" required>
            </div>
            <div class="mb-3">
                <label for="last_name" class="form-label">Achternaam</label>
                <input type="text" id="last_name" name="last_name" class="form-control" value="<?= htmlspecialchars($clientDetails['last_name'] ?? '') ?>" required>
            </div>
            <div class="mb-3">
                <label for="address" class="form-label">Adres</label>
                <input type="text" id="address" name="address" class="form-control" value="<?= htmlspecialchars($clientDetails['address'] ?? '') ?>" required>
            </div>
            <button type="submit" class="btn btn-danger w-100">Opslaan</button>
        </form>

        <h2 class="my-4">Uw Bestellingen</h2>
        <?php if (!empty($clientOrders)): ?>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Ordernummer</th>
                            <th>Inhoud</th>
                            <th>Status</th>
                            <th>Datum en Tijd</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($clientOrders as $order): ?>
                            <?php
                            $formattedDateTime = (new DateTime($order['datetime']))->format('Y-m-d H:i');
                            $statusLabels = ['Nieuw', 'In de oven', 'Onderweg', 'Afgeleverd'];
                            ?>
                            <tr>
                                <td><?= htmlspecialchars($order['order_id']) ?></td>
                                <td><?= htmlspecialchars($order['products']) ?></td>
                                <td><?= htmlspecialchars($statusLabels[$order['status']] ?? 'Onbekend') ?></td>
                                <td><?= htmlspecialchars($formattedDateTime) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p class="text-muted">U heeft nog geen bestellingen geplaatst.</p>
        <?php endif; ?>

        <a href="/menu" class="btn btn-secondary my-4">Terug naar menu</a>
    </div>

<?php 
createFooter();
?>
