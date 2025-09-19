<?php require_once 'classloader.php'; ?>
<?php 
if (!$userObj->isLoggedIn()) {
  header("Location: login.php");
  exit();
}
if (!$userObj->isAdmin() && !$userObj->isFiverrAdmin()) { // Allow Fiverr admin to view their client profile
  header("Location: ../freelancer/index.php");
  exit();
} 
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <script src="https://code.jquery.com/jquery-3.7.0.js"></script>

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

    h3 {
      font-weight: 500;
      color: #343a40;
    }

    label {
      font-weight: 500;
    }

    .profile-img {
      border-radius: 10px;
      max-width: 100%;
      height: auto;
    }
  </style>

  <title>Client Profile</title>
</head>
<body>

  <?php include 'includes/navbar.php'; ?>
  <?php $userInfo = $userObj->getUsers($_SESSION['user_id']); ?>

  <div class="container-fluid">
    <div class="display-4 text-center">Welcome to Your Profile</div>

    <div class="row justify-content-center">
      <div class="col-md-12">
        <div class="card shadow mt-4 mb-4">
          <div class="card-body">
            <div class="row">
              <div class="col-md-6 text-center">
                <h1 class="mb-4">Your Profile</h1>
                <img src="https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_1280.png" class="profile-img mb-4" alt="Profile Picture">
                <h3>Username: <?php echo $userInfo['username']; ?></h3>
                <h3>Email: <?php echo $userInfo['email']; ?></h3>
                <h3>Phone Number: <?php echo $userInfo['contact_number']; ?></h3>
              </div>

              <div class="col-md-6">
                <form action="core/handleForms.php" method="POST" enctype="multipart/form-data">
                  <div class="form-group">
                    <label>Username</label>
                    <input type="text" class="form-control" name="username" value="<?php echo $userInfo['username']; ?>" disabled>
                  </div>
                  <div class="form-group">
                    <label>Email</label>
                    <input type="email" class="form-control" name="email" value="<?php echo $userInfo['email']; ?>" disabled>
                  </div>
                  <div class="form-group">
                    <label>Contact Number</label>
                    <input type="text" class="form-control" name="contact_number" value="<?php echo $userInfo['contact_number']; ?>" required>
                  </div>
                  <div class="form-group">
                    <label>Bio</label>
                    <textarea name="bio_description" class="form-control" rows="4"><?php echo $userInfo['bio_description']; ?></textarea>
                  </div>
                  <div class="form-group">
                    <label>Display Picture</label>
                    <input type="file" class="form-control" name="display_picture">
                  </div>
                  <div class="form-group text-right">
                    <input type="submit" class="btn btn-primary" name="updateUserBtn" value="Update Profile">
                  </div>  
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

</body>
</html>