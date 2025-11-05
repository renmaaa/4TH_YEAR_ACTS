<?php
include '../views/navbar.php';
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'superadmin') {
  header('Location: login.php');
  exit();
}
require_once '../config/database.php';

$stmt = $pdo->prepare("SELECT * FROM users WHERE role = 'admin'");
$stmt->execute();
$admins = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>User Management</title>
  <link rel="stylesheet" href="../public/css/style.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />
</head>
<body class="bg-light">
  <div class="container mt-5">
    <h2 class="mb-4">Admin Accounts</h2>

    <form method="POST" action="../controllers/RegisterAdminController.php" class="mb-4">
      <div class="row g-2">
        <div class="col-md-4">
          <input type="text" name="username" class="form-control" placeholder="New Admin Username" required />
        </div>
        <div class="col-md-4">
          <input type="password" name="password" class="form-control" placeholder="Password" required />
        </div>
        <div class="col-md-4">
          <button type="submit" class="btn btn-success w-100">Create Admin</button>
        </div>
      </div>
    </form>

    <table class="table table-bordered table-striped">
      <thead class="table-primary">
        <tr>
          <th>ID</th>
          <th>Username</th>
          <th>Status</th>
          <th>Date Added</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($admins as $admin): ?>
          <tr>
            <td><?= $admin['id'] ?></td>
            <td><?= htmlspecialchars($admin['username']) ?></td>
            <td><?= $admin['status'] ?></td>
            <td><?= $admin['date_added'] ?></td>
            <td>
              <?php if ($admin['status'] === 'active'): ?>
                <form method="POST" action="../controllers/UserController.php" style="display:inline;">
                  <input type="hidden" name="user_id" value="<?= $admin['id'] ?>" />
                  <button type="submit" class="btn btn-danger btn-sm">Suspend</button>
                </form>
              <?php else: ?>
                <span class="text-muted">Suspended</span>
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</body>
</html>