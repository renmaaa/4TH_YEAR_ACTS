<?php require_once 'classloader.php'; ?>
<?php 
if (!$userObj->isLoggedIn()) {
  header("Location: login.php");
}
if ($userObj->isAdmin()) {
  header("Location: ../client/index.php");
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

    .card-body h1 {
      font-size: 1.8rem;
      font-weight: 600;
      color: #0077B6;
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

    .alert {
      border-radius: 10px;
      font-weight: 500;
    }

    h2 a {
      color: #0077B6;
      text-decoration: none;
      font-weight: 600;
    }

    h2 a:hover {
      text-decoration: underline;
    }

    .proposal-image {
      border-radius: 10px;
      margin-top: 10px;
    }

    .proposal-meta {
      font-size: 0.9rem;
      color: #6c757d;
    }

    .proposal-price {
      font-size: 1.2rem;
      font-weight: 600;
      color: #00B4D8;
    }

    .proposal-link a {
      font-weight: 500;
      color: #0077B6;
    }

    .proposal-link a:hover {
      text-decoration: underline;
    }
  </style>

  <title>Freelancer Dashboard</title>
</head>
<body>

  <?php include 'includes/navbar.php'; ?>

  <div class="container-fluid">
    <div class="display-4 text-center">
      Welcome, <span class="text-success"><?php echo $_SESSION['username']; ?></span>! Add Your Proposal Below
    </div>

    <div class="row">
      <!-- Proposal Form -->
      <div class="col-md-5">
        <div class="card mt-4 mb-4">
          <div class="card-body">
            <?php  
              if (isset($_SESSION['message']) && isset($_SESSION['status'])) {
                $alertType = $_SESSION['status'] == "200" ? "success" : "danger";
                echo "<div class='alert alert-$alertType'>{$_SESSION['message']}</div>";
                unset($_SESSION['message']);
                unset($_SESSION['status']);
              }
            ?>
            <h1 class="mb-4">Add Proposal</h1>
            <form action="core/handleForms.php" method="POST" enctype="multipart/form-data">
              <div class="form-group">
                <label>Description</label>
                <input type="text" class="form-control" name="description" required>
              </div>
              <div class="form-group">
                <label>Minimum Price</label>
                <input type="number" class="form-control" name="min_price" required>
              </div>
              <div class="form-group">
                <label>Maximum Price</label>
                <input type="number" class="form-control" name="max_price" required>
              </div>
              <div class="form-group">
                <label>Image</label>
                <input type="file" class="form-control" name="image" required>
              </div>
              <div class="form-group text-right">
                <input type="submit" class="btn btn-primary" name="insertNewProposalBtn" value="Submit Proposal">
              </div>
            </form>
          </div>
        </div>
      </div>

      <div class="col-md-7">
        <?php $getProposals = $proposalObj->getProposals(); ?>
        <?php foreach ($getProposals as $proposal) { ?>
        <div class="card shadow mt-4 mb-4">
          <div class="card-body">
            <h2><a href="other_profile_view.php?user_id=<?php echo $proposal['user_id']; ?>"><?php echo $proposal['username']; ?></a></h2>
            <img src="<?php echo '../images/' . $proposal['image']; ?>" alt="Proposal Image" class="img-fluid proposal-image">
            <p class="proposal-meta mt-3"><i><?php echo $proposal['proposals_date_added']; ?></i></p>
            <p class="mt-2"><?php echo $proposal['description']; ?></p>
            <p class="proposal-price"><?php echo number_format($proposal['min_price']) . " - " . number_format($proposal['max_price']); ?> PHP</p>
            <div class="proposal-link float-right">
              <a href="#">Check out services</a>
            </div>
          </div>
        </div>
        <?php } ?>
      </div>
    </div>
  </div>

</body>
</html>
