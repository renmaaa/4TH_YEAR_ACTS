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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Users</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="container mt-5">
        <h2>All Users</h2>
        <input type="text" id="search" class="form-control mb-3" placeholder="Search by username, first name, or last name">
        <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#addUserModal">Add User</button>
        <table class="table table-striped" id="usersTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Is Admin</th>
                    <th>Date Added</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

    <!-- Add User Modal -->
    <div class="modal fade" id="addUserModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="addUserForm">
                        <div class="mb-3">
                            <label for="addUsername" class="form-label">Username</label>
                            <input type="text" class="form-control" id="addUsername" required>
                        </div>
                        <div class="mb-3">
                            <label for="addFirstname" class="form-label">First Name</label>
                            <input type="text" class="form-control" id="addFirstname" required>
                        </div>
                        <div class="mb-3">
                            <label for="addLastname" class="form-label">Last Name</label>
                            <input type="text" class="form-control" id="addLastname" required>
                        </div>
                        <div class="mb-3">
                            <label for="addPassword" class="form-label">Password</label>
                            <input type="password" class="form-control" id="addPassword" required>
                        </div>
                        <div class="mb-3">
                            <label for="addConfirmPassword" class="form-label">Confirm Password</label>
                            <input type="password" class="form-control" id="addConfirmPassword" required>
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="addIsAdmin">
                            <label class="form-check-label" for="addIsAdmin">Admin</label>
                        </div>
                        <button type="submit" class="btn btn-primary">Add User</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function loadUsers(search = '') {
            fetch('api.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: new URLSearchParams({ action: 'get_users', search })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const tbody = document.querySelector('#usersTable tbody');
                    tbody.innerHTML = '';
                    data.users.forEach(user => {
                        const row = `<tr>
                            <td>${user.id}</td>
                            <td>${user.username}</td>
                            <td>${user.firstname}</td>
                            <td>${user.lastname}</td>
                            <td>${user.is_admin ? 'Yes' : 'No'}</td>
                            <td>${user.date_added}</td>
                        </tr>`;
                        tbody.innerHTML += row;
                    });
                } else {
                    Swal.fire('Error', data.message, 'error');
                }
            });
        }

        document.getElementById('search').addEventListener('input', function() {
            loadUsers(this.value);
        });

        document.getElementById('addUserForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const username = document.getElementById('addUsername').value.trim();
            const firstname = document.getElementById('addFirstname').value.trim();
            const lastname = document.getElementById('addLastname').value.trim();
            const password = document.getElementById('addPassword').value;
            const confirmPassword = document.getElementById('addConfirmPassword').value;
            const isAdmin = document.getElementById('addIsAdmin').checked ? 1 : 0;

            if (!username || !firstname || !lastname || !password || !confirmPassword) {
                Swal.fire('Error', 'All fields are required.', 'error');
                return;
            }
            if (password.length < 8) {
                Swal.fire('Error', 'Password must be at least 8 characters.', 'error');
                return;
            }
            if (password !== confirmPassword) {
                Swal.fire('Error', 'Passwords do not match.', 'error');
                return;
            }

            fetch('api.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: new URLSearchParams({ action: 'add_user', username, firstname, lastname, password, confirm_password: confirmPassword, is_admin: isAdmin })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('Success', data.message, 'success').then(() => {
                        bootstrap.Modal.getInstance(document.getElementById('addUserModal')).hide();
                        document.getElementById('addUserForm').reset();
                        loadUsers();
                    });
                } else {
                    Swal.fire('Error', data.message, 'error');
                }
            });
        });
        loadUsers();
    </script>
</body>
</html>
