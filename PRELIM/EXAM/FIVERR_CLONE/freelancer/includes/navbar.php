<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500;600&display=swap" rel="stylesheet">

<style>
  .navbar {
    background-color: #0077B6;
    font-family: 'Poppins', sans-serif;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
  }

  .navbar-brand {
    font-weight: 600;
    font-size: 1.5rem;
    color: #ffffff !important;
  }

  .navbar-nav .nav-link {
    font-weight: 500;
    color: #ffffff !important;
    margin-right: 15px;
    transition: color 0.3s ease;
  }

  .navbar-nav .nav-link:hover {
    color: #90E0EF !important;
  }

  .navbar-toggler {
    border: none;
  }

  .navbar-toggler-icon {
    background-image: url("data:image/svg+xml;charset=utf8,%3Csvg viewBox='0 0 30 30' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath stroke='rgba%28255, 255, 255, 0.7%29' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3E%3C/svg%3E");
  }
</style>

<nav class="navbar navbar-expand-lg navbar-dark p-4">
  <a class="navbar-brand" href="index.php">Freelancer Panel</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  
  <div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav ml-auto">
      <li class="nav-item">
        <a class="nav-link" href="index.php">Home</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="profile.php">Profile</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="your_proposals.php">Your Proposals</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="offers_from_clients.php">Offers From Clients</a>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownCategories" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Categories
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdownCategories">
          <?php 
            $categories = $categoryObj->getCategories();
            foreach ($categories as $cat) {
              echo '<h6 class="dropdown-header">' . htmlspecialchars($cat['category_name']) . '</h6>';
              $subcategories = $subcategoryObj->getSubcategories(null, $cat['category_id']);
              if (!empty($subcategories)) {
                foreach ($subcategories as $subcat) {
                  echo '<a class="dropdown-item" href="#">' . htmlspecialchars($subcat['subcategory_name']) . '</a>';
                }
              } else {
                echo '<a class="dropdown-item disabled" href="#">No subcategories</a>';
              }
              echo '<div class="dropdown-divider"></div>';
            }
          ?>
        </div>
      </li>
      <?php if ($userObj->isFiverrAdmin()): ?>
      <li class="nav-item">
        <a class="nav-link" href="../client/fiverr_admin/index.php">Admin Panel</a>
      </li>
      <?php endif; ?>
      <li class="nav-item">
        <a class="nav-link" href="core/handleForms.php?logoutUserBtn=1">Logout</a>
      </li>
    </ul>
  </div>
</nav>