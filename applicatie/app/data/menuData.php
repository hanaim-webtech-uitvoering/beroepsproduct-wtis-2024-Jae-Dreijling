<?php

require_once __DIR__ . '/../config/db_connectie.php';

class Menu {
    public static function getAllItems() {
        // $db = maakVerbinding();
        // $stmt = $db->query("SELECT * FROM Product");
        // return $stmt->fetchAll(PDO::FETCH_ASSOC);
        $db = maakVerbinding();

        $query = "SELECT 
            p.name AS product_name, 
            p.price, 
            COALESCE(STRING_AGG(i.name, ', '), 'Unknown') AS ingredients
            FROM Product p
            LEFT JOIN Product_Ingredient pi ON p.name = pi.product_name
            LEFT JOIN Ingredient i ON pi.ingredient_name = i.name
            GROUP BY p.name, p.price";


        $stmt = $db->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>