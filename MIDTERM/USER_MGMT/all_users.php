<?php
session_start();
if (!isset($_SESSION['user']) || !$_SESSION['user']['is_admin']) {
    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>All Users</title>
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
    .table thead {
      background-color: #0d6efd;
      color: white;
    }
    .btn-custom {
      background-color: #0d6efd;
      color: white;
    }
    .btn-custom:hover {
      background-color: #0b5ed7;
    }
    .modal-header {
      background-color: #0d6efd;
      color: white;
    }
  </style>
</head>
<body>
<div class="container">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <a href="index.php" class="btn btn-outline-secondary">🏠 Home</a>
    <button id="logoutBtn" class="btn btn-outline-danger">Logout</button>
  </div>

  <div class="card p-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h3 class="mb-0">User Management</h3>
      <button class="btn btn-custom" data-bs-toggle="modal" data-bs-target="#addUserModal">+ Add User</button>
    </div>
    <input type="text" id="searchInput" class="form-control mb-3" placeholder="Search by username, first or last name...">
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

<!-- Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="addUserForm" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add New User</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="text" id="username" name="username" class="form-control mb-2" placeholder="Username">
        <input type="text" id="first_name" name="first_name" class="form-control mb-2" placeholder="First Name">
        <input type="text" id="last_name" name="last_name" class="form-control mb-2" placeholder="Last Name">
        <input type="password" id="password" name="password" class="form-control mb-2" placeholder="Password">
        <input type="password" id="confirm_password" name="confirm_password" class="form-control mb-2" placeholder="Confirm Password">
        <div class="form-check">
          <input type="checkbox" id="is_admin" name="is_admin" class="form-check-input">
          <label class="form-check-label" for="is_admin">Is Admin</label>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-custom">Add User</button>
      </div>
    </form>
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

document.getElementById('addUserForm').addEventListener('submit', async function(e) {
  e.preventDefault();

  const username = document.getElementById('username').value.trim();
  const first_name = document.getElementById('first_name').value.trim();
  const last_name = document.getElementById('last_name').value.trim();
  const password = document.getElementById('password').value;
  const confirm_password = document.getElementById('confirm_password').value;
  const is_admin = document.getElementById('is_admin').checked ? 1 : 0;

  if (!username || !first_name || !last_name || !password || !confirm_password) {
    Swal.fire('Error', 'All fields are required.', 'error');
    return;
  }
  if (password.length < 8) {
    Swal.fire('Error', 'Password must be at least 8 characters.', 'error');
    return;
  }
  if (password !== confirm_password) {
    Swal.fire('Error', 'Passwords do not match.', 'error');
    return;
  }

  const formData = new FormData();
  formData.append('action', 'add_user');
  formData.append('username', username);
  formData.append('first_name', first_name);
  formData.append('last_name', last_name);
  formData.append('password', password);
  formData.append('confirm_password', confirm_password);
  formData.append('is_admin', is_admin);

  const response = await fetch('api.php', {
    method: 'POST',
    body: formData
  });

  const result = await response.json();
  if (result.success) {
    Swal.fire('Success', result.message, 'success');
    fetchUsers();
    document.getElementById('addUserForm').reset();
    bootstrap.Modal.getInstance(document.getElementById('addUserModal')).hide();
  } else {
    Swal.fire('Error', result.message, 'error');
  }
});

fetchUsers();
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
