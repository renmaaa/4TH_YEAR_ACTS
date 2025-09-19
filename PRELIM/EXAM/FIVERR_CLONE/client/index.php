<?php require_once 'classloader.php'; ?>
<?php 
if (!$userObj->isLoggedIn()) {
  header("Location: login.php");
  exit();
}
if (!$userObj->isAdmin()) { // If not a client (i.e., a freelancer)
  header("Location: ../freelancer/index.php");
  exit();
} 
if ($userObj->isFiverrAdmin()) { // If a Fiverr admin, redirect to admin panel
    header("Location: fiverr_admin/index.php");
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

    .proposal-img {
      border-radius: 10px;
      margin-top: 10px;
    }

    .offer {
      margin-bottom: 20px;
    }

    .offer:hover {
      background-color: #eef6fb;
      transition: 0.3s ease;
    }

    label {
      font-weight: 500;
    }
  </style>

  <title>Client Offer Manager</title>
</head>
<body>

  <?php include 'includes/navbar.php'; ?>

  <div class="container-fluid">
    <div class="display-4 text-center">
      Welcome, <span class="text-success"><?php echo $_SESSION['username']; ?></span>!  
      <br>
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

    <div class="row justify-content-center">
      <div class="col-md-12">
        <?php $getProposals = $proposalObj->getProposals(); ?>
        <?php foreach ($getProposals as $proposal) { ?>
        <div class="card shadow mt-4 mb-4">
          <div class="card-body">
            <div class="row">
              <!-- Proposal Info -->
              <div class="col-md-6">
                <h2><a href="other_profile_view.php?user_id=<?php echo $proposal['user_id']; ?>"><?php echo $proposal['username']; ?></a></h2>
                <img src="<?php echo '../images/'.$proposal['image']; ?>" class="img-fluid proposal-img" alt="Proposal Image">
                <p class="mt-3"><?php echo $proposal['description']; ?></p>
                <p class="text-muted">Category: <?php echo htmlspecialchars($proposal['category_name']); ?> | Subcategory: <?php echo htmlspecialchars($proposal['subcategory_name']); ?></p>
                <h4 class="text-info"><?php echo number_format($proposal['min_price']) . " - " . number_format($proposal['max_price']);?> PHP</h4>
              </div>

              <!-- Offer Section -->
              <div class="col-md-6">
                <div class="card h-100">
                  <div class="card-header"><h2>All Offers</h2></div>
                  <div class="card-body overflow-auto" style="max-height: 400px;">
                    <?php $getOffersByProposalID = $offerObj->getOffersByProposalID($proposal['proposal_id']); ?>
                    <?php foreach ($getOffersByProposalID as $offer) { ?>
                    <div class="offer">
                      <h5><?php echo $offer['username']; ?> <span class="text-primary">(<?php echo $offer['contact_number']; ?>)</span></h5>
                      <small><i><?php echo $offer['offer_date_added']; ?></i></small>
                      <p><?php echo $offer['description']; ?></p>

                      <?php if ($offer['user_id'] == $_SESSION['user_id']) { ?>
                        <form action="core/handleForms.php" method="POST">
                          <input type="hidden" name="offer_id" value="<?php echo $offer['offer_id']; ?>">
                          <input type="submit" class="btn btn-danger btn-sm" value="Delete" name="deleteOfferBtn">
                        </form>

                        <form action="core/handleForms.php" method="POST" class="updateOfferForm d-none mt-2">
                          <label>Description</label>
                          <input type="text" class="form-control mb-2" name="description" value="<?php echo $offer['description']; ?>">
                          <input type="hidden" name="offer_id" value="<?php echo $offer['offer_id']; ?>">
                          <input type="submit" class="btn btn-primary btn-block" name="updateOfferBtn">
                        </form>
                      <?php } ?>
                      <hr>
                    </div>
                    <?php } ?>
                  </div>
                  <div class="card-footer">
                    <form action="core/handleForms.php" method="POST">
                      <label>Description</label>
                      <input type="text" class="form-control mb-2" name="description">
                      <input type="hidden" name="proposal_id" value="<?php echo $proposal['proposal_id']; ?>">
                      <input type="submit" class="btn btn-primary float-right mt-2" name="insertOfferBtn" value="Add Offer"> 
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <?php } ?>
      </div>
    </div>
  </div>

  <script>
    $('.offer').on('dblclick', function () {
      $(this).find('.updateOfferForm').toggleClass('d-none');
    });
  </script>

</body>
</html>