<?php
require_once '../config/database.php';

class Product {
    public static function add($name, $price, $image_url, $added_by) {
        global $pdo;
        $stmt = $pdo->prepare("INSERT INTO products (name, price, image_url, added_by) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$name, $price, $image_url, $added_by]);
    }

    public static function all() {
        global $pdo;
        return $pdo->query("SELECT * FROM products")->fetchAll();
    }
}
?>