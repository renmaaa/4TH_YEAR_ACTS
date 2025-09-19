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
        $subject = trim($_POST['subject'] ?? '');
        $reason = trim($_POST['reason'] ?? '');
        $filePath = null;

        if (empty($subject) || empty($reason)) {
            $error = "Subject and Reason cannot be empty.";
        } else {
            // Handle file upload
            if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] === UPLOAD_ERR_OK) {
                $targetDir = "../uploads/"; // Directory to store uploads
                if (!is_dir($targetDir)) {
                    mkdir($targetDir, 0777, true); // Create directory if it doesn't exist
                }

                $fileName = basename($_FILES['attachment']['name']);
                $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                $allowedExtensions = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png'];

                if (!in_array($fileExtension, $allowedExtensions)) {
                    $error = "Invalid file type. Only PDF, DOC, DOCX, JPG, JPEG, PNG are allowed.";
                } elseif ($_FILES['attachment']['size'] > 5 * 1024 * 1024) { // 5MB limit
                    $error = "File size exceeds 5MB limit.";
                } else {
                    $newFileName = uniqid('excuse_') . '.' . $fileExtension;
                    $targetFilePath = $targetDir . $newFileName;

                    if (move_uploaded_file($_FILES['attachment']['tmp_name'], $targetFilePath)) {
                        $filePath = $targetFilePath;
                    } else {
                        $error = "Error uploading file.";
                    }
                }
            }

            if (empty($error)) {
                try {
                    $student->submitExcuseLetter($pdo, $studentTableId, $subject, $reason, $filePath);
                    $message = "Excuse letter submitted successfully. It is now pending admin approval.";
                    // Clear form fields after successful submission
                    $_POST = array();
                } catch (Exception $e) {
                    $error = "Error submitting excuse letter: " . htmlspecialchars($e->getMessage());
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="shortcut icon" href="../images/student-icon.png" type="image/png">
    <title>Submit Excuse Letter</title>
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
                        <a class="nav-link text-dark fw-medium" href="viewHSistory.php">Attendance History</a>
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
        <h2 class="text-center">Submit Excuse Letter</h2>
        <?php if ($message): ?>
            <div class="alert alert-success mt-3"><?= $message ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="alert alert-danger mt-3"><?= $error ?></div>
        <?php endif; ?>

        <?php if (!$studentDetails): ?>
            <div class="alert alert-warning mt-3">Your student profile is incomplete. You cannot submit an excuse letter.</div>
        <?php else: ?>
            <form method="POST" enctype="multipart/form-data" class="card p-4 shadow-sm">
                <div class="mb-3">
                    <label for="subject" class="form-label">Subject</label>
                    <input type="text" name="subject" id="subject" class="form-control" placeholder="e.g., Absence for Math Class" value="<?= htmlspecialchars($_POST['subject'] ?? '') ?>" required>
                </div>
                <div class="mb-3">
                    <label for="reason" class="form-label">Reason</label>
                    <textarea name="reason" id="reason" class="form-control" rows="5" placeholder="Provide a detailed reason for your absence..." required><?= htmlspecialchars($_POST['reason'] ?? '') ?></textarea>
                </div>
                <div class="mb-3">
                    <label for="attachment" class="form-label">Attachment (Optional)</label>
                    <input type="file" name="attachment" id="attachment" class="form-control">
                    <small class="form-text text-muted">Max 5MB. Allowed types: PDF, DOC, DOCX, JPG, JPEG, PNG.</small>
                </div>
                <button type="submit" class="btn btn-primary w-100">Submit Excuse Letter</button>
            </form>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
