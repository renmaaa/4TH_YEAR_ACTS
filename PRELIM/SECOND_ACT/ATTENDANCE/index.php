<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome to the Enrollment System</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
<div class="landing-container">
    <h1> Attendance System </h1>
    <p>Select your role to continue:</p>

    <div class="role-section">
        <div class="role-card">
            <h2>👨‍🏫 Admin</h2>
            <a href="admin/login.php" class="btn">Login</a>
            <a href="admin/register.php" class="btn secondary">Register</a>
        </div>

        <div class="role-card">
            <h2>🧑 Student</h2>
            <a href="student/login.php" class="btn">Login</a>
            <a href="student/register.php" class="btn secondary">Register</a>
        </div>
    </div>
</div>
</body>
</html>
