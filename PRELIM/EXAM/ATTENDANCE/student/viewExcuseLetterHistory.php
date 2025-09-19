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

$excuseLetters = [];
if ($studentDetails) {
    $studentTableId = $studentDetails['id'];
    $student = new Student($_SESSION['user']['name'], $_SESSION['user']['email'], '', ''); // Course and year not strictly needed for history
    $excuseLetters = $student->getExcuseLetterHistory($pdo, $studentTableId);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="shortcut icon" href="../images/student-icon.png" type="image/png">
    <title>My Excuse Letters</title>
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
        <h2 class="text-center">My Excuse Letter History</h2>
        <?php if (empty($excuseLetters)): ?>
            <div class="alert alert-info mt-4">No excuse letters submitted yet.</div>
        <?php else: ?>
            <div class="row g-4 mt-3">
                <?php foreach ($excuseLetters as $letter): ?>
                    <div class="col-md-6">
                        <div class="card shadow-sm h-100">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">Subject: <?= htmlspecialchars($letter['subject']) ?></h5>
                            </div>
                            <div class="card-body">
                                <p class="card-text"><strong>Submitted On:</strong> <?= htmlspecialchars($letter['submission_date']) ?></p>
                                <p class="card-text"><strong>Reason:</strong> <?= nl2br(htmlspecialchars($letter['reason'])) ?></p>
                                <?php if ($letter['file_path']): ?>
                                    <p class="card-text"><strong>Attachment:</strong> <a href="<?= htmlspecialchars($letter['file_path']) ?>" target="_blank" class="btn btn-sm btn-info">View Attachment</a></p>
                                <?php endif; ?>
                                <p class="card-text"><strong>Status:</strong>
                                    <?php
                                        $statusClass = '';
                                        switch ($letter['status']) {
                                            case 'pending': $statusClass = 'badge bg-warning text-dark'; break;
                                            case 'approved': $statusClass = 'badge bg-success'; break;
                                            case 'rejected': $statusClass = 'badge bg-danger'; break;
                                        }
                                    ?>
                                    <span class="<?= $statusClass ?>"><?= ucfirst(htmlspecialchars($letter['status'])) ?></span>
                                </p>
                                <?php if ($letter['admin_notes']): ?>
                                    <p class="card-text"><strong>Admin Notes:</strong> <?= nl2br(htmlspecialchars($letter['admin_notes'])) ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>