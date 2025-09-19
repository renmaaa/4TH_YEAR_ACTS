<?php require_once '../classloader.php'; ?>
<?php 
if (!$userObj->isLoggedIn() || !$userObj->isFiverrAdmin()) {
  header("Location: ../login.php");
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
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

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

    .btn-danger {
      border-radius: 30px;
      font-weight: 600;
    }

    .alert {
      border-radius: 10px;
      font-weight: 500;
    }

    .category-item:hover {
        background-color: #eef6fb;
        transition: 0.3s ease;
    }
  </style>

  <title>Manage Categories</title>
</head>
<body>

  <?php include 'includes/navbar.php'; ?>

  <div class="container-fluid">
    <div class="display-4 text-center">Manage Categories</div>

    <div class="text-center mb-4">
      <?php  
        if (isset($_SESSION['message']) && isset($_SESSION['status'])) {
          $alertType = $_SESSION['status'] == "200" ? "success" : "danger";
          echo "<div class='alert alert-$alertType mx-auto' style='max-width: 600px;'>{$_SESSION['message']}</div>";
          unset($_SESSION['message']);
          unset($_SESSION['status']);
        }
      ?>
    </div>

    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card shadow mt-4 mb-4">
          <div class="card-header">Add New Category</div>
          <div class="card-body">
            <form action="core/handleForms.php" method="POST">
              <div class="form-group">
                <label for="category_name">Category Name</label>
                <input type="text" class="form-control" id="category_name" name="category_name" required>
              </div>
              <button type="submit" name="addCategoryBtn" class="btn btn-primary float-right">Add Category</button>
            </form>
          </div>
        </div>
      </div>
    </div>

    <div class="row justify-content-center mt-5">
      <div class="col-md-8">
        <div class="card shadow mb-4">
          <div class="card-header">Existing Categories (Double-click to edit)</div>
          <div class="card-body">
            <ul class="list-group">
              <?php $categories = $categoryObj->getCategories(); ?>
              <?php if (empty($categories)): ?>
                <li class="list-group-item text-center text-muted">No categories added yet.</li>
              <?php else: ?>
                <?php foreach ($categories as $cat): ?>
                  <li class="list-group-item category-item d-flex justify-content-between align-items-center">
                    <span><?php echo htmlspecialchars($cat['category_name']); ?></span>
                    <div>
                      <form action="core/handleForms.php" method="POST" class="d-inline">
                        <input type="hidden" name="category_id" value="<?php echo $cat['category_id']; ?>">
                        <button type="submit" name="deleteCategoryBtn" class="btn btn-danger btn-sm ml-2" onclick="return confirm('Are you sure you want to delete this category? This will also delete all associated subcategories and proposals!');">Delete</button>
                      </form>
                    </div>
                    <form action="core/handleForms.php" method="POST" class="updateCategoryForm d-none mt-2 w-100">
                        <label class="sr-only" for="edit_category_name_<?php echo $cat['category_id']; ?>">Edit Category Name</label>
                        <input type="text" class="form-control mb-2" id="edit_category_name_<?php echo $cat['category_id']; ?>" name="category_name" value="<?php echo htmlspecialchars($cat['category_name']); ?>">
                        <input type="hidden" name="category_id" value="<?php echo $cat['category_id']; ?>">
                        <button type="submit" class="btn btn-primary btn-block" name="updateCategoryBtn">Update</button>
                    </form>
                  </li>
                <?php endforeach; ?>
              <?php endif; ?>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    $('.category-item').on('dblclick', function () {
      $(this).find('.updateCategoryForm').toggleClass('d-none');
      $(this).find('span').toggleClass('d-none');
      $(this).find('form.d-inline').toggleClass('d-none');
    });
  </script>

</body>
</html>