<?php

require_once __DIR__ . '/../config/db_connectie.php';

class Menu {
    public static function getAllItems(): array {
        try {
            $db = createConnection();

            $query = "
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
                GROUP BY 
                    p.name, p.price
            ";

            $stmt = $db->query($query);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Database error while fetching all items: " . $e->getMessage());
        }
    }

    public function addProduct(string $name, float $price, string $typeId): void {
        try {
            $db = createConnection();

            // check if the product type actually exists
            $stmtCheckType = $db->prepare("SELECT COUNT(*) FROM ProductType WHERE name = ?");
            $stmtCheckType->execute([$typeId]);
            if ($stmtCheckType->fetchColumn() == 0) {
                throw new Exception("Product type '$typeId' does not exist.");
            }

            // insert into the database after checking.
            $stmt = $db->prepare("INSERT INTO Product (name, price, type_id) VALUES (?, ?, ?)");
            $stmt->execute([$name, $price, $typeId]);
        } catch (PDOException $e) {
            throw new Exception("Database error while adding product: " . $e->getMessage());
        }
    }

    public function deleteProduct(string $name): void {
        try {
            $db = createConnection();

            // Verify if the product exists
            $stmtCheckProduct = $db->prepare("SELECT COUNT(*) FROM Product WHERE name = ?");
            $stmtCheckProduct->execute([$name]);
            if ($stmtCheckProduct->fetchColumn() == 0) {
                throw new Exception("Product '$name' does not exist.");
            }

            // Delete the product
            $stmt = $db->prepare("DELETE FROM Product WHERE name = ?");
            $stmt->execute([$name]);
        } catch (PDOException $e) {
            throw new Exception("Database error while deleting product: " . $e->getMessage());
        }
    }
}
?>
