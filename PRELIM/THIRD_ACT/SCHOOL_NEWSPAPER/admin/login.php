<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin Login</title>

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(to right, #fbc2eb, #a6c1ee);
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
      background: linear-gradient(to right, #2575fc, #6a11cb);
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
    }

    .btn-primary {
      background-color: #6a11cb;
      border: none;
      border-radius: 50px;
      font-weight: 500;
      padding: 0.6rem 1.5rem;
      transition: background-color 0.3s ease;
    }

    .btn-primary:hover {
      background-color: #4e0ec2;
    }

    .login-message {
      text-align: center;
      font-weight: 600;
      margin: 1rem 0;
      font-size: 1rem;
    }

    .register-link {
      text-align: center;
      margin: 1rem 0;
      font-size: 0.95rem;
    }

    .register-link a {
      color: #6a11cb;
      font-weight: 600;
      text-decoration: none;
    }

    .register-link a:hover {
      text-decoration: underline;
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
            echo "<div class='login-message' style='color: {$color};'>{$_SESSION['message']}</div>";
          }
          ?>
          <div class="card-header">
            <h3>Admin Login</h3>
            <p>Access your dashboard below</p>
          </div>
          <form action="core/handleForms.php" method="POST">
            <div class="card-body p-4">
              <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" name="email" required>
              </div>
              <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" name="password" required>
              </div>
              <button type="submit" class="btn btn-primary btn-block mt-4" name="loginUserBtn">Login</button>
            </div>
          </form>
          <div class="register-link">
            Don't have an account? <a href="register.php">Register as Admin</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
          