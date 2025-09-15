<?php
session_start();
if (isset($_SESSION['status']) && $_SESSION['status'] == "200") {
  header("Location: ../index.php");
  exit();
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
  <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
  <title>Writer Login</title>
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-image: url("https://images.unsplash.com/photo-1488190211105-8b0e65b80b4e?q=80&w=1170&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D");
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .login-card {
      background-color: rgba(255, 255, 255, 0.95);
      border-radius: 15px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
      padding: 2rem;
      width: 100%;
      max-width: 500px;
    }

    .login-card h2 {
      font-weight: 600;
      color: #355E3B;
      margin-bottom: 1.5rem;
    }

    .form-control {
      border-radius: 10px;
      padding: 0.75rem;
    }

    .btn-primary {
      background-color: #355E3B;
      border-color: #355E3B;
      border-radius: 50px;
      padding: 0.5rem 1.5rem;
      font-weight: 500;
    }

    .btn-primary:hover {
      background-color: #2c4d2f;
      border-color: #2c4d2f;
    }

    .alert-message {
      font-size: 1rem;
      font-weight: 500;
      margin-bottom: 1rem;
    }

    a {
      color: #355E3B;
      font-weight: 500;
    }

    a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <div class="login-card">
    <h2 class="text-center">Writer Login</h2>
    <form action="core/handleForms.php" method="POST">
      <?php  
      if (isset($_SESSION['message']) && isset($_SESSION['status'])) {
        $color = $_SESSION['status'] == "200" ? "green" : "red";
        echo "<div class='alert-message text-center' style='color: {$color};'>{$_SESSION['message']}</div>";
        if ($_SESSION['status'] == "200") {
          echo "<script>setTimeout(() => { window.location.href = '../index.php'; }, 1500);</script>";
        }
      }
      ?>
      <div class="form-group">
        <label for="email">Email</label>
        <input type="email" class="form-control" name="email" required>
      </div>
      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" class="form-control" name="password" required>
      </div>
      <div class="text-right">
        <input type="submit" class="btn btn-primary" name="loginUserBtn" value="Login">
      </div>
      <p class="mt-3 text-center">Don't have an account yet? <a href="register.php">Register here</a></p>
    </form>
  </div>
</body>
</html>
