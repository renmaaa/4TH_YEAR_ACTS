<?php require_once 'classloader.php'; ?>

<?php 
if (!$userObj->isLoggedIn()) {
  header("Location: login.php");
  exit();
}

if (!$userObj->isAdmin()) {
  header("Location: ../writer/index.php");
  exit();
}  
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin Article Management</title>

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #f4f6f9;
      padding: 2rem;
      color: #333;
    }

    .display-4 {
      font-size: 2rem;
      font-weight: 600;
      margin-top: 2rem;
      margin-bottom: 1rem;
      color: #2c3e50;
    }

    .form-control {
      border-radius: 10px;
      padding: 0.75rem;
      font-size: 1rem;
    }

    textarea.form-control {
      min-height: 150px;
      resize: vertical;
    }

    .btn-primary {
      background-color: #008080;
      border-color: #008080;
      border-radius: 50px;
      font-weight: 500;
    }

    .btn-primary:hover {
      background-color: #006666;
      border-color: #006666;
    }

    .btn-danger {
      border-radius: 50px;
    }

    .card {
      border: none;
      border-radius: 15px;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .card:hover {
      transform: translateY(-3px);
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    }

    .card-body h1 {
      font-size: 1.5rem;
      font-weight: 600;
      color: #008080;
    }

    .updateArticleForm {
      background-color: #eef2f7;
      padding: 1rem;
      border-radius: 10px;
      margin-top: 1rem;
    }

    .updateArticleForm h4 {
      font-weight: 600;
      color: #2c3e50;
    }
  </style>
</head>
<body>
  <?php include 'includes/navbar.php'; ?>

  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <form action="core/handleForms.php" method="POST" class="mb-5">
          <div class="form-group">
            <input type="text" class="form-control mt-4" name="title" placeholder="Input title here" required>
          </div>
          <div class="form-group">
            <textarea name="description" class="form-control mt-4" placeholder="Submit an article!" required></textarea>
          </div>
          <input type="submit" class="btn btn-primary float-right mt-4" name="insertArticleBtn" value="Publish Article">
        </form>

        <h2 class="display-4 text-center">Double-click an article to edit</h2>

        <?php $articles = $articleObj->getArticlesByUserID($_SESSION['user_id']); ?>
        <?php foreach ($articles as $article) { ?>
      <div class="card mt-4 shadow articleCard">
        <div class="card-body">
          <h1><?php echo htmlspecialchars($article['title']); ?></h1>
          <small><strong><?php echo htmlspecialchars($article['username']); ?></strong> – <?php echo $article['created_at']; ?></small>
          <p class="mt-3"><?php echo nl2br(htmlspecialchars($article['content'])); ?></p>

          <form class="deleteArticleForm mt-3" method="POST" action="core/handleForms.php">
            <input type="hidden" name="article_id" value="<?php echo $article['article_id']; ?>" class="article_id">
            <input type="submit" class="btn btn-danger float-right deleteArticleBtn" name="deleteArticleBtn" value="Delete">
          </form>

          <button class="btn btn-secondary btn-sm float-right mr-2 shareArticleBtn" data-article-id="<?php echo $article['article_id']; ?>" data-article-title="<?php echo htmlspecialchars($article['title']); ?>">
            Share Article
          </button>

          <div class="updateArticleForm d-none">
            <h4>Edit the article</h4>
            <form action="core/handleForms.php" method="POST">
              <div class="form-group mt-4">
                <input type="text" class="form-control" name="title" value="<?php echo htmlspecialchars($article['title']); ?>">
              </div>
              <div class="form-group">
                <textarea name="description" class="form-control"><?php echo htmlspecialchars($article['content']); ?></textarea>
                <input type="hidden" name="article_id" value="<?php echo $article['article_id']; ?>">
                <input type="submit" class="btn btn-primary float-right mt-4" name="editArticleBtn" value="Update Article">
              </div>
            </form>
          </div>
        </div>
      </div>
    <?php } ?>

    <div class="modal fade" id="shareArticleModal" tabindex="-1" aria-labelledby="shareArticleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="shareArticleModalLabel">Share Article: <span id="shareArticleTitle"></span></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form action="core/handleForms.php" method="POST">
            <div class="modal-body">
              <input type="hidden" name="article_id" id="shareArticleId">
              <div class="form-group">
                <label for="sharedWithUser">Share with:</label>
                <select class="form-control" id="sharedWithUser" name="shared_with_user_id" required>
                  <option value="">Select a user</option>
                  <?php foreach ($allUsers as $user): ?>
                    <?php if ($user['user_id'] != $_SESSION['user_id']): ?>
                      <option value="<?php echo $user['user_id']; ?>"><?php echo htmlspecialchars($user['username']); ?></option>
                    <?php endif; ?>
                  <?php endforeach; ?>
                  </select>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary" name="shareArticleBtn">Share</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <script>
      $('.articleCard').on('dblclick', function () {
        $(this).find('.updateArticleForm').toggleClass('d-none');
      });

      $('.deleteArticleForm').on('submit', function (event) {
        event.preventDefault();
        const formData = {
          article_id: $(this).find('.article_id').val(),
          deleteArticleBtn: 1
        };
        if (confirm("Are you sure you want to delete this article? This action cannot be undone.")) {
          $.post("core/handleForms.php", formData, function (data) {
            if (data == 1) {
              location.reload();
            } else {
              alert("Deletion failed");
            }
          });
        }
      });
      $('.shareArticleBtn').on('click', function() {
          const articleId = $(this).data('article-id');
          const articleTitle = $(this).data('article-title');
          $('#shareArticleId').val(articleId);
          $('#shareArticleTitle').text(articleTitle);
          $('#shareArticleModal').modal('show');
        });
    </script>
</body>
</html>
