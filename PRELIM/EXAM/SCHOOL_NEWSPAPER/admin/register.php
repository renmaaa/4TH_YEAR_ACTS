<?php 
require_once 'classes/Database.php';
require_once 'classes/User.php'; 
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin Registration</title>

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(to right, #c2e9fb, #a1c4fd);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 1rem;
    }

    .card {
      border-radius: 20px;
      box-shadow: 0 10px 25px rgba(0,0,0,0.15);
      overflow: hidden;
      animation: fadeIn 0.6s ease-in-out;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .card-header {
      background: linear-gradient(to right, #4b6cb7, #182848);
      color: white;
      padding: 1.5rem;
      text-align: center;
    }

    .card-header h3 {
      font-weight: 600;
      margin-bottom: 0.5rem;
    }

    .card-header p {
      font-size: 0.95rem;
      opacity: 0.9;
    }

    .form-control {
      border-radius: 10px;
      padding: 0.75rem;
      font-size: 1rem;
      margin-bottom: 1rem;
    }

    .btn-primary {
      background-color: #4b6cb7;
      border: none;
      border-radius: 50px;
      font-weight: 500;
      padding: 0.6rem 1.5rem;
      transition: background-color 0.3s ease;
    }

    .btn-primary:hover {
      background-color: #3a56a0;
    }

    .login-link {
      text-align: center;
      margin: 1rem 0;
      font-size: 0.95rem;
    }

    .login-link a {
      color: #4b6cb7;
      font-weight: 600;
      text-decoration: none;
    }

    .login-link a:hover {
      text-decoration: underline;
    }

    .status-message {
      font-weight: 600;
      text-align: center;
      padding: 1rem;
      font-size: 1rem;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card">
          <?php  
          if (isset($_SESSION['message']) && isset($_SESSION['status'])) {
            $color = $_SESSION['status'] == "200" ? "green" : "red";
            echo "<div class='status-message' style='color: {$color};'>{$_SESSION['message']}</div>";
          }
          ?>
          <div class="card-header">
            <h3>Admin Registration</h3>
            <p>Create your admin account below</p>
          </div>
          <form action="core/handleForms.php" method="POST" class="p-4">
            <input type="text" class="form-control" name="username" placeholder="Username" required>
            <input type="email" class="form-control" name="email" placeholder="Email" required>
            <input type="password" class="form-control" name="password" placeholder="Password" required>
            <input type="password" class="form-control" name="confirm_password" placeholder="Confirm Password" required>
            <input type="submit" class="btn btn-primary btn-block mt-3" name="registerAdminBtn" value="Register">
          </form>
          <div class="login-link">
            Already have an account? <a href="login.php">Login here</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
