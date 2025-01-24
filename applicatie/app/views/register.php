<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


require_once __DIR__ . '/../logic/layoutFunctions.php';


createHead();
createHeader("Inloggen als klant")
?>

<body class="d-flex flex-column min-vh-100">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h3 class="text-center mb-4">Registreren als Klant</h3>
                <?php if (isset($_GET['error'])): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($_GET['error']) ?></div>
                <?php endif; ?>
                <form action="/app/logic/userFunctions.php" method="post">
                    <input type="hidden" name="action" value="register">
                    <input type="hidden" name="role" value="Client">

                    <div class="mb-3">
                        <label for="username" class="form-label">Gebruikersnaam</label>
                        <input type="text" id="username" name="username" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Wachtwoord</label>
                        <input type="password" id="password" name="password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="first_name" class="form-label">Voornaam</label>
                        <input type="text" id="first_name" name="first_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="last_name" class="form-label">Achternaam</label>
                        <input type="text" id="last_name" name="last_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Adres</label>
                        <input type="text" id="address" name="address" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-danger w-100">Registreren</button>
                </form>
            </div>
        </div>
    </div>

<?php createFooter(); ?>
