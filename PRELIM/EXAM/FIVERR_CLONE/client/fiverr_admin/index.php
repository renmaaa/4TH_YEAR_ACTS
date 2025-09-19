<?php require_once '../classloader.php'; ?>
<?php 
if (!$userObj->isLoggedIn() || !$userObj->isFiverrAdmin()) {
  header("Location: ../login.php");
  exit();
}
?>
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
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #F8F9FA;
    }

    .display-4 {
      font-weight: 600;
      color: #0077B6;
      margin: 40px 0;
    }

    .card {
      border-radius: 12px;
      box-shadow: 0 6px 20px rgba(0,0,0,0.08);
    }

    .card-header {
      background-color: #0077B6;
      color: white;
      font-weight: 600;
      border-top-left-radius: 12px;
      border-top-right-radius: 12px;
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

    .alert {
      border-radius: 10px;
      font-weight: 500;
    }

    .admin-card-icon {
        font-size: 3rem;
        color: #00B4D8;
    }
  </style>

  <title>Fiverr Admin Panel</title>
</head>
<body>

  <?php include 'includes/navbar.php'; ?>

  <div class="container-fluid">
    <div class="display-4 text-center">
      Welcome, <span class="text-success"><?php echo $_SESSION['username']; ?></span>! (Fiverr Administrator)
    </div>

    <div class="text-center mb-4">
      <?php  
        if (isset($_SESSION['message']) && isset($_SESSION['status'])) {
          $alertType = $_SESSION['status'] == "200" ? "success" : "danger";
          echo "<div class='alert alert-$alertType mx-auto' style='max-width: 600px;'>{$_SESSION['message']}</div>";
          unset($_SESSION['message']);
          unset($_SESSION['status']);
        }
      ?>
    </div>

    <div class="row justify-content-center mt-5">
        <div class="col-md-5 mb-4">
            <div class="card text-center h-100">
                <div class="card-body">
                    <i class="fas fa-list-alt admin-card-icon mb-3"></i>
                    <h3 class="card-title">Manage Categories</h3>
                    <p class="card-text">Add, edit, or delete service categories.</p>
                    <a href="manage_categories.php" class="btn btn-primary">Go to Categories</a>
                </div>
            </div>
        </div>
        <div class="col-md-5 mb-4">
            <div class="card text-center h-100">
                <div class="card-body">
                    <i class="fas fa-sitemap admin-card-icon mb-3"></i>
                    <h3 class="card-title">Manage Subcategories</h3>
                    <p class="card-text">Organize services with subcategories under each main category.</p>
                    <a href="manage_subcategories.php" class="btn btn-primary">Go to Subcategories</a>
                </div>
            </div>
        </div>
    </div>
  </div>

</body>
</html>