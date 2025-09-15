<?php require_once 'classloader.php'; ?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <script src="https://code.jquery.com/jquery-3.7.0.js"></script>

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-image: url("https://images.unsplash.com/photo-1501504905252-473c47e087f8?q=80&w=1074&auto=format&fit=crop");
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      position: relative;
      min-height: 100vh;
    }

    body::before {
      content: "";
      position: absolute;
      top: 0; left: 0;
      width: 100%; height: 100%;
      background-color: rgba(0, 0, 0, 0.5);
      z-index: -1;
    }

    .card {
      border-radius: 12px;
      box-shadow: 0 6px 20px rgba(0,0,0,0.15);
    }

    .card-header {
      background-color: #0077B6;
      color: white;
      font-weight: 600;
      border-top-left-radius: 12px;
      border-top-right-radius: 12px;
    }

    .form-control {
      border-radius: 10px;
    }

    .btn-primary {
      background-color: #0077B6;
      border: none;
      font-weight: 600;
      border-radius: 30px;
    }

    .btn-primary:hover {
      background-color: #023E8A;
    }

    .form-group label {
      font-weight: 500;
    }

    .login-wrapper {
      margin-top: 80px;
    }

    .alert {
      border-radius: 10px;
      font-weight: 500;
    }

    a {
      color: #00B4D8;
      font-weight: 500;
    }

    a:hover {
      text-decoration: underline;
    }
  </style>

  <title>Client Login</title>
</head>
<body>

  <div class="container login-wrapper">
    <div class="row justify-content-center">
      <div class="col-md-8 p-4">
        <div class="card">
          <div class="card-header text-center">
            <h2>Welcome to the Client Panel</h2>
            <p class="mb-0">Please log in to continue</p>
          </div>
          <form action="core/handleForms.php" method="POST">
            <div class="card-body">
              <?php  
                if (isset($_SESSION['message']) && isset($_SESSION['status'])) {
                  $alertType = $_SESSION['status'] == "200" ? "success" : "danger";
                  echo "<div class='alert alert-$alertType'>{$_SESSION['message']}</div>";
                  unset($_SESSION['message']);
                  unset($_SESSION['status']);
                }
              ?>
              <div class="form-group">
                <label for="email">Username</label>
                <input type="email" class="form-control" name="email" id="email" placeholder="Enter your email">
              </div>
              <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" name="password" id="password" placeholder="Enter your password">
              </div>
              <div class="form-group text-right">
                <input type="submit" class="btn btn-primary" name="loginUserBtn" value="Login">
              </div>
              <div class="form-group text-center">
                <p class="mb-0">Don't have an account yet? <a href="register.php">Register here</a></p>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

</body>
</html>
