<?php
require_once '../models/User.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = User::findByUsername($_POST['username']);
    if ($user && password_verify($_POST['password'], $user['password']) && $user['status'] === 'active') {
        $_SESSION['user'] = $user;
        header('Location: ../views/dashboard.php');
    } else {
        echo "Invalid credentials or suspended account.";
    }
}
?>