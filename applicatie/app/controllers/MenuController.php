<?php
require_once '../models/Menu.php';

class MenuController {
    public function displayMenu() {
        $menu = Menu::getAllItems();
        require '../views/menu.php';
    }
}
?>
