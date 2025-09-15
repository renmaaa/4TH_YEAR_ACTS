<?php require_once 'classloader.php'; ?>

<?php 
if (!$userObj->isLoggedIn()) {
  header("Location: index.php");
  exit();
}

if ($userObj->isAdmin()) {
  header("Location: ../admin/index.php");
  exit();
}  
?>
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
      background-color: #f4f6f9;
      color: #333;
    }

    .display-4 {
      font-size: 2.5rem;
      font-weight: 600;
      margin-top: 2rem;
      margin-bottom: 2rem;
      color: #2c3e50;
    }

    .form-control {
      border-radius: 10px;
      padding: 0.75rem;
    }

    textarea.form-control {
      min-height: 150px;
      resize: vertical;
    }

    .btn-primary {
      background-color: #355E3B;
      border-color: #355E3B;
      border-radius: 50px;
      font-weight: 500;
    }

    .btn-primary:hover {
      background-color: #2c4d2f;
      border-color: #2c4d2f;
    }

    .card {
      border: none;
      border-radius: 15px;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .card:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    }

    .card-body h1 {
      font-size: 1.5rem;
      font-weight: 600;
      color: #355E3B;
    }

    .card-body p {
      font-size: 1rem;
      line-height: 1.6;
    }

    small.bg-primary {
      border-radius: 5px;
      padding: 2px 6px;
    }
  </style>
</head>
<body>
  <?php include 'include/navbar.php'; ?>

  <div class="container">
    <div class="display-4 text-center">
      Hello there and welcome, <span class="text-success"><?php echo htmlspecialchars($_SESSION['username']); ?></span>!<br>
      Here are all the articles
    </div>

    <div class="row justify-content-center">
      <div class="col-md-8">
      <form action="core/handleForms.php" method="POST" enctype="multipart/form-data" class="mb-5">
        <div class="form-group">
          <input type="text" class="form-control mt-4" name="title" placeholder="Input title here" required>
        </div>
        <div class="form-group">
          <textarea name="description" class="form-control mt-4" placeholder="Submit an article!" required></textarea>
        </div>
        <div class="form-group">
          <label for="article_image">Upload Image (optional)</label>
          <input type="file" class="form-control-file" id="article_image" name="article_image" accept="image/*">
        </div>
        <input type="submit" class="btn btn-primary float-right mt-4" name="insertArticleBtn" value="Publish Article">
      </form>
    
        <?php 
        $articles = $articleObj->getArticles(); 
        ?>
        <?php foreach ($articles as $article) { ?>
        <div class="card mt-4 shadow-sm">
          <div class="card-body">
            <h1><?php echo htmlspecialchars($article['title']); ?></h1>
            <?php if (!empty($article['is_admin']) && $article['is_admin'] == 1) { ?>
              <p><small class="bg-primary text-white">Message From Admin</small></p>
            <?php } ?>
            <small>
              <strong><?php echo isset($article['username']) ? htmlspecialchars($article['username']) : 'Unknown'; ?></strong> – 
              <?php echo htmlspecialchars($article['created_at']); ?>
            </small>
            <?php if (!empty($article['image_path'])): ?>
              <div class="my-3 text-center">
                <img src="<?php echo htmlspecialchars($article['image_path']); ?>" alt="Article Image" class="img-fluid rounded" style="max-height: 300px;">
              </div>
            <?php endif; ?>
            <p class="mt-2"><?php echo nl2br(htmlspecialchars($article['content'])); ?></p>
            <button class="btn btn-info btn-sm float-right ml-2 requestEditBtn" data-article-id="<?php echo $article['article_id']; ?>" data-article-title="<?php echo htmlspecialchars($article['title']); ?>" data-article-content="<?php echo htmlspecialchars($article['content']); ?>">
              Request Edit
            </button>
          </div>
        </div>
      <?php } ?>
    <div class="modal fade" id="editRequestModal" tabindex="-1" aria-labelledby="editRequestModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="editRequestModalLabel">Request Edit for Article</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form action="core/handleForms.php" method="POST">
            <div class="modal-body">
              <input type="hidden" name="article_id" id="editRequestArticleId">
              <div class="form-group">
                <label for="proposedTitle">Proposed Title</label>
                <input type="text" class="form-control" id="proposedTitle" name="proposed_title" required>
              </div>
              <div class="form-group">
                <label for="proposedContent">Proposed Content</label>
                <textarea class="form-control" id="proposedContent" name="proposed_content" rows="6" required></textarea>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary" name="submitEditRequestBtn">Submit Edit Request</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <script>
      $(document).ready(function() {
        $('.requestEditBtn').on('click', function() {
          const articleId = $(this).data('article-id');
          const articleTitle = $(this).data('article-title');
          const articleContent = $(this).data('article-content');
          $('#editRequestArticleId').val(articleId);
          $('#proposedTitle').val(articleTitle);
          $('#proposedContent').val(articleContent);
          $('#editRequestModal').modal('show');
        });
      });
    </script>
</body>
</html>
