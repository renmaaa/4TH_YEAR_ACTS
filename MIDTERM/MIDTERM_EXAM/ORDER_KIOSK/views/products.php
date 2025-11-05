<?php
session_start();
include '../views/navbar.php';
require_once '../config/database.php';

$stmt = $pdo->query("SELECT * FROM products ORDER BY date_added DESC");
$products = $stmt->fetchAll();

$success = $_SESSION['success'] ?? null;
$error = $_SESSION['error'] ?? null;
unset($_SESSION['success'], $_SESSION['error']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Manage Products</title>
  <link rel="stylesheet" href="../public/css/style.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />
  <style>
    .product-card {
      transition: transform 0.2s ease;
    }
    .product-card:hover {
      transform: scale(1.02);
    }
    .product-image {
      height: 150px;
      object-fit: cover;
    }
  </style>
</head>
<body class="bg-light">
  <div class="container mt-5">
    <h2 class="mb-4">Add New Product</h2>

    <?php if ($success): ?>
      <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php elseif ($error): ?>
      <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" action="../controllers/ProductController.php" enctype="multipart/form-data" class="card p-4 shadow-sm mb-5">
      <div class="mb-3">
        <label for="name" class="form-label">Product Name</label>
        <input type="text" name="name" class="form-control" placeholder="Product Name" required />
      </div>
      <div class="mb-3">
        <label for="price" class="form-label">Price</label>
        <input type="number" name="price" class="form-control" placeholder="Price" required />
      </div>
      <div class="mb-3">
        <label for="image" class="form-label">Product Image</label>
        <input type="file" name="image" class="form-control" required />
      </div>
      <button type="submit" class="btn btn-primary w-100">Add Product</button>
    </form>

    <h4 class="mb-3">📦 Existing Products</h4>
    <div class="row g-3">
      <?php foreach ($products as $product): ?>
        <div class="col-md-4">
          <div class="card product-card shadow-sm">
            <img src="../uploads/<?= htmlspecialchars($product['image']) ?>" class="card-img-top product-image" alt="<?= htmlspecialchars($product['name']) ?>" />
            <div class="card-body text-center">
              <h5 class="card-title"><?= htmlspecialchars($product['name']) ?></h5>
              <p class="card-text">₱<?= number_format($product['price'], 2) ?></p>
              <p class="text-muted small">Added: <?= date('M d, Y', strtotime($product['date_added'])) ?></p>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>