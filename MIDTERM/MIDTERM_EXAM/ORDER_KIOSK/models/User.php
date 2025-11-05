<?php
require_once '../config/database.php';
require_once '../controllers/AuthController.php';

class User {
    public static function findByUsername($username) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        return $stmt->fetch();
    }

    public static function create($username, $password, $role) {
        global $pdo;
        $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
        return $stmt->execute([$username, password_hash($password, PASSWORD_DEFAULT), $role]);
    }

    public static function suspend($id) {
        global $pdo;
        $stmt = $pdo->prepare("UPDATE users SET status = 'suspended' WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
?>