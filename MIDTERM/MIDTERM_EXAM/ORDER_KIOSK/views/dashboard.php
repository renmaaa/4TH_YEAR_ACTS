<?php
session_start();
if (!isset($_SESSION['user'])) {
  header('Location: login.php');
  exit();
}
$user = $_SESSION['user'];
include_once '../views/navbar.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dashboard - POS System</title>
  <link rel="stylesheet" href="../public/css/style.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />
  <style>
    .module-card {
      transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .module-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 16px rgba(0,0,0,0.15);
    }
    .card-icon {
      font-size: 2rem;
      margin-bottom: 10px;
    }
  </style>
</head>
<body class="bg-light">
  <div class="container mt-5">
    <div class="text-center mb-5">
      <h2 class="fw-bold">Welcome, <?= htmlspecialchars($user['username']) ?>!</h2>
      <p class="text-muted">Select a module to begin managing your POS system.</p>
    </div>

    <div class="row justify-content-center g-4">
      <?php if ($user['role'] === 'admin'): ?>
        <div class="col-md-4">
          <a href="pos.php" class="text-decoration-none">
            <div class="card module-card text-center p-4 bg-primary text-white">
              <div class="card-icon">🛒</div>
              <h5 class="card-title">Point of Sales</h5>
              <p class="card-text">Process orders and manage transactions.</p>
            </div>
          </a>
        </div>
        <div class="col-md-4">
          <a href="products.php" class="text-decoration-none">
            <div class="card module-card text-center p-4 bg-secondary text-white">
              <div class="card-icon">📦</div>
              <h5 class="card-title">Manage Products</h5>
              <p class="card-text">Add, edit, and organize menu items.</p>
            </div>
          </a>
        </div>
      <?php endif; ?>

      <?php if ($user['role'] === 'superadmin'): ?>
        <div class="col-md-4">
          <a href="users.php" class="text-decoration-none">
            <div class="card module-card text-center p-4 bg-success text-white">
              <div class="card-icon">👥</div>
              <h5 class="card-title">User Management</h5>
              <p class="card-text">Create and manage admin accounts.</p>
            </div>
          </a>
        </div>
        <div class="col-md-4">
          <a href="products.php" class="text-decoration-none">
            <div class="card module-card text-center p-4 bg-secondary text-white">
              <div class="card-icon">📦</div>
              <h5 class="card-title">Manage Products</h5>
              <p class="card-text">Add, edit, and organize menu items.</p>
            </div>
          </a>
        </div>
      <?php endif; ?>

      <div class="col-md-4">
        <a href="reports.php" class="text-decoration-none">
          <div class="card module-card text-center p-4 bg-dark text-white">
            <div class="card-icon">📊</div>
            <h5 class="card-title">Reports</h5>
            <p class="card-text">View and export transaction history.</p>
          </div>
        </a>
      </div>
    </div>
  </div>
</body>
</html>