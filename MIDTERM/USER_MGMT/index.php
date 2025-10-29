<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}
$user = $_SESSION['user'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Welcome, <?= htmlspecialchars($user['username']) ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    body {
      font-family: 'Inter', sans-serif;
      background: linear-gradient(to right, #f8f9fa, #e9ecef);
      min-height: 100vh;
      padding: 2rem;
    }
    .card {
      border: none;
      border-radius: 1rem;
      box-shadow: 0 0 20px rgba(0,0,0,0.05);
    }
    .card-header {
      background-color: #0d6efd;
      color: white;
      border-top-left-radius: 1rem;
      border-top-right-radius: 1rem;
    }
    .btn-custom {
      background-color: #0d6efd;
      color: white;
    }
    .btn-custom:hover {
      background-color: #0b5ed7;
    }
    .table thead {
      background-color: #0d6efd;
      color: white;
    }
  </style>
</head>
<body>
<div class="container">
  <div class="card mb-4">
    <div class="card-header text-center py-4">
      <h2 class="mb-0">Welcome, <?= htmlspecialchars($user['username']) ?> 👋</h2>
    </div>
    <div class="card-body text-center">
      <p class="lead">You're logged in as <strong><?= $user['is_admin'] ? 'Admin' : 'User' ?></strong>.</p>
      <?php if ($user['is_admin']): ?>
        <a href="all_users.php" class="btn btn-custom mb-3">Manage Users</a><br>
      <?php endif; ?>
      <button id="logoutBtn" class="btn btn-outline-danger">Logout</button>
    </div>
    <div class="card-footer text-muted text-center py-3">
      <small>Built with ❤️ using Bootstrap 5</small>
    </div>
  </div>

  <div class="card">
    <div class="card-header">
      <h5 class="mb-0">User Directory</h5>
    </div>
    <div class="card-body">
      <input type="text" id="searchInput" class="form-control mb-3" placeholder="Search users...">
      <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
          <thead>
            <tr>
              <th>Username</th>
              <th>First Name</th>
              <th>Last Name</th>
              <th>Is Admin</th>
              <th>Date Added</th>
            </tr>
          </thead>
          <tbody id="userTableBody"></tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<script>
document.getElementById('logoutBtn').addEventListener('click', async () => {
  const formData = new FormData();
  formData.append('action', 'logout');

  const response = await fetch('api.php', {
    method: 'POST',
    body: formData
  });

  const result = await response.json();
  if (result.success) {
    window.location.href = 'login.php';
  }
});

async function fetchUsers(search = '') {
  const formData = new FormData();
  formData.append('action', 'get_users');
  formData.append('search', search);

  const response = await fetch('api.php', {
    method: 'POST',
    body: formData
  });

  const result = await response.json();
  const tbody = document.getElementById('userTableBody');
  tbody.innerHTML = '';

  if (result.success) {
    result.users.forEach(user => {
      const row = `<tr>
        <td>${user.username}</td>
        <td>${user.first_name}</td>
        <td>${user.last_name}</td>
        <td>${user.is_admin ? 'Yes' : 'No'}</td>
        <td>${user.date_added}</td>
      </tr>`;
      tbody.innerHTML += row;
    });
  }
}

document.getElementById('searchInput').addEventListener('input', e => {
  fetchUsers(e.target.value);
});

fetchUsers();
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
