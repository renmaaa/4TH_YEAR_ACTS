<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
  header('Location: login.php');
  exit();
}
include_once 'navbar.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Point of Sale</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />
  <style>
    .product-card {
      transition: transform 0.2s ease;
    }
    .product-card:hover {
      transform: scale(1.03);
    }
    .cart-summary {
      background-color: #f8f9fa;
      border-radius: 8px;
      padding: 1rem;
    }
  </style>
</head>
<body class="bg-light">
  <div class="container mt-4">
    <h2 class="mb-4 text-center">🛒 Point of Sale</h2>

    <div class="row">
      <!-- Product List -->
      <div class="col-md-8">
        <div class="row g-3">
          <?php
          require_once '../config/database.php';
          $stmt = $pdo->query("SELECT * FROM products"); // Removed 'status' filter
          while ($product = $stmt->fetch()):
          ?>
            <div class="col-md-4">
              <div class="card product-card shadow-sm">
                <img src="../uploads/<?= $product['image'] ?>" class="card-img-top" alt="<?= htmlspecialchars($product['name']) ?>" />
                <div class="card-body text-center">
                  <h5 class="card-title"><?= htmlspecialchars($product['name']) ?></h5>
                  <p class="card-text">₱<?= number_format($product['price'], 2) ?></p>
                  <form method="POST" action="../controllers/CartController.php">
                    <input type="hidden" name="product_id" value="<?= $product['id'] ?>" />
                    <button type="submit" class="btn btn-primary btn-sm w-100">Add to Cart</button>
                  </form>
                </div>
              </div>
            </div>
          <?php endwhile; ?>
        </div>
      </div>

      <div class="col-md-4">
        <div class="cart-summary shadow-sm">
          <h5 class="mb-3">🧾 Cart Summary</h5>
          <?php
          $cart = $_SESSION['cart'] ?? [];
          $total = 0;
          if (count($cart) > 0):
          ?>
            <ul class="list-group mb-3">
              <?php foreach ($cart as $item): ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                  <?= htmlspecialchars($item['name']) ?>
                  <span>₱<?= number_format($item['price'], 2) ?></span>
                </li>
                <?php $total += $item['price']; ?>
              <?php endforeach; ?>
            </ul>
            <p><strong>Total:</strong> ₱<?= number_format($total, 2) ?></p>
            <form method="POST" action="../controllers/OrderController.php">
              <button type="submit" class="btn btn-success w-100">Complete Order</button>
            </form>
          <?php else: ?>
            <p class="text-muted">Your cart is empty.</p>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>