<?php
require_once '../models/Order.php';

class StatusController {
    public function updateStatus($orderId, $status) {
        Order::updateStatus($orderId, $status);
    }
}
?>
