<?php
session_start();
if (isset($_SESSION['user'])) {
  header('Location: dashboard.php');
  exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Register Superadmin</title>
  <link rel="stylesheet" href="../public/css/style.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />
</head>
<body class="bg-light">
  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card shadow-sm">
          <div class="card-header bg-success text-white text-center">
            <h3>Superadmin Registration</h3>
          </div>
          <div class="card-body">
            <form method="POST" action="../controllers/RegisterSuperadminController.php">
              <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" name="username" class="form-control" required />
              </div>
              <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required />
              </div>
              <button type="submit" class="btn btn-success w-100">Create Superadmin</button>
            </form>
          </div>
          <div class="card-footer text-muted text-center">
            <small>This form is for initial setup only.</small>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>