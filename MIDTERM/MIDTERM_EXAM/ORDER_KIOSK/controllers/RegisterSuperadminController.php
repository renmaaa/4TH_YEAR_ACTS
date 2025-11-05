<?php
require_once '../models/User.php';
session_start();

global $pdo;
$stmt = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'superadmin'");
$exists = $stmt->fetchColumn();

if ($exists > 0) {
  $_SESSION['error'] = "Superadmin already exists.";
  header('Location: ../views/login.php');
  exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = $_POST['username'];
  $password = $_POST['password'];

  $success = User::create($username, $password, 'superadmin');
  if ($success) {
    $_SESSION['message'] = "Superadmin created successfully.";
    header('Location: ../views/login.php');
  } else {
    $_SESSION['error'] = "Failed to create superadmin.";
    header('Location: ../views/register_superadmin.php');
  }
}
?>