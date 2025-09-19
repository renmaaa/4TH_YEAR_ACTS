<?php
session_start();
require_once '../core/dbConfig.php';
require_once '../core/models.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'student') {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['user']['id'];

// Get student table ID
$stmt = $pdo->prepare("SELECT id FROM students WHERE user_id = ?");
$stmt->execute([$userId]);
$studentDetails = $stmt->fetch(PDO::FETCH_ASSOC);

$records = [];
if ($studentDetails) {
    $studentTableId = $studentDetails['id'];
    $student = new Student($_SESSION['user']['name'], $_SESSION['user']['email'], '', ''); // Course and year not strictly needed for history
    $records = $student->getAttendanceHistory($pdo, $studentTableId);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="shortcut icon" href="../images/student-icon.png" type="image/png">
    <title>Student Dashboard</title>
    <link href="../css/styles.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light text-dark">
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand d-flex align-items-center" href="index.php">
                <img src="../images/student-icon.png" alt="Student" width="40" height="40" class="me-2">
                <span class="fw-bold text-danger fs-4">Student Dashboard</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#studentNavbar" aria-controls="studentNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="studentNavbar">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link text-dark fw-medium" href="viewHistory.php">Attendance History</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-dark fw-medium" href="fileAttendance.php">File Attendance</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-dark fw-medium" href="submitExcuseLetter.php">Submit Excuse Letter</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-dark fw-medium" href="viewExcuseLetterHistory.php">Excuse Letter History</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-dark fw-medium" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h2 class="text-center">My Attendance History</h2>
        <?php if (empty($records)): ?>
            <div class="alert alert-info mt-4">No attendance records found.</div>
        <?php else: ?>
            <table class='table table-bordered mt-4'>
                <thead class="table-dark">
                    <tr>
                        <th>Date</th>
                        <th>Time In</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($records as $r): ?>
                        <tr>
                            <td><?= htmlspecialchars($r['date']) ?></td>
                            <td><?= $r['time_in'] ? htmlspecialchars($r['time_in']) : '—' ?></td>
                            <td><?= ucfirst(htmlspecialchars($r['status'])) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
