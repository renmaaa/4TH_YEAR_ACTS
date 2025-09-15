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
      background-image: url("https://img.freepik.com/free-vector/winter-blue-pink-gradient-background-vector_53876-117275.jpg");
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
      background-color: rgba(255, 255, 255, 0.7);
      z-index: -1;
    }

    .card {
      border-radius: 12px;
      box-shadow: 0 6px 20px rgba(0,0,0,0.1);
    }

    .card-header {
      background-color: #00B4D8;
      color: white;
      font-weight: 600;
      border-top-left-radius: 12px;
      border-top-right-radius: 12px;
      text-align: center;
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

    .alert {
      border-radius: 10px;
      font-weight: 500;
    }

    .register-wrapper {
      margin-top: 80px;
    }

    a {
      color: #0077B6;
      font-weight: 500;
    }

    a:hover {
      text-decoration: underline;
    }
  </style>

  <title>Freelancer Registration</title>
</head>
<body>

  <div class="container register-wrapper">
    <div class="row justify-content-center">
      <div class="col-md-8 p-4">
        <div class="card">
          <?php  
            if (isset($_SESSION['message']) && isset($_SESSION['status'])) {
              $alertType = $_SESSION['status'] == "200" ? "success" : "danger";
              echo "<div class='alert alert-$alertType mt-3 mx-3'>{$_SESSION['message']}</div>";
              unset($_SESSION['message']);
              unset($_SESSION['status']);
            }
          ?>
          <div class="card-header">
            <h2>Register as a Freelancer</h2>
            <p class="mb-0">Create your account to start offering your services</p>
          </div>
          <form action="core/handleForms.php" method="POST">
            <div class="card-body">
              <div class="form-group">
                <label for="username">Username</label>
                <input type="text" class="form-control" name="username" id="username" required placeholder="Enter your username">
              </div>
              <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" name="email" id="email" required placeholder="Enter your email">
              </div>
              <div class="form-group">
                <label for="contact_number">Contact Number</label>
                <input type="text" class="form-control" name="contact_number" id="contact_number" required placeholder="Enter your contact number">
              </div>
              <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" name="password" id="password" required placeholder="Create a password">
              </div>
              <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" class="form-control" name="confirm_password" id="confirm_password" required placeholder="Confirm your password">
              </div>
              <div class="form-group text-right">
                <input type="submit" class="btn btn-primary" name="insertNewUserBtn" value="Register">
              </div>
              <div class="form-group text-center">
                <p class="mb-0">Already have an account? <a href="login.php">Login here</a></p>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

</body>
</html>
