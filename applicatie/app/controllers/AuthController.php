<?php
require_once '../models/User.php';
require_once '../helpers/Validation.php';
require_once '../helpers/Session.php';

class AuthController {
    // Register a new user
    public function register($username, $password, $email) {
        if (Validation::validateRegistration($username, $password, $email)) {
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

            // Attempt to register the user
            try {
                User::register($username, $hashedPassword, $email);
                header('Location: ../views/login.php');
                exit;
            } catch (Exception $e) {
                // Handle registration errors (e.g., username already taken)
                $_SESSION['error'] = 'Registration failed: ' . $e->getMessage();
                header('Location: ../views/register.php');
                exit;
            }
        } else {
            $_SESSION['error'] = 'Invalid registration data.';
            header('Location: ../views/register.php');
            exit;
        }
    }

    // Log in an existing user
    public function login($username, $password) {
        $user = User::getByUsername($username);

        if ($user && password_verify($password, $user['password'])) {
            // Start a session and store user details
            Session::start($user['id']);
            $_SESSION['username'] = $user['username'];
            header('Location: ../views/dashboard.php');
            exit;
        } else {
            $_SESSION['error'] = 'Invalid username or password.';
            header('Location: ../views/login.php');
            exit;
        }
    }

    // Log out the user
    public function logout() {
        Session::destroy();
        header('Location: ../views/login.php');
        exit;
    }
}
