<?php
session_start();
require_once '../core/dbConfig.php';
require_once '../core/models.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'student') {
    header("Location: login.php");
    exit;
}

$message = '';
$error = '';

$userId = $_SESSION['user']['id'];
$stmt = $pdo->prepare("SELECT id, course_id, year_level FROM students WHERE user_id = ?");
$stmt->execute([$userId]);
$studentDetails = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$studentDetails) {
    $error = "Student record not found. Please ensure your student profile is complete.";
} else {
    $studentTableId = $studentDetails['id'];
    $student = new Student($_SESSION['user']['name'], $_SESSION['user']['email'], $studentDetails['course_id'], $studentDetails['year_level']);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $status = $_POST['status'] ?? '';

        if (!in_array($status, ['present', 'late', 'excused', 'absent'])) {
            $error = "Invalid attendance status selected.";
        } else {
            try {
                $student->fileAttendance($pdo, $studentTableId, $status);
                $message = "Attendance submitted successfully as '$status'.";
            } catch (Exception $e) {
                $error = "Error submitting attendance: " . htmlspecialchars($e->getMessage());
            }
        }
    }
}

$records = [];
if ($studentDetails) {
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
                        <a class="nav-link text-dark fw-medium" href="viewHistory.php">History</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-dark fw-medium" href="fileAttendance.php">Attendance</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-dark fw-medium" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h2 class="text-center">File Attendance</h2>
        <?php if ($message): ?>
            <div class="alert alert-success mt-3"><?= $message ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="alert alert-danger mt-3"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label for="status" class="form-label">Attendance Status</label>
                <select class="form-select" name="status" id="status" required>
                    <option value="">-- Select Status --</option>
                    <option value="present">Present</option>
                    <option value="late">Late</option>
                    <option value="excused">Excused</option>
                    <option value="absent">Absent</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Submit Attendance</button>
        </form>

        <?php if (!empty($records)): ?>
            <h4 class="mt-5">Your Attendance History</h4>
            <table class="table table-bordered table-striped mt-3">
                <thead class="table-dark">
                    <tr>
                        <th>Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($records as $record): ?>
                        <tr>
                            <td><?= htmlspecialchars($record['date']) ?></td>
                            <td><?= ucfirst(htmlspecialchars($record['status'])) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
