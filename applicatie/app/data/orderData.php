<?php
require_once '../config/db_connect.php';

/**
 * Add a new order to the system.
 */
function addOrder($customerName, $address, $products, $clientUsername = null) {
    try {
        $db = connectToDatabase();
        $db->beginTransaction();

        // Assign a random staff member to the order
        $stmtStaff = $db->prepare("
            SELECT TOP 1 username 
            FROM [User] 
            WHERE role = 'Personnel' 
            ORDER BY NEWID()
        ");
        $stmtStaff->execute();
        $staffMember = $stmtStaff->fetch(PDO::FETCH_ASSOC);

        if (!$staffMember) {
            throw new Exception("No available staff member found.");
        }

        $staffUsername = $staffMember['username'];
        $initialStatus = 'New';

        // Insert the order into the database
        $stmt = $db->prepare("
            INSERT INTO Pizza_Order (client_name, address, datetime, status, personnel_username) 
            VALUES (?, ?, GETDATE(), ?, ?)
        ");
        $stmt->execute([$customerName, $address, $initialStatus, $staffUsername]);
        $orderId = $db->lastInsertId();

        // Insert the products for the order
        $stmtProduct = $db->prepare("
            INSERT INTO Pizza_Order_Product (order_id, product_name, quantity) 
            VALUES (?, ?, ?)
        ");
        foreach ($products as $product) {
            $stmtProduct->execute([$orderId, $product['name'], $product['quantity']]);
        }

        $db->commit();
        return $orderId;
    } catch (Exception $e) {
        $db->rollBack();
        throw new Exception("Error adding the order: " . $e->getMessage());
    }
}

/**
 * Retrieve a sorted overview of orders.
 */
function getSortedOrdersOverview($sortColumn, $sortOrder) {
    try {
        $db = connectToDatabase();

        $allowedSortColumns = ['order_id', 'datetime', 'status', 'personnel_username'];
        if (!in_array($sortColumn, $allowedSortColumns)) {
            $sortColumn = 'datetime'; // Default column
        }

        $sortOrder = strtolower($sortOrder) === 'asc' ? 'ASC' : 'DESC';

        $stmt = $db->prepare("
            SELECT 
                po.order_id, 
                po.client_name, 
                po.address, 
                po.datetime, 
                po.status, 
                po.personnel_username,
                STRING_AGG(CONCAT(pop.product_name, ' (', pop.quantity, ')'), ', ') AS products
            FROM 
                Pizza_Order po
            LEFT JOIN 
                Pizza_Order_Product pop ON po.order_id = pop.order_id
            GROUP BY 
                po.order_id, po.client_name, po.address, po.datetime, po.status, po.personnel_username
            ORDER BY 
                $sortColumn $sortOrder
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        throw new Exception("Error retrieving sorted orders: " . $e->getMessage());
    }
}

/**
 * Retrieve all orders.
 */
function getAllOrders() {
    try {
        $db = connectToDatabase();
        $stmt = $db->prepare("
            SELECT 
                po.order_id, 
                po.client_name, 
                po.address, 
                po.datetime, 
                po.status, 
                po.personnel_username,
                STRING_AGG(CONCAT(pop.product_name, ' (', pop.quantity, ')'), ', ') AS products
            FROM 
                Pizza_Order po
            LEFT JOIN 
                Pizza_Order_Product pop ON po.order_id = pop.order_id
            GROUP BY 
                po.order_id, po.client_name, po.address, po.datetime, po.status, po.personnel_username
            ORDER BY 
                po.datetime DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        throw new Exception("Error retrieving orders: " . $e->getMessage());
    }
}

/**
 * Retrieve details of a specific order.
 */
function getOrderDetails($orderId) {
    try {
        $db = connectToDatabase();

        // Fetch the order details
        $stmt = $db->prepare("
            SELECT 
                po.order_id,
                po.client_name,
                po.address,
                po.datetime,
                po.status,
                po.personnel_username
            FROM 
                Pizza_Order po
            WHERE 
                po.order_id = ?
        ");
        $stmt->execute([$orderId]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$order) {
            throw new Exception("No order found with ID: $orderId");
        }

        // Fetch the products associated with the order
        $stmtProducts = $db->prepare("
            SELECT 
                product_name AS name,
                quantity 
            FROM 
                Pizza_Order_Product 
            WHERE 
                order_id = ?
        ");
        $stmtProducts->execute([$orderId]);
        $order['products'] = $stmtProducts->fetchAll(PDO::FETCH_ASSOC);

        return $order;
    } catch (Exception $e) {
        throw new Exception("Error retrieving order details: " . $e->getMessage());
    }
}

/**
 * Update the status of an order.
 */
function updateOrderStatus($orderId, $status) {
    try {
        $db = connectToDatabase();
        $stmt = $db->prepare("UPDATE Pizza_Order SET status = ? WHERE order_id = ?");
        $stmt->execute([$status, $orderId]);
    } catch (Exception $e) {
        throw new Exception("Error updating order status: " . $e->getMessage());
    }
}

/**
 * Update the assigned staff member for an order.
 */
function updateAssignedStaff($orderId, $username) {
    try {
        $db = connectToDatabase();
        $stmt = $db->prepare("UPDATE Pizza_Order SET personnel_username = ? WHERE order_id = ?");
        $stmt->execute([$username, $orderId]);
    } catch (Exception $e) {
        throw new Exception("Error updating assigned staff: " . $e->getMessage());
    }
}

/**
 * Retrieve all staff members.
 */
function getAllStaffMembers() {
    try {
        $db = connectToDatabase();
        $stmt = $db->prepare("SELECT username FROM [User] WHERE role = 'Personnel'");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        throw new Exception("Error retrieving staff members: " . $e->getMessage());
    }
}
?>
