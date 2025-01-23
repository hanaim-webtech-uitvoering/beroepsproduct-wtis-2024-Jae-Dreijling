<?php
require_once '../models/User.php';
require_once '../helpers/Validation.php';
require_once '../helpers/Session.php';
require_once '../models/Order.php';

class AuthController {
    public function register($username, $password, $email) {
        if (Validation::validateRegistration($username, $password, $email)) {
            User::register($username, password_hash($password, PASSWORD_BCRYPT), $email);
            header('Location: ../views/login.php');
        }
    }

    public function login($username, $password) {
        $user = User::getByUsername($username);
        if ($user && password_verify($password, $user['password'])) {
            Session::start($user['id']);
            header('Location: ../views/dashboard.php');
        }
    }
}
?>
