<?php
require_once '../config/database.php';

class Order {
    public static function add($product_id, $quantity, $total_price, $ordered_by) {
        global $pdo;
        $stmt = $pdo->prepare("INSERT INTO orders (product_id, quantity, total_price, ordered_by) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$product_id, $quantity, $total_price, $ordered_by]);
    }

    public static function filter($start, $end) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM orders WHERE date_added BETWEEN ? AND ?");
        $stmt->execute([$start, $end]);
        return $stmt->fetchAll();
    }

    public static function total($start, $end) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT SUM(total_price) as total FROM orders WHERE date_added BETWEEN ? AND ?");
        $stmt->execute([$start, $end]);
        return $stmt->fetch()['total'];
    }
}
?>