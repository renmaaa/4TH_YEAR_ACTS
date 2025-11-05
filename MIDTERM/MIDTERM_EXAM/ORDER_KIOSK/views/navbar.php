<?php
if (!isset($_SESSION)) session_start();
$user = $_SESSION['user'] ?? null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>POS Navigation</title>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />
  <style>
    .navbar-brand {
      font-size: 1.25rem;
    }
    .nav-link {
      font-weight: 500;
    }
  </style>
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
    <div class="container-fluid">
      <a class="navbar-brand fw-bold d-flex align-items-center" href="dashboard.php">
        🧾 <span class="ms-2">POS System</span>
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse justify-content-between" id="navbarNav">
        <ul class="navbar-nav">
          <?php if ($user && $user['role'] === 'admin'): ?>
            <li class="nav-item"><a class="nav-link" href="pos.php">🛒 POS</a></li>
            <li class="nav-item"><a class="nav-link" href="products.php">📦 Products</a></li>
          <?php endif; ?>

          <?php if ($user && $user['role'] === 'superadmin'): ?>
            <li class="nav-item"><a class="nav-link" href="users.php">👥 User Management</a></li>
            <li class="nav-item"><a class="nav-link" href="products.php">📦 Products</a></li>
          <?php endif; ?>

          <li class="nav-item"><a class="nav-link" href="reports.php">📊 Reports</a></li>
        </ul>

        <?php if ($user): ?>
          <ul class="navbar-nav">
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown">
                <?= htmlspecialchars($user['username']) ?> (<?= $user['role'] ?>)
              </a>
              <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="dashboard.php">🏠 Dashboard</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item text-danger" href="../controllers/LogoutController.php">🚪 Logout</a></li>
              </ul>
            </li>
          </ul>
        <?php endif; ?>
      </div>
    </div>
  </nav>
</body>
</html>