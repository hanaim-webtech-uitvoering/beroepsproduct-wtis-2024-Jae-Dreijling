<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../logic/layoutFunctions.php';

$error_message = $_SESSION['error_message'] ?? null;
unset($_SESSION['error_message']);


createHead();
createHeader("Inloggen als klant")
?>

<body class="d-flex flex-column min-vh-100">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h3 class="text-center mb-4">Inloggen als Klant</h3>
                <?php if ($error_message): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error_message) ?></div>
                <?php endif; ?>
                <form action="/app/logic/userFunctions.php" method="post">
                    <input type="hidden" name="action" value="login">
                    <input type="hidden" name="role" value="Client">

                    <div class="mb-3">
                        <label for="username" class="form-label">Gebruikersnaam</label>
                        <input type="text" id="username" name="username" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Wachtwoord</label>
                        <input type="password" id="password" name="password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Inloggen</button>
                </form>
                <p class="mt-3 text-center">
                    Heb je nog geen account? <a href="register.php">Registreer hier</a>.
                </p>
                <p class="text-center">
                    Ben je medewerker? <a href="workerLogin.php">Log hier in</a>.
                </p>
            </div>
        </div>
    </div>

<?php createFooter(); ?>
