<?php
require_once __DIR__ . '/../data/userData.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_destroy();
    header("Location: ../presentatie/.php?logout=success");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? null;

    switch ($action) {
        case 'login':
            handleLogin();
            break;
        case 'register':
            handleRegister();
            break;
        default:
            redirectToError("Ongeldige actie.", "/app/views/login.php");
    }
} else {
    redirectToError("Ongeldig verzoek.", "/app/views/login.php");
}

function handleLogin() {
    if (!isset($_POST['username'], $_POST['password'], $_POST['role'])) {
        redirectToError("Vul alle velden in.", ($_POST['role'] ?? 'Client') === 'Personnel' ? "/workerLogin" : "/login.php");
    }

    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];
    

    try {
        $user = new User();
        $gebruiker = $user->getUser($username, $role);

        if ($gebruiker && password_verify($password, $gebruiker['password'])) {
            $_SESSION['username'] = $gebruiker['username'];
            $_SESSION['role'] = $gebruiker['role'];
            $_SESSION['user_address'] = $gebruiker['address'] ?? '';

            if ($role === 'Personnel') {
                header("Location: /beste");
            } else {
                header("Location: /menu");
            }
            exit;
        } else {
            redirectToError("Ongeldige gebruikersnaam of wachtwoord.", $role === 'Personnel' ? "/medewerkerLogin" : "/login");
        }
    } catch (Exception $e) {
        redirectToError("Er is een fout opgetreden bij het inloggen: " . $e->getMessage(), $role === 'Personnel' ? "/medewerkerLogin" : "/login");
    }
}


function handleRegister() {
    if (!isset($_POST['username'], $_POST['password'], $_POST['first_name'], $_POST['last_name'], $_POST['address'], $_POST['role'])) {
        redirectToError("Vul alle velden in.", "/app/views/register.php");
    }

    $username = $_POST['username'];
    $password = $_POST['password'];
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $address = $_POST['address'];
    $role = $_POST['role'];

    try {
        $user = new User(); 

        if ($user->doesUsernameExist($username)) {
            throw new Exception("De gebruikersnaam is al in gebruik. Kies een andere.");
        }

        $user->registerNewUser($username, $password, $firstName, $lastName, $address, $role);

        header("Location: /app/views/login.php?success=registered");
        exit;
    } catch (Exception $e) {
        redirectToError($e->getMessage(), "/app/views/register.php");
    }
}

function redirectToError($message, $location) {
    $_SESSION['error_message'] = $message;
    header("Location: $location");
    exit;
}
?>
