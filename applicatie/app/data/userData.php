<?php

require_once __DIR__ . '/../config/db_connectie.php';

class User {
    public function getUser(string $username, string $role): ?array {
        try {
            $db = createConnection();
    
            $stmt = $db->prepare("
                SELECT username, password, role, address
                FROM [User] 
                WHERE username = ? AND role = ?
            ");
            $stmt->execute([$username, $role]);
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        } catch (PDOException $e) {
            throw new Exception("Database error while fetching user: " . $e->getMessage());
        }
    }
    
    
    function doesUsernameExist($username) {
        try {
            $db = createConnection();
            $stmt = $db->prepare("SELECT COUNT(*) FROM [User] WHERE username = ?");
            $stmt->execute([$username]);
            return $stmt->fetchColumn() > 0;
        } catch (Exception $e) {
            throw new Exception("Error checking if username exists: " . $e->getMessage());
        }
    }
    
    function registerNewUser($username, $password, $firstName, $lastName, $address, $role) {
        try {
            $db = createConnection();
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
            $stmt = $db->prepare("
                INSERT INTO [User] (username, password, first_name, last_name, address, role) 
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([$username, $hashedPassword, $firstName, $lastName, $address, $role]);
        } catch (Exception $e) {
            throw new Exception("Error registering new user: " . $e->getMessage());
        }
    }
    
    function getClientDetails($username) {
        try {
            $db = createConnection();
            $stmt = $db->prepare("
                SELECT first_name, last_name, address 
                FROM [User] 
                WHERE username = ?
            ");
            $stmt->execute([$username]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            throw new Exception("Error fetching client details: " . $e->getMessage());
        }
    }
    
    function getClientOrders($clientUsername) {
        try {
            $db = createConnection();
    
            $stmt = $db->prepare("
                SELECT 
                    po.order_id, 
                    po.datetime, 
                    po.status,
                    STRING_AGG(CONCAT(pop.product_name, ' (', pop.quantity, ')'), ', ') AS products
                FROM 
                    Pizza_Order po
                LEFT JOIN 
                    Pizza_Order_Product pop ON po.order_id = pop.order_id
                WHERE 
                    po.client_username = ?
                GROUP BY 
                    po.order_id, po.datetime, po.status
                ORDER BY 
                    po.datetime DESC
            ");
            $stmt->execute([$clientUsername]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            throw new Exception("Error fetching client orders: " . $e->getMessage());
        }
    }
    
    function updateClientDetails($username, $firstName, $lastName, $address) {
        try {
            $db = createConnection();
            $stmt = $db->prepare("
                UPDATE [User]
                SET first_name = ?, last_name = ?, address = ?
                WHERE username = ?
            ");
            $stmt->execute([$firstName, $lastName, $address, $username]);
        } catch (Exception $e) {
            throw new Exception("Error updating client details: " . $e->getMessage());
        }
    }
    
    function updatePassword($username, $hashedPassword) {
        try {
            $db = createConnection();
            $stmt = $db->prepare("UPDATE [User] SET password = ? WHERE username = ?");
            $stmt->execute([$hashedPassword, $username]);
        } catch (Exception $e) {
            throw new Exception("Error updating password: " . $e->getMessage());
        }
    }

}


?>
