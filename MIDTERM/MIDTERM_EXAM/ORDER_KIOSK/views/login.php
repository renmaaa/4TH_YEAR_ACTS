<?php
session_start();
$error = $_SESSION['error'] ?? null;
unset($_SESSION['error']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login - POS System</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />
  <style>
    body {
      background: linear-gradient(to right, #007bff, #00c6ff);
      font-family: 'Segoe UI', sans-serif;
    }
    .login-card {
      max-width: 400px;
      margin: auto;
      margin-top: 10vh;
      padding: 2rem;
      border-radius: 10px;
      background-color: #fff;
      box-shadow: 0 8px 20px rgba(0,0,0,0.15);
    }
    .form-control {
      border-radius: 6px;
    }
    .btn-primary {
      border-radius: 6px;
    }
  </style>
</head>
<body>
  <div class="login-card">
    <h3 class="text-center mb-4">🔐 POS System Login</h3>

    <?php if ($error): ?>
      <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" action="../controllers/AuthController.php">
      <div class="mb-3">
        <label for="username" class="form-label">Username</label>
        <input type="text" name="username" class="form-control" placeholder="Enter username" required />
      </div>
      <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" name="password" class="form-control" placeholder="Enter password" required />
      </div>
      <button type="submit" class="btn btn-primary w-100">Login</button>
    </form>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>