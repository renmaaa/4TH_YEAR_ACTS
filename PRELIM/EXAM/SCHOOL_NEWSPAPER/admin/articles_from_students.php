<?php require_once 'classloader.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Articles From Students</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #f4f6f9;
      padding: 2rem;
      color: #333;
    }

    h1 {
      font-size: 2rem;
      font-weight: 600;
      margin-bottom: 1.5rem;
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

    .display-4 {
      font-size: 2rem;
      font-weight: 600;
      margin-top: 3rem;
      margin-bottom: 1rem;
      color: #2c3e50;
    }
  </style>
</head>
<body>
  <?php include 'includes/navbar.php'; ?>
  <h1>Submit a New Article</h1>
  <form action="core/handleForms.php" method="POST" enctype="multipart/form-data" class="mb-5">
    <div class="form-group">
      <input type="text" class="form-control mt-2" name="title" placeholder="Input title here" required>
    </div>
    <div class="form-group">
      <textarea name="description" class="form-control mt-2" placeholder="Submit an article!" required></textarea>
    </div>
    <div class="form-group">
      <label for="category_id">Category</label>
      <select class="form-control" id="category_id" name="category_id">
        <option value="">Select a category (optional)</option>
        <?php foreach ($categories as $category): ?>
          <option value="<?php echo $category['category_id']; ?>"><?php echo htmlspecialchars($category['category_name']); ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="form-group">
      <input type="file" name="article_image" class="form-control-file">
    </div>
    <input type="submit" class="btn btn-primary float-right mt-3" name="insertArticleBtn" value="Submit Article">
  </form>

  <div class="display-4">Pending Articles</div>
  <?php $articles = $articleObj->getArticles(); ?>
  <?php foreach ($articles as $article) { ?>
    <?php if ($article['is_active'] == 0) { ?>
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

          <form class="deleteArticleForm mt-3" method="POST" action="core/handleForms.php">
            <input type="hidden" name="article_id" value="<?php echo $article['article_id']; ?>" class="article_id">
            <input type="submit" class="btn btn-danger float-right deleteArticleBtn" name="deleteArticleBtn" value="Delete">
          </form>

          <form class="updateArticleStatus mt-3" method="POST" action="core/handleForms.php">
            <input type="hidden" name="article_id" value="<?php echo $article['article_id']; ?>" class="article_id">
            <select name="status" class="form-control is_active_select mt-2" onchange="this.form.submit()">
              <option value="">Select an option</option>
              <option value="0" selected>Pending</option>
              <option value="1">Active</option>
            </select>
            <input type="hidden" name="updateArticleVisibility" value="1">
          </form>

          <div class="updateArticleForm d-none">
            <h4>Edit the article</h4>
            <form action="core/handleForms.php" method="POST">
              <div class="form-group mt-4">
                <input type="text" class="form-control" name="title" value="<?php echo htmlspecialchars($article['title']); ?>">
              </div>
              <div class="form-group">
                <textarea name="description" class="form-control"><?php echo htmlspecialchars($article['content']); ?></textarea>
              </div>
              <div class="form-group">
                <label for="edit_category_id_<?php echo $article['article_id']; ?>">Category</label>
                <select class="form-control" id="edit_category_id_<?php echo $article['article_id']; ?>" name="category_id">
                  <option value="">Select a category (optional)</option>
                  <?php foreach ($categories as $category): ?>
                    <option value="<?php echo $category['category_id']; ?>" <?php echo ($article['category_id'] == $category['category_id']) ? 'selected' : ''; ?>>
                      <?php echo htmlspecialchars($category['category_name']); ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
              <input type="hidden" name="article_id" value="<?php echo $article['article_id']; ?>">
              <input type="submit" class="btn btn-primary float-right mt-4" name="editArticleBtn" value="Update Article">
            </form>
          </div>
        </div>
      </div>  
    <?php } ?> 
  <?php } ?> 
  // ... (after Pending Articles section) ...

<div class="display-4 mt-5">Pending Edit Requests</div>
<?php $pendingEditRequests = $articleObj->getPendingEditRequests(); ?>
<?php if (empty($pendingEditRequests)): ?>
  <p class="text-muted">No pending edit requests.</p>
<?php else: ?>
  <?php foreach ($pendingEditRequests as $request): ?>
    <div class="card mt-4 shadow">
      <div class="card-body">
        <h5 class="card-title">Edit Request for: "<?php echo htmlspecialchars($request['article_title']); ?>"</h5>
        <p class="card-text">Requested by: <strong><?php echo htmlspecialchars($request['requester_username']); ?></strong> on <?php echo htmlspecialchars($request['requested_at']); ?></p>
        <hr>
        <h6>Proposed Title:</h6>
        <p><?php echo htmlspecialchars($request['proposed_title']); ?></p>
        <h6>Proposed Content:</h6>
        <p><?php echo nl2br(htmlspecialchars($request['proposed_content'])); ?></p>

        <form action="core/handleForms.php" method="POST" class="d-inline">
          <input type="hidden" name="request_id" value="<?php echo $request['request_id']; ?>">
          <input type="hidden" name="article_id" value="<?php echo $request['article_id']; ?>">
          <input type="hidden" name="proposed_title" value="<?php echo htmlspecialchars($request['proposed_title']); ?>">
          <input type="hidden" name="proposed_content" value="<?php echo htmlspecialchars($request['proposed_content']); ?>">
          <button type="submit" name="acceptEditRequestBtn" class="btn btn-success btn-sm">Accept</button>
        </form>
        <form action="core/handleForms.php" method="POST" class="d-inline ml-2">
          <input type="hidden" name="request_id" value="<?php echo $request['request_id']; ?>">
          <button type="submit" name="rejectEditRequestBtn" class="btn btn-danger btn-sm">Reject</button>
        </form>
      </div>
    </div>
  <?php endforeach; ?>
<?php endif; ?>


  <script>
    $('.articleCard').on('dblclick', function () {
      $(this).find('.updateArticleForm').toggleClass('d-none');
    });
  </script>
</body>
</html>
