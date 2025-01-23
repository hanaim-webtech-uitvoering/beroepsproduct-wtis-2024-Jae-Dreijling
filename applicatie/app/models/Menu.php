<?php
require_once __DIR__ . '/../config/db_connectie.php';

class Menu {
    public static function getAllItems() {
        $db = maakVerbinding();
        $stmt = $db->query("SELECT * FROM Product");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
