<?php
require_once 'classloader.php';
session_start();

if (!$userObj->isLoggedIn() || !$userObj->isAdmin()) {
  header("Location: login.php");
  exit();
}

$categories = $articleObj->getCategories();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Categories</title>
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
    .card-body h5 {
      font-size: 1.25rem;
      font-weight: 600;
      color: #008080;
    }
    .updateCategoryForm {
      background-color: #eef2f7;
      padding: 1rem;
      border-radius: 10px;
      margin-top: 1rem;
    }
    .updateCategoryForm h6 {
      font-weight: 600;
      color: #2c3e50;
    }
  </style>
</head>
<body>
  <?php include 'includes/navbar.php'; ?>

  <div class="container">
    <h1>Manage Article Categories</h1>

    <?php  
    if (isset($_SESSION['message']) && isset($_SESSION['status'])) {
      $color = $_SESSION['status'] == "200" ? "green" : "red";
      echo "<div class='alert alert-" . ($_SESSION['status'] == "200" ? "success" : "danger") . "'>{$_SESSION['message']}</div>";
      unset($_SESSION['message']);
      unset($_SESSION['status']);
    }
    ?>

    <div class="card shadow mb-4">
      <div class="card-body">
        <h5 class="card-title">Add New Category</h5>
        <form action="core/handleForms.php" method="POST">
          <div class="form-group">
            <input type="text" class="form-control" name="category_name" placeholder="Category Name" required>
          </div>
          <button type="submit" class="btn btn-primary" name="createCategoryBtn">Add Category</button>
        </form>
      </div>
    </div>

    <h2 class="mt-5">Existing Categories</h2>
    <?php if (empty($categories)): ?>
      <p class="text-muted">No categories found.</p>
    <?php else: ?>
      <?php foreach ($categories as $category): ?>
        <div class="card mt-3 shadow-sm">
          <div class="card-body">
            <h5 class="d-inline-block mr-3"><?php echo htmlspecialchars($category['category_name']); ?></h5>
            <small class="text-muted">Created: <?php echo $category['created_at']; ?></small>
            
            <button class="btn btn-info btn-sm float-right ml-2 toggleEditCategoryForm" data-category-id="<?php echo $category['category_id']; ?>">Edit</button>
            <form action="core/handleForms.php" method="POST" class="d-inline float-right deleteCategoryForm">
              <input type="hidden" name="category_id" value="<?php echo $category['category_id']; ?>">
              <button type="submit" class="btn btn-danger btn-sm" name="deleteCategoryBtn">Delete</button>
            </form>

            <div class="updateCategoryForm mt-3 d-none" id="editCategoryForm<?php echo $category['category_id']; ?>">
              <h6>Edit Category</h6>
              <form action="core/handleForms.php" method="POST">
                <div class="form-group">
                  <input type="text" class="form-control" name="category_name" value="<?php echo htmlspecialchars($category['category_name']); ?>" required>
                </div>
                <input type="hidden" name="category_id" value="<?php echo $category['category_id']; ?>">
                <button type="submit" class="btn btn-primary btn-sm" name="updateCategoryBtn">Update Category</button>
              </form>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>

  <script>
    $(document).ready(function() {
      $('.toggleEditCategoryForm').on('click', function() {
        const categoryId = $(this).data('category-id');
        $('#editCategoryForm' + categoryId).toggleClass('d-none');
      });

      $('.deleteCategoryForm').on('submit', function(event) {
        if (!confirm("Are you sure you want to delete this category? All articles assigned to this category will have their category set to NULL.")) {
          event.preventDefault();
        }
      });
    });
  </script>
</body>
</html>
