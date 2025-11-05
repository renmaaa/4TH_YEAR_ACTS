<?php
session_start();
$user = $_SESSION['user'] ?? null;
$data = $_SESSION['report_data'] ?? null;

if (!$user || !in_array($user['role'], ['admin', 'superadmin']) || !$data) {
  header('Location: login.php');
  exit();
}

include_once '../views/navbar.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Report Preview</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />
  <style>
    @media print {
      .no-print { display: none; }
      body { background: white !important; }
    }
    .report-header {
      border-bottom: 2px solid #ccc;
      margin-bottom: 1rem;
      padding-bottom: 0.5rem;
    }
    .table th, .table td {
      vertical-align: middle;
    }
  </style>
</head>
<body class="bg-light">
  <div class="container mt-4">
    <div class="report-header d-flex justify-content-between align-items-center">
      <div>
        <h2 class="fw-bold">🧾 Transaction Report</h2>
        <p class="mb-0">Date Range: <strong><?= htmlspecialchars($data['start']) ?></strong> to <strong><?= htmlspecialchars($data['end']) ?></strong></p>
      </div>
      <button onclick="window.print()" class="btn btn-dark no-print">🖨️ Print</button>
    </div>

    <div class="table-responsive">
      <table class="table table-bordered table-striped">
        <thead class="table-light">
          <tr>
            <th>Date</th>
            <th>Product</th>
            <th>Quantity</th>
            <th>Total</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($data['orders'] as $order): ?>
            <tr>
              <td><?= htmlspecialchars($order['date']) ?></td>
              <td><?= htmlspecialchars($order['product']) ?></td>
              <td><?= htmlspecialchars($order['quantity']) ?></td>
              <td>₱<?= number_format($order['total'], 2) ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <h5 class="text-end mt-4">Grand Total: <strong>₱<?= number_format($data['total'], 2) ?></strong></h5>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>