<?php require_once 'classloader.php'; ?>
<?php 
if (!$userObj->isLoggedIn()) {
  header("Location: login.php");
  exit();
}
if ($userObj->isAdmin()) { 
  header("Location: ../client/index.php");
  exit();
}  
if ($userObj->isFiverrAdmin()) { 
    header("Location: ../client/fiverr_admin/index.php");
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

    .proposal-meta {
      font-size: 0.9rem;
      color: #6c757d;
    }

    .proposal-price {
      font-size: 1.2rem;
      font-weight: 600;
      color: #00B4D8;
    }

    .offer {
      margin-bottom: 20px;
    }

    .offer h4 {
      font-weight: 500;
    }

    .offer small {
      color: #6c757d;
    }

    .offer p {
      margin-bottom: 10px;
    }

    .offer hr {
      border-top: 1px solid #dee2e6;
    }

    .proposal-link a {
      font-weight: 500;
      color: #0077B6;
    }

    .proposal-link a:hover {
      text-decoration: underline;
    }
  </style>

  <title>Proposal & Offers</title>
</head>
<body>

  <?php include 'includes/navbar.php'; ?>

  <div class="container-fluid">
    <div class="display-4 text-center">Welcome to Your Proposals</div>

    <div class="row justify-content-center">
      <div class="col-md-12">
        <?php $getProposalsByUserID = $proposalObj->getProposalsByUserID($_SESSION['user_id']); ?>
        <?php foreach ($getProposalsByUserID as $proposal) { ?>
        <div class="card shadow mt-4 mb-4">
          <div class="card-body">
            <div class="row">
              <div class="col-md-6">
                <h2><a href="#"><?php echo $proposal['username']; ?></a></h2>
                <img src="<?php echo '../images/'.$proposal['image']; ?>" class="img-fluid proposal-img" alt="Proposal Image">
                <p class="proposal-meta mt-3"><i><?php echo $proposal['proposals_date_added']; ?></i></p>
                <p class="mt-2"><?php echo htmlspecialchars($proposal['description']); ?></p>
                <p class="text-muted">Category: <?php echo htmlspecialchars($proposal['category_name']); ?> | Subcategory: <?php echo htmlspecialchars($proposal['subcategory_name']); ?></p>
                <p class="proposal-price"><?php echo number_format($proposal['min_price']) . " - " . number_format($proposal['max_price']);?> PHP</p>
                <div class="proposal-link float-right">
                  <a href="#">Check out services</a>
                </div>
              </div>

              <div class="col-md-6">
                <div class="card h-100">
                  <div class="card-header"><h2>All Offers</h2></div>
                  <div class="card-body overflow-auto" style="max-height: 400px;">
                    <?php $getOffersByProposalID = $offerObj->getOffersByProposalID($proposal['proposal_id']); ?>
                    <?php foreach ($getOffersByProposalID as $offer) { ?>
                    <div class="offer">
                      <h4><?php echo $offer['username']; ?> <span class="text-primary">(<?php echo $offer['contact_number']; ?>)</span></h4>
                      <small><i><?php echo $offer['offer_date_added']; ?></i></small>
                      <p><?php echo htmlspecialchars($offer['description']); ?></p>
                      <hr>
                    </div>
                    <?php } ?>
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

</body>
</html>