<?php
require_once '../config/db_connectie.php';

class User {
    // Register a new user
    public static function register($username, $password, $email) {
        $db = Database::connect();

        // Check if the username or email already exists
        $stmt = $db->prepare("SELECT COUNT(*) FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $email]);
        if ($stmt->fetchColumn() > 0) {
            throw new Exception('Username or email already exists.');
        }

        // Insert new user into the database
        $stmt = $db->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");
        $stmt->execute([$username, $password, $email]);
    }

    // Get user by username
    public static function getByUsername($username) {
        $db = Database::connect();
        $stmt = $db->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Update user information
    public static function update($userId, $newUsername, $newEmail, $newPassword = null) {
        $db = Database::connect();

        // Build query dynamically to allow optional password update
        $query = "UPDATE users SET username = ?, email = ?";
        $params = [$newUsername, $newEmail];

        if ($newPassword) {
            $query .= ", password = ?";
            $params[] = password_hash($newPassword, PASSWORD_BCRYPT);
        }

        $query .= " WHERE id = ?";
        $params[] = $userId;

        $stmt = $db->prepare($query);
        $stmt->execute($params);
    }

    // Get user by ID (useful for profiles)
    public static function getById($userId) {
        $db = Database::connect();
        $stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
