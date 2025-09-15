<?php require_once 'classloader.php'; ?>

<?php 
if (!$userObj->isLoggedIn()) {
  header("Location: login.php");
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

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
  <title>My Articles</title>
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #f4f6f9;
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
      color: #355E3B;
    }

    .status-badge {
      font-size: 0.9rem;
      font-weight: 600;
      padding: 4px 10px;
      border-radius: 20px;
      display: inline-block;
    }

    .status-pending {
      background-color: #f8d7da;
      color: #721c24;
    }

    .status-active {
      background-color: #d4edda;
      color: #155724;
    }

    .updateArticleForm {
      background-color: #f0f4f8;
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
  <?php include 'include/navbar.php'; ?>

  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <h2 class="display-4 text-center">Welcome, <span class="text-success"><?php echo $_SESSION['username']; ?></span></h2>

        <form action="core/handleForms.php" method="POST" class="mb-5">
          <div class="form-group">
            <input type="text" class="form-control mt-4" name="title" placeholder="Input title here" required>
          </div>
          <div class="form-group">
            <textarea name="description" class="form-control mt-4" placeholder="Submit an article!" required></textarea>
          </div>
          <input type="submit" class="btn btn-primary float-right mt-4" name="insertArticleBtn" value="Publish Article">
        </form>

        <h3 class="text-center mb-4">Double-click an article to edit</h3>

        <?php $articles = $articleObj->getArticlesByUserID($_SESSION['user_id']); ?>
        <?php foreach ($articles as $article) { ?>
          <div class="card mt-4 shadow-sm articleCard">
            <div class="card-body">
              <h1><?php echo $article['title']; ?></h1> 
              <small><strong><?php echo $article['username']; ?></strong> – <?php echo $article['created_at']; ?></small>
              <p class="mt-2 status-badge 
                <?php echo $article['is_active'] == 1 ? 'status-active' : 'status-pending'; ?>">
                Status: <?php echo $article['is_active'] == 1 ? 'ACTIVE' : 'PENDING'; ?>
              </p>
              <p class="mt-3"><?php echo $article['content']; ?></p>

              <form class="deleteArticleForm mt-3">
                <input type="hidden" name="article_id" value="<?php echo $article['article_id']; ?>" class="article_id">
                <input type="submit" class="btn btn-danger float-right deleteArticleBtn" value="Delete">
              </form>

              <div class="updateArticleForm d-none">
                <h4>Edit the article</h4>
                <form action="core/handleForms.php" method="POST">
                  <div class="form-group mt-4">
                    <input type="text" class="form-control" name="title" value="<?php echo $article['title']; ?>">
                  </div>
                  <div class="form-group">
                    <textarea name="description" class="form-control"><?php echo $article['content']; ?></textarea>
                    <input type="hidden" name="article_id" value="<?php echo $article['article_id']; ?>">
                    <input type="submit" class="btn btn-primary float-right mt-4" name="editArticleBtn" value="Update">
                  </div>
                </form>
              </div>
            </div>
          </div>  
        <?php } ?> 
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
      if (confirm("Are you sure you want to delete this article?")) {
        $.post("core/handleForms.php", formData, function (data) {
          if (data) {
            location.reload();
          } else {
            alert("Deletion failed");
          }
        });
      }
    });
  </script>
</body>
</html>
