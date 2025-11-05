<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'superadmin') {
  $_SESSION['error'] = "Unauthorized access.";
  header('Location: ../views/login.php');
  exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = trim($_POST['username']);
  $password = $_POST['password'];

  if (empty($username) || empty($password)) {
    $_SESSION['error'] = "All fields are required.";
    header('Location: ../views/users.php');
    exit();
  }

  $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
  $stmt->execute([$username]);
  if ($stmt->fetchColumn() > 0) {
    $_SESSION['error'] = "Username already taken.";
    header('Location: ../views/users.php');
    exit();
  }

  $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
  $stmt = $pdo->prepare("INSERT INTO users (username, password, role, status, date_added) VALUES (?, ?, 'admin', 'active', NOW())");
  $success = $stmt->execute([$username, $hashedPassword]);

  if ($success) {
    $_SESSION['message'] = "Admin account created successfully.";
  } else {
    $_SESSION['error'] = "Failed to create admin account.";
  }

  header('Location: ../views/users.php');
  exit();
}
?>