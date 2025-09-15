<?php
require_once 'classloader.php';

if (!$userObj->isLoggedIn()) {
    header("Location: login.php");
    exit();
}

if ($userObj->isAdmin()) {
    header("Location: ../admin/index.php");
    exit();
}

$shared_articles = $articleObj->getSharedArticlesForUser ($_SESSION['user_id']);
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <title>Shared Articles</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" crossorigin="anonymous" />
  <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
  <style>
    body {
      font-family: "Arial", sans-serif;
      background-color: #f0f8ff;
    }
    .articleCard {
      border-radius: 15px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
      background: #fff;
      padding: 20px;
      margin-bottom: 30px;
    }
    .updateArticleForm {
      background: #e6f2ff;
      padding: 15px;
      border-radius: 10px;
    }
    h1 {
      font-family: 'Comic Sans MS', cursive, sans-serif;
      color: #2a7ae2;
    }
    .btn-primary {
      background-color: #4a90e2;
      border-color: #4a90e2;
      font-weight: bold;
    }
    .btn-primary:hover {
      background-color: #357ABD;
      border-color: #357ABD;
    }
  </style>
</head>
<body>
  <?php include 'include/navbar.php'; ?>

  <div class="container mt-5">
    <h2 class="text-center mb-4" style="font-family: 'Comic Sans MS', cursive, sans-serif; color: #2a7ae2;">
      Articles Shared with You
    </h2>

    <?php if (empty($shared_articles)) : ?>
      <p class="text-center text-muted">No articles have been shared with you yet.</p>
    <?php else: ?>
      <?php foreach ($shared_articles as $article): ?>
        <div class="articleCard">
          <h1><?php echo htmlspecialchars($article['title']); ?></h1>
          <small class="text-muted">
            Shared by: <strong><?php echo htmlspecialchars($article['author_username']); ?></strong> on <?php echo htmlspecialchars($article['share_date']); ?>
          </small>

          <?php if (!empty($article['image_path'])): ?>
            <div class="my-3 text-center">
              <img src="<?php echo htmlspecialchars($article['image_path']); ?>" alt="Article Image" class="img-fluid rounded" style="max-height: 300px;">
            </div>
          <?php endif; ?>

          <p><?php echo nl2br(htmlspecialchars($article['content'])); ?></p>

          <button class="btn btn-primary editSharedArticleBtn" type="button" aria-expanded="false" aria-controls="editForm<?php echo $article['article_id']; ?>">
            Edit Shared Article
          </button>

          <div class="updateArticleForm mt-3 d-none" id="editForm<?php echo $article['article_id']; ?>">
            <h4>Edit the shared article</h4>
            <form action="core/handleForms.php" method="POST" enctype="multipart/form-data">
              <div class="form-group mt-3">
                <label for="title<?php echo $article['article_id']; ?>">Title</label>
                <input type="text" class="form-control" id="title<?php echo $article['article_id']; ?>" name="title" value="<?php echo htmlspecialchars($article['title']); ?>" required>
              </div>
              <div class="form-group">
                <label for="description<?php echo $article['article_id']; ?>">Content</label>
                <textarea class="form-control" id="description<?php echo $article['article_id']; ?>" name="description" rows="6" required><?php echo htmlspecialchars($article['content']); ?></textarea>
              </div>
              <div class="form-group">
                <label for="article_image<?php echo $article['article_id']; ?>">Change Image (optional)</label>
                <input type="file" class="form-control-file" id="article_image<?php echo $article['article_id']; ?>" name="article_image" accept="image/*">
              </div>
              <input type="hidden" name="article_id" value="<?php echo $article['article_id']; ?>">
              <button type="submit" class="btn btn-primary mt-2" name="editSharedArticleBtn">Save Changes</button>
            </form>
          </div>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>

  <script>
    $(document).ready(function() {
      $('.editSharedArticleBtn').click(function() {
        var button = $(this);
        var form = button.next('.updateArticleForm');
        form.toggleClass('d-none');

        var expanded = button.attr('aria-expanded') === 'true';
        button.attr('aria-expanded', !expanded);
      });
    });
  </script>
</body>
</html>
