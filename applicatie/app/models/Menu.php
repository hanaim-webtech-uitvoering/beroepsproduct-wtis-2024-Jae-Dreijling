<?php
require_once '../config/db_connectie.php';

class Menu {
    public static function getAllItems() {
        $db = Database::connect();
        $stmt = $db->query("SELECT * FROM menu");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
