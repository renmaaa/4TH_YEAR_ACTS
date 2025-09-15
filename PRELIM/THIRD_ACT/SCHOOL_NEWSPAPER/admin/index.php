<?php
require_once 'classloader.php';
session_start();

if (!$userObj->isLoggedIn()) {
  header("Location: login.php");
  exit();
}

if (!$userObj->isAdmin()) {
  header("Location: ../writer/index.php");
  exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin Dashboard</title>

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(to right, #e0eafc, #cfdef3);
      min-height: 100vh;
    }
    .card {
      border-radius: 12px;
    }
    .card-body h1 {
      font-size: 1.5rem;
    }
    .form-control {
      border-radius: 8px;
    }
    .btn-primary {
      background-color: #4b6cb7;
      border: none;
    }
    .display-4 {
      font-size: 2rem;
      margin-top: 30px;
    }
  </style>
</head>
<body>
  <?php include 'includes/navbar.php'; ?>
  <div class="container-fluid">
    <div class="display-4 text-center">
      Welcome to the Admin Panel, <span class="text-success"><?php echo $_SESSION['username']; ?></span>!
    </div>

    <div class="row justify-content-center mt-4">
      <div class="col-md-6">
        <form action="core/handleForms.php" method="POST">
          <div class="form-group">
            <input type="text" class="form-control mt-4" name="title" placeholder="Article Title" required>
          </div>
          <div class="form-group">
            <textarea name="description" class="form-control mt-4" placeholder="Message as Admin" rows="4" required></textarea>
          </div>
          <button type="submit" class="btn btn-primary form-control mt-4 mb-4" name="insertAdminArticleBtn">Submit Article</button>
        </form>

        <?php $articles = $articleObj->getActiveArticles(); ?>
        <?php foreach ($articles as $article) { ?>
          <div class="card mt-4 shadow">
            <div class="card-body">
              <h1><?php echo htmlspecialchars($article['title']); ?></h1>
              <?php if ($article['is_admin'] == 1) { ?>
                <p><small class="bg-primary text-white p-1">Message From Admin</small></p>
              <?php } ?>
              <small><strong><?php echo htmlspecialchars($article['username']); ?></strong> - <?php echo $article['created_at']; ?></small>
              <?php if (!empty($article['image_path'])): ?>
                <div class="my-3 text-center">
                  <img src="<?php echo htmlspecialchars($article['image_path']); ?>" alt="Article Image" class="img-fluid rounded" style="max-height: 300px;">
                </div>
              <?php endif; ?>
              <p class="mt-2"><?php echo nl2br(htmlspecialchars($article['content'])); ?></p>
            </div>
          </div>  
        <?php } ?> 
      </div>
    </div>
  </div>
</body>
</html>
