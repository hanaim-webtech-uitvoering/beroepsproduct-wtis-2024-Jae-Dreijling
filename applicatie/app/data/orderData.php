<?php

require_once __DIR__ . '/../config/db_connectie.php';

class Order {
    public static function getProductDetails(string $productName): ?array {
        try {
            $db = createConnection();

            $stmt = $db->prepare("
                SELECT 
                    p.name AS product_name, 
                    p.price,
                    COALESCE(STRING_AGG(i.name, ', '), 'Unknown') AS ingredients
                FROM 
                    Product p
                LEFT JOIN 
                    Product_Ingredient pi ON p.name = pi.product_name
                LEFT JOIN 
                    Ingredient i ON pi.ingredient_name = i.name
                WHERE 
                    p.name = ?
                GROUP BY 
                    p.name, p.price
            ");
            $stmt->execute([$productName]);

            return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        } catch (PDOException $e) {
            throw new Exception("Database error while fetching product details: " . $e->getMessage());
        }
    }

    public static function addOrder(string $clientUsername, string $clientName, string $address, array $cart): int {
        try {
            $db = createConnection();

            $stmtPersonnel = $db->prepare("
                SELECT TOP 1 username 
                FROM [User] 
                WHERE role = 'Personnel' 
                ORDER BY NEWID()
            ");
            $stmtPersonnel->execute();
            $personnel = $stmtPersonnel->fetch(PDO::FETCH_ASSOC);

            if (!$personnel) {
                throw new Exception("No personnel available to handle the order.");
            }

            $personnelUsername = $personnel['username'];

            $stmtOrder = $db->prepare("
                INSERT INTO Pizza_Order (client_username, client_name, address, personnel_username, datetime, status) 
                VALUES (?, ?, ?, ?, GETDATE(), ?)
            ");
            $stmtOrder->execute([
                $clientUsername, 
                $clientName, 
                $address, 
                $personnelUsername, 
                0 // Status: New
            ]);
            $orderId = $db->lastInsertId();

            $stmtProduct = $db->prepare("
                INSERT INTO Pizza_Order_Product (order_id, product_name, quantity) 
                VALUES (?, ?, ?)
            ");

            foreach ($cart as $item) {
                $stmtProduct->execute([$orderId, $item['product_name'], $item['quantity']]);
            }

            $db->commit();
            return $orderId;
        } catch (PDOException $e) {
            $db->rollBack();
            throw new Exception("Database error while adding order: " . $e->getMessage());
        }
    }

    public static function getUserOrders(string $username): array {
        try {
            $db = createConnection();

            $stmt = $db->prepare("
                SELECT 
                    po.order_id, 
                    po.datetime, 
                    po.status, 
                    po.address,
                    COALESCE(STRING_AGG(CONCAT(pop.product_name, ' (', pop.quantity, ')'), ', '), 'No products') AS products
                FROM 
                    Pizza_Order po
                LEFT JOIN 
                    Pizza_Order_Product pop ON po.order_id = pop.order_id
                WHERE 
                    po.client_username = ?
                GROUP BY 
                    po.order_id, po.datetime, po.status, po.address
                ORDER BY 
                    po.datetime DESC
            ");
            $stmt->execute([$username]);

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Database error while fetching user orders: " . $e->getMessage());
        }
    }

    public static function getAllOrders(): array {
        try {
            $db = createConnection();
    
            $stmt = $db->prepare("
                SELECT 
                    po.order_id, 
                    po.client_username, 
                    po.client_name, 
                    po.address,
                    po.datetime, 
                    po.status, 
                    COALESCE(STRING_AGG(CONCAT(pop.product_name, ' (', pop.quantity, ')'), ', '), 'No products') AS products
                FROM 
                    Pizza_Order po
                LEFT JOIN 
                    Pizza_Order_Product pop ON po.order_id = pop.order_id
                GROUP BY 
                    po.order_id, 
                    po.client_username, 
                    po.client_name, 
                    po.address, 
                    po.datetime, 
                    po.status
                ORDER BY 
                    po.datetime DESC
            ");
            $stmt->execute();
    
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Database error while fetching all orders: " . $e->getMessage());
        }
    }
    
    public static function updateOrderStatus(int $orderId, int $newStatus): void {
        try {
            $db = createConnection();

            $stmt = $db->prepare("UPDATE Pizza_Order SET status = ? WHERE order_id = ?");
            $stmt->execute([$newStatus, $orderId]);
        } catch (PDOException $e) {
            throw new Exception("Database error while updating order status: " . $e->getMessage());
        }
    }


}
