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

    .btn-danger {
      border-radius: 30px;
      font-weight: 600;
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

    .proposalCard img {
      border-radius: 10px;
      margin-top: 10px;
    }

    .proposalCard:hover {
      background-color: #eef6fb;
      transition: 0.3s ease;
    }

    label {
      font-weight: 500;
    }
  </style>

  <title>Your Proposals</title>
</head>
<body>

  <?php include 'includes/navbar.php'; ?>

  <div class="container-fluid">
    <div class="display-4 text-center">Double-click a proposal to edit</div>

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

    <div class="row justify-content-center">
      <div class="col-md-6">
        <?php $getProposalsByUserID = $proposalObj->getProposalsByUserID($_SESSION['user_id']); ?>
        <?php foreach ($getProposalsByUserID as $proposal) { ?>
        <div class="card proposalCard shadow mt-4 mb-4">
          <div class="card-body">
            <h2><a href="#"><?php echo $proposal['username']; ?></a></h2>
            <img src="<?php echo "../images/".$proposal['image']; ?>" class="img-fluid" alt="Proposal Image">
            <p class="mt-3"><i><?php echo $proposal['proposals_date_added']; ?></i></p>
            <p class="mt-2"><?php echo $proposal['description']; ?></p>
            <h4 class="text-info"><?php echo number_format($proposal['min_price']) . " - " . number_format($proposal['max_price']);?> PHP</h4>

            <form action="core/handleForms.php" method="POST" class="mt-3">
              <input type="hidden" name="proposal_id" value="<?php echo $proposal['proposal_id']; ?>">
              <input type="hidden" name="image" value="<?php echo $proposal['image']; ?>">
              <input type="submit" name="deleteProposalBtn" class="btn btn-danger float-right" value="Delete">
            </form>

            <form action="core/handleForms.php" method="POST" class="updateProposalForm d-none mt-4">
              <div class="row">
                <div class="col-md-6">
                  <label>Minimum Price</label>
                  <input type="number" class="form-control" name="min_price" value="<?php echo $proposal['min_price']; ?>">
                </div>
                <div class="col-md-6">
                  <label>Maximum Price</label>
                  <input type="number" class="form-control" name="max_price" value="<?php echo $proposal['max_price']; ?>">
                </div>
                <div class="col-md-12 mt-3">
                  <label>Description</label>
                  <input type="hidden" name="proposal_id" value="<?php echo $proposal['proposal_id']; ?>">
                  <textarea name="description" class="form-control" rows="3"><?php echo $proposal['description']; ?></textarea>
                  <input type="submit" class="btn btn-primary form-control mt-3" name="updateProposalBtn" value="Update Proposal">
                </div>
              </div>
            </form>
          </div>
        </div>
        <?php } ?>
      </div>
    </div>
  </div>

  <script>
    $('.proposalCard').on('dblclick', function () {
      $(this).find('.updateProposalForm').toggleClass('d-none');
    });
  </script>

</body>
</html>
