<nav class="navbar navbar-expand-lg navbar-dark py-3 px-4" style="background-color: #008080; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
  <a class="navbar-brand font-weight-bold" href="index.php" style="font-size: 1.5rem;">
    🛠️ Admin Panel
  </a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" 
          aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
    <ul class="navbar-nav">
      <li class="nav-item mx-2">
        <a class="nav-link text-white" href="articles_from_students.php">
          <i class="fas fa-hourglass-half mr-1"></i> Pending Articles
        </a>
      </li>
      <li class="nav-item mx-2">
        <a class="nav-link text-white" href="articles_submitted.php">
          <i class="fas fa-file-alt mr-1"></i> Articles Submitted
        </a>
      </li>
      <li class="nav-item mx-2">
        <a class="nav-link text-white" href="categories.php">
          <i class="fas fa-tags mr-1"></i> Categories
        </a>
      </li>
      <li class="nav-item mx-2">
        <a class="nav-link text-white" href="core/handleForms.php?logoutUserBtn=1">
          <i class="fas fa-sign-out-alt mr-1"></i> Logout
        </a>
      </li>
    </ul>
  </div>
</nav>
