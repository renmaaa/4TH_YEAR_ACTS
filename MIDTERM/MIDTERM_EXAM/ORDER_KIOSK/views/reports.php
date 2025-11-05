<?php
session_start();
include_once '../views/navbar.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Generate Report</title>
  <link rel="stylesheet" href="../public/css/style.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />
</head>
<body class="bg-light">
  <div class="container mt-5">
    <h2 class="mb-4">Transaction Report</h2>

    <form method="POST" action="../controllers/ReportController.php" class="card p-4 shadow-sm">
      <div class="row mb-3">
        <div class="col-md-6">
          <label for="start" class="form-label">Start Date</label>
          <input type="date" name="start" class="form-control" required />
        </div>
        <div class="col-md-6">
          <label for="end" class="form-label">End Date</label>
          <input type="date" name="end" class="form-control" required />
        </div>
      </div>
      <button type="submit" class="btn btn-dark w-100">Generate Report</button>
    </form>
  </div>
</body>
</html>