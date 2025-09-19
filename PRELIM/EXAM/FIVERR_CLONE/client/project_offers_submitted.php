<?php require_once 'classloader.php'; ?>
<?php 
if (!$userObj->isLoggedIn()) {
  header("Location: login.php");
  exit();
}
if (!$userObj->isAdmin() && !$userObj->isFiverrAdmin()) { // Allow Fiverr admin to view client offers
  header("Location: ../freelancer/index.php");
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

    .empty-state {
      text-align: center;
      padding: 60px 20px;
      color: #6c757d;
    }

    .empty-state i {
      font-size: 3rem;
      color: #00B4D8;
      margin-bottom: 20px;
    }

    .empty-state h5 {
      font-weight: 600;
      margin-bottom: 10px;
    }

    .empty-state p {
      font-size: 1rem;
    }
  </style>

  <title>Submitted Project Offers</title>
</head>
<body>

  <?php include 'includes/navbar.php'; ?>

  <div class="container-fluid">
    <div class="display-4 text-center">Welcome! Here are all the submitted project offers</div>

    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card mt-5 mb-5">
          <div class="card-body empty-state">
            <i class="fas fa-folder-open"></i>
            <h5>No project offers submitted yet</h5>
            <p>Once freelancers start submitting proposals, you'll see them listed here with full details and offer history.</p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

</body>
</html>