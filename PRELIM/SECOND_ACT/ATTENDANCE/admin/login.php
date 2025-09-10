<?php
        session_start();
        require_once '../core/dbConfig.php';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];

            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND role = 'admin'");
            $stmt->execute([$email]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user'] = [
                    'id' => $user['id'],
                    'name' => $user['name'],
                    'email' => $user['email'],
                    'role' => 'admin'
                ];
                header("Location: index.php");
                exit;
            } else {
                $error = "Invalid credentials.";
            }
        }
        ?>
        

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Login</title>
    <link href="../css/styles.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .admin-container {
            max-width: 400px;
            margin: 80px auto;
        }
    </style>
</head>
<body>
<div class="admin-container">
    <div class="card shadow">
        <div class="card-body">
            <h2 class="card-title text-center mb-4">Admin Login</h2>
            <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
            <form method="POST">
                <div class="mb-3">
                    <input type="email" name="email" class="form-control" placeholder="Admin Email" required>
                </div>
                <div class="mb-3">
                    <input type="password" name="password" class="form-control" placeholder="Admin Password" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Login</button>
            </form>
            <br>
            <div class="text-center">
                <p class="mb-2">Don't have an account?</p>
                <a href="register.php" class="btn btn-outline-secondary w-100">Register as Admin</a>
            </div>
        </div>
    </div>
</div>
</body>
</html>
