<?php
session_start();
require_once '../core/dbConfig.php';
require_once '../core/models.php';

if ($_SESSION['user']['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$records = [];
$error = '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="shortcut icon" href="../images/icon.jpg" type="image/jpg">
    <title>Admin Dashboard</title>
    <link href="../css/styles.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light text-dark">
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand d-flex align-items-center" href="index.php">
                <img src="../images/icon.jpg" alt="Admin" width="40" height="40" class="me-2">
                <span class="fw-bold text-danger fs-4">Admin Dashboard</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="adminNavbar">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link text-dark fw-medium" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link text-dark fw-medium" href="manageCourse.php">Courses</a></li>
                    <li class="nav-item"><a class="nav-link text-dark fw-medium" href="viewAttendance.php">Attendance</a></li>
                    <li class="nav-item"><a class="nav-link text-dark fw-medium" href="manageExcuseLetters.php">Excuse Letters</a></li>
                    <li class="nav-item"><a class="nav-link text-dark fw-medium" href="logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="text-center text-white d-flex align-items-center justify-content-center"
            style="height: 200px; background-image: url('../images/attendance-dashboard.png'); background-size: cover; background-position: center; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.2); position: relative;">
            <div style="position: absolute; width: 100%; height: 100%; background-color: rgba(0,0,0,0.4); border-radius: 10px;"></div>
            <h2 style="position: relative; z-index: 1;">View Attendance</h2>
        </div>

        <br>

        <form method="POST" class="card p-4 shadow-sm">
            <div class="mb-3">
                <label for="course" class="form-label">Course</label>
                <select name="course" id="course_id" class="form-select" required>
                    <option value="">-- Select Course --</option>
                    <?php
                    $stmt = $pdo->query("SELECT id, name FROM courses");
                    while ($row = $stmt->fetch()) {
                        $selected = ($_POST['course'] ?? '') == $row['id'] ? 'selected' : '';
                        echo "<option value='{$row['id']}' $selected>{$row['name']}</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="year" class="form-label">Year Level</label>
                <select name="year" id="year" class="form-select" required>
                    <option value="">-- Select Year --</option>
                    <?php
                    $years = [1 => '1st Year', 2 => '2nd Year', 3 => '3rd Year', 4 => '4th Year'];
                    foreach ($years as $val => $label) {
                        $selected = ($_POST['year'] ?? '') == $val ? 'selected' : '';
                        echo "<option value='$val' $selected>$label</option>";
                    }
                    ?>
                </select>
            </div>

            <button type="submit" class="btn btn-primary w-100">View Records</button>
        </form>

        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $admin = new Admin($_SESSION['user']['name'], $_SESSION['user']['email']);
                $records = $admin->viewAttendance($pdo, $_POST['course'], $_POST['year']);

                if ($records && count($records) > 0) {
                    echo "<div class='card mt-4 shadow-sm'><div class='card-body p-0'>";
                    echo "<table class='table table-bordered mb-0'><thead class='table-light'><tr><th>Name</th><th>Date</th><th>Time In</th><th>Status</th></tr></thead><tbody>";
                    foreach ($records as $r) {
                        $status = ucfirst($r['status']);
                        $timeIn = $r['time_in'] ?? '—';
                        echo "<tr><td>{$r['name']}</td><td>{$r['date']}</td><td>{$timeIn}</td><td>{$status}</td></tr>";
                    }
                    echo "</tbody></table></div></div>";
                } else {
                    echo "<div class='alert alert-warning mt-4'>No attendance records found for the selected course and year.</div>";
                }
            } catch (Exception $e) {
                echo "<div class='alert alert-danger mt-4'>Error fetching records: " . htmlspecialchars($e->getMessage()) . "</div>";
            }
        }
        ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>