<?php
require_once __DIR__ . '/../models/Menu.php';


class MenuController {
    public function displayMenu() {
        $menu = Menu::getAllItems();
        require __DIR__ . '/../views/menu.php';
    }
}
?>
