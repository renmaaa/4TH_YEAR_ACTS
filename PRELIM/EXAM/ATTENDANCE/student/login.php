<?php
        session_start();
        require_once '../core/dbConfig.php';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];

            // Corrected: Authenticate student role
            $stmt = $pdo->prepare("SELECT u.id, u.name, u.email, u.password, u.role, s.course_id, s.year_level
                                   FROM users u
                                   LEFT JOIN students s ON u.id = s.user_id
                                   WHERE u.email = ? AND u.role = 'student'");
            $stmt->execute([$email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user'] = [
                    'id' => $user['id'],
                    'name' => $user['name'],
                    'email' => $user['email'],
                    'role' => 'student', // Ensure role is student
                    'course_id' => $user['course_id'],
                    'year_level' => $user['year_level']
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
    <title>Student Login</title>
    <link href="../css/styles.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .student-container {
            max-width: 400px;
            margin: 80px auto;
        }
    </style>
</head>
<body>
<div class="student-container">
    <div class="card shadow">
        <div class="card-body">
            <h2 class="card-title text-center mb-4">Student Login</h2>
            <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
            <form method="POST">
                <div class="mb-3">
                    <input type="email" name="email" class="form-control" placeholder="Student Email" required>
                </div>
                <div class="mb-3">
                    <input type="password" name="password" class="form-control" placeholder="Student Password" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Login</button>
            </form>
            <br>
            <div class="text-center">
                <p class="mb-2">Don't have an account?</p>
                <a href="register.php" class="btn btn-outline-secondary w-100">Register as Student</a>
            </div>
        </div>
    </div>
</div>
</body>
</html>
