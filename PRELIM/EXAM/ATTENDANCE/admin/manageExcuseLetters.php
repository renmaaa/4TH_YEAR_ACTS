<?php
session_start();
require_once '../core/dbConfig.php';
require_once '../core/models.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$admin = new Admin($_SESSION['user']['name'], $_SESSION['user']['email']);

$selectedCourse = $_POST['course_filter'] ?? ($_GET['course_filter'] ?? 0); // 0 for all courses
$selectedStatus = $_POST['status_filter'] ?? ($_GET['status_filter'] ?? 'pending'); // Default to pending

// Handle approval/rejection
if ($_SERVER['REQUEST_METHOD'] === 'POST' && (isset($_POST['approve']) || isset($_POST['reject']))) {
    $letterId = $_POST['letter_id'];
    $newStatus = isset($_POST['approve']) ? 'approved' : 'rejected';
    $adminNotes = trim($_POST['admin_notes'] ?? '');

    try {
        $admin->updateExcuseLetterStatus($pdo, $letterId, $newStatus, $adminNotes);
        $_SESSION['message'] = "Excuse letter status updated to " . ucfirst($newStatus) . ".";
        // Redirect to prevent form resubmission and apply filters
        header("Location: manageExcuseLetters.php?course_filter={$selectedCourse}&status_filter={$selectedStatus}");
        exit;
    } catch (Exception $e) {
        $_SESSION['error'] = "Error updating excuse letter: " . htmlspecialchars($e->getMessage());
        header("Location: manageExcuseLetters.php?course_filter={$selectedCourse}&status_filter={$selectedStatus}");
        exit;
    }
}

// Fetch courses for the filter dropdown
$coursesStmt = $pdo->query("SELECT id, name FROM courses ORDER BY name ASC");
$courses = $coursesStmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch excuse letters based on filters
$excuseLetters = $admin->getExcuseLettersByCourseAndStatus($pdo, (int)$selectedCourse, $selectedStatus);

$message = $_SESSION['message'] ?? '';
$error = $_SESSION['error'] ?? '';
unset($_SESSION['message'], $_SESSION['error']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="shortcut icon" href="../images/icon.jpg" type="image/jpg">
    <title>Manage Excuse Letters</title>
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
            style="height: 200px; background-image: url('../images/admin-dashboard.png'); background-size: cover; background-position: center; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.2); position: relative;">
            <div style="position: absolute; width: 100%; height: 100%; background-color: rgba(0,0,0,0.4); border-radius: 10px;"></div>
            <h2 style="position: relative; z-index: 1;">Manage Excuse Letters</h2>
        </div>

        <?php if ($message): ?>
            <div class="alert alert-success mt-3"><?= $message ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="alert alert-danger mt-3"><?= $error ?></div>
        <?php endif; ?>

        <!-- Filter Form -->
        <form method="GET" class="card p-3 shadow-sm mt-4">
            <div class="row g-3 align-items-end">
                <div class="col-md-5">
                    <label for="course_filter" class="form-label">Filter by Course</label>
                    <select name="course_filter" id="course_filter" class="form-select">
                        <option value="0">All Courses</option>
                        <?php foreach ($courses as $course): ?>
                            <option value="<?= $course['id'] ?>" <?= ((int)$selectedCourse === (int)$course['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($course['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-5">
                    <label for="status_filter" class="form-label">Filter by Status</label>
                    <select name="status_filter" id="status_filter" class="form-select">
                        <option value="all" <?= ($selectedStatus === 'all') ? 'selected' : '' ?>>All Statuses</option>
                        <option value="pending" <?= ($selectedStatus === 'pending') ? 'selected' : '' ?>>Pending</option>
                        <option value="approved" <?= ($selectedStatus === 'approved') ? 'selected' : '' ?>>Approved</option>
                        <option value="rejected" <?= ($selectedStatus === 'rejected') ? 'selected' : '' ?>>Rejected</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Apply Filters</button>
                </div>
            </div>
        </form>

        <!-- Excuse Letters List -->
        <div class="mt-4">
            <?php if (empty($excuseLetters)): ?>
                <div class="alert alert-info">No excuse letters found for the selected filters.</div>
            <?php else: ?>
                <div class="row g-4">
                    <?php foreach ($excuseLetters as $letter): ?>
                        <div class="col-md-6">
                            <div class="card shadow-sm h-100">
                                <div class="card-header bg-dark text-white">
                                    <h5 class="mb-0">Subject: <?= htmlspecialchars($letter['subject']) ?></h5>
                                </div>
                                <div class="card-body">
                                    <p class="card-text"><strong>Student:</strong> <?= htmlspecialchars($letter['student_name']) ?></p>
                                    <p class="card-text"><strong>Course:</strong> <?= htmlspecialchars($letter['course_name']) ?> (Year <?= htmlspecialchars($letter['year_level']) ?>)</p>
                                    <p class="card-text"><strong>Submitted On:</strong> <?= htmlspecialchars($letter['submission_date']) ?></p>
                                    <p class="card-text"><strong>Reason:</strong> <?= nl2br(htmlspecialchars($letter['reason'])) ?></p>
                                    <?php if ($letter['file_path']): ?>
                                        <p class="card-text"><strong>Attachment:</strong> <a href="<?= htmlspecialchars($letter['file_path']) ?>" target="_blank" class="btn btn-sm btn-info">View Attachment</a></p>
                                    <?php endif; ?>
                                    <p class="card-text"><strong>Current Status:</strong>
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

                                    <?php if ($letter['status'] === 'pending'): ?>
                                        <hr>
                                        <form method="POST">
                                            <input type="hidden" name="letter_id" value="<?= $letter['id'] ?>">
                                            <input type="hidden" name="course_filter" value="<?= htmlspecialchars($selectedCourse) ?>">
                                            <input type="hidden" name="status_filter" value="<?= htmlspecialchars($selectedStatus) ?>">
                                            <div class="mb-3">
                                                <label for="admin_notes_<?= $letter['id'] ?>" class="form-label">Admin Notes (Optional)</label>
                                                <textarea name="admin_notes" id="admin_notes_<?= $letter['id'] ?>" class="form-control" rows="2"></textarea>
                                            </div>
                                            <button type="submit" name="approve" class="btn btn-success btn-sm me-2">Approve</button>
                                            <button type="submit" name="reject" class="btn btn-danger btn-sm">Reject</button>
                                        </form>
                                    <?php elseif ($letter['admin_notes']): ?>
                                        <p class="card-text mt-3"><strong>Admin Notes:</strong> <?= nl2br(htmlspecialchars($letter['admin_notes'])) ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <br>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>