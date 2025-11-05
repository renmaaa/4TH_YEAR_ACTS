<?php
session_start();
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['product_id'])) {
  $_SESSION['error'] = "Invalid request.";
  header('Location: ../views/pos.php');
  exit();
}

$productId = $_POST['product_id'];

$stmt = $pdo->prepare("SELECT id, name, price FROM products WHERE id = ?");
$stmt->execute([$productId]);
$product = $stmt->fetch();

if (!$product) {
  $_SESSION['error'] = "Product not found.";
  header('Location: ../views/pos.php');
  exit();
}

$cart = $_SESSION['cart'] ?? [];

$cart[] = [
  'id' => $product['id'],
  'name' => $product['name'],
  'price' => $product['price'],
  'quantity' => 1
];

$_SESSION['cart'] = $cart;
$_SESSION['success'] = "{$product['name']} added to cart.";

header('Location: ../views/pos.php');
exit();