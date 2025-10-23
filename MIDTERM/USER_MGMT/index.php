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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Hello there, <?php echo htmlspecialchars($user['username']); ?>!</h2>
        <p>Welcome to your dashboard.</p>
        <?php if ($user['is_admin']): ?>
            <a href="all_users.php" class="btn btn-primary">View All Users</a>
        <?php endif; ?>
        <button id="logout" class="btn btn-danger ms-2">Logout</button>
    </div>

    <script>
        document.getElementById('logout').addEventListener('click', function() {
            fetch('api.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: new URLSearchParams({ action: 'logout' })
            }).then(() => window.location.href = 'login.php');
        });
    </script>
</body>
</html>
