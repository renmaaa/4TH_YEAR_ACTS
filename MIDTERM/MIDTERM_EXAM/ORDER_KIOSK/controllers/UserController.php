<?php
session_start();
require_once '../config/database.php';

// Only superadmins can manage users
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'superadmin') {
  $_SESSION['error'] = "Unauthorized access.";
  header('Location: ../views/login.php');
  exit();
}

// Suspend admin logic
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['suspend_id'])) {
  $suspendId = $_POST['suspend_id'];

  // Prevent suspending superadmin or invalid ID
  $stmt = $pdo->prepare("SELECT role FROM users WHERE id = ?");
  $stmt->execute([$suspendId]);
  $target = $stmt->fetch();

  if (!$target || $target['role'] !== 'admin') {
    $_SESSION['error'] = "Invalid user or cannot suspend this account.";
    header('Location: ../views/users.php');
    exit();
  }

  $stmt = $pdo->prepare("UPDATE users SET status = 'suspended' WHERE id = ?");
  $success = $stmt->execute([$suspendId]);

  $_SESSION[$success ? 'success' : 'error'] = $success
    ? "Admin account suspended successfully."
    : "Failed to suspend admin account.";

  header('Location: ../views/users.php');
  exit();
}

// Create admin logic
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username'], $_POST['password'])) {
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
    $_SESSION['error'] = "Username already exists.";
    header('Location: ../views/users.php');
    exit();
  }

  $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
  $stmt = $pdo->prepare("INSERT INTO users (username, password, role, status, date_added) VALUES (?, ?, 'admin', 'active', NOW())");
  $success = $stmt->execute([$username, $hashedPassword]);

  $_SESSION[$success ? 'success' : 'error'] = $success
    ? "Admin user created successfully."
    : "Failed to create user.";

  header('Location: ../views/users.php');
  exit();
}
?>