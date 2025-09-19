<?php require_once 'writer/classloader.php'; ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
  <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #f8f9fa;
      color: #333;
    }

    .navbar {
      background-color: #355E3B;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .display-4 {
      font-size: 2.5rem;
      font-weight: 600;
      color: #2c3e50;
    }

    .card {
      border: none;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .card:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    }

    .card-body h1 {
      font-size: 1.75rem;
      font-weight: 600;
      color: #355E3B;
    }

    .card-body p {
      font-size: 1rem;
      line-height: 1.6;
    }

    .btn {
      padding: 0.5rem 1.25rem;
      font-weight: 500;
      border-radius: 50px;
    }

    .btn-primary {
      background-color: #355E3B;
      border-color: #355E3B;
    }

    .btn-primary:hover {
      background-color: #2c4d2f;
      border-color: #2c4d2f;
    }

    .btn-secondary {
      background-color: #6c757d;
      border-color: #6c757d;
    }

    .btn-secondary:hover {
      background-color: #5a6268;
      border-color: #545b62;
    }

    img.img-fluid {
      border-radius: 10px;
      max-height: 250px;
      object-fit: cover;
    }

    small.bg-primary {
      border-radius: 5px;
      padding: 2px 6px;
    }
  </style>
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-dark p-4">
    <a class="navbar-brand" href="#">School Publication Homepage</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" 
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
  </nav>

  <div class="container-fluid">
    <div class="display-4 text-center mt-4">Hello there and welcome to the main homepage!</div>

    <div class="row mt-5">
      <div class="col-md-6">
        <div class="card shadow">
          <div class="card-body text-center">
            <h1>Writer</h1>
            <img src="https://images.unsplash.com/photo-1577900258307-26411733b430?q=80&w=1170&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" class="img-fluid mb-3">
            <p>Content writers create clear, engaging, and informative content that helps businesses communicate their services or products effectively, build brand authority, attract and retain customers, and drive web traffic and conversions.</p>
            <a href="writer/login.php" class="btn btn-primary m-1">Login as Writer</a>
            <a href="writer/register.php" class="btn btn-secondary m-1">Register as Writer</a>
          </div>
        </div>
      </div>

      <div class="col-md-6">
        <div class="card shadow">
          <div class="card-body text-center">
            <h1>Admin</h1>
            <img src="https://plus.unsplash.com/premium_photo-1661582394864-ebf82b779eb0?q=80&w=1170&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" class="img-fluid mb-3">
            <p>Admin writers play a key role in content team development. They are the highest-ranking editorial authority responsible for managing the entire editorial process, and aligning all published material with the publication’s overall vision and strategy.</p>
            <a href="admin/login.php" class="btn btn-primary m-1">Login as Admin</a>
            <a href="admin/register.php" class="btn btn-secondary m-1">Register as Admin</a>
          </div>
        </div>
      </div>
    </div>

    <div class="display-4 text-center mt-5">All articles are below!!</div>

    <div class="row justify-content-center">
      <div class="col-md-6">
        <?php $articles = $articleObj->getArticles(); ?>
        <?php foreach ($articles as $article) { ?>
          <div class="card mt-4 shadow">
            <div class="card-body">
              <h1><?php echo htmlspecialchars($article['title']); ?></h1>
              <?php if ($article['is_admin'] == 1) { ?>
                <p><small class="bg-primary text-white p-1">Message From Admin</small></p>
              <?php } ?>
              <small><strong><?php echo htmlspecialchars($article['username']); ?></strong> - <?php echo $article['created_at']; ?></small>
              <?php if (!empty($article['category_name'])): ?>
                <p><small class="badge badge-info"><?php echo htmlspecialchars($article['category_name']); ?></small></p>
              <?php endif; ?>
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
