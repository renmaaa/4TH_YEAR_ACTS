<?php
session_start();
if (isset($_SESSION['user'])) {
    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Register</h4>
                </div>
                <div class="card-body">
                    <form id="registerForm">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" id="username" name="username" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="first_name" class="form-label">First Name</label>
                            <input type="text" id="first_name" name="first_name" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="last_name" class="form-label">Last Name</label>
                            <input type="text" id="last_name" name="last_name" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" id="password" name="password" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Confirm Password</label>
                            <input type="password" id="confirm_password" name="confirm_password" class="form-control">
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="is_admin" name="is_admin">
                            <label class="form-check-label" for="is_admin">Register as Admin</label>
                        </div>
                        <button type="submit" class="btn btn-success w-100">Register</button>
                    </form>
                    <div class="mt-3 text-center">
                        <a href="login.php">Already have an account? Login</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('registerForm').addEventListener('submit', async function(e) {
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
    formData.append('action', 'register');
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
        Swal.fire('Success', result.message, 'success').then(() => {
            window.location.href = 'login.php';
        });
    } else {
        Swal.fire('Error', result.message, 'error');
    }
});
</script>
</body>
</html>
