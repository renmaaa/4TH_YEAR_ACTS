<?php
session_start();
require_once '../core/dbConfig.php';
require_once '../core/models.php'; // Added this line as Admin class is used

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Handle search
$searchTerm = $_GET['search'] ?? '';
$stmt = $pdo->prepare("SELECT * FROM courses WHERE name LIKE :search ORDER BY id ASC");
$stmt->execute(['search' => "%$searchTerm%"]);
$courses = $stmt->fetchAll();

// Handle form actions
if (isset($_POST['add'])) {
    $newCourse = trim($_POST['new_course']);
    if ($newCourse !== '') {
        $admin = new Admin($_SESSION['user']['name'], $_SESSION['user']['email']);
        $admin->addCourse($pdo, $newCourse);
        header("Location: manageCourse.php"); // Redirect after add
        exit;
    }
}

if (isset($_POST['update'])) {
    $id = $_POST['course_id'];
    $name = trim($_POST['course_name']);
    $stmt = $pdo->prepare("UPDATE courses SET name = :name WHERE id = :id");
    $stmt->execute(['name' => $name, 'id' => $id]);
    header("Location: manageCourse.php"); // Redirect after update
    exit;
}

if (isset($_POST['delete'])) {
    $id = $_POST['course_id'];
    $stmt = $pdo->prepare("DELETE FROM courses WHERE id = :id");
    $stmt->execute(['id' => $id]);
    header("Location: manageCourse.php"); // Redirect after delete
    exit;
}

// No unconditional redirect here
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
                    <li class="nav-item"><a class="nav-link text-dark fw-medium" href="logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="text-center text-white d-flex align-items-center justify-content-center"
            style="height: 200px; background-image: url('../images/course-dashboard.png'); background-size: cover; background-position: center; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.2); position: relative;">
            <div style="position: absolute; width: 100%; height: 100%; background-color: rgba(0,0,0,0.4); border-radius: 10px;"></div>
            <h2 style="position: relative; z-index: 1;">Manage Courses</h2>
        </div>

        <!-- Search Form -->
        <form method="GET" class="row g-2 mt-4">
            <div class="col-md-8">
                <input type="text" name="search" class="form-control" placeholder="Search course..." value="<?= htmlspecialchars($searchTerm) ?>">
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary w-100">Search</button>
            </div>
        </form>

        <!-- Add Course Form -->
        <form method="POST" class="row g-2 mt-3">
            <div class="col-md-8">
                <input type="text" name="new_course" class="form-control" placeholder="Add new course">
            </div>
            <div class="col-md-4">
                <button type="submit" name="add" class="btn btn-success w-100">Add Course</button>
            </div>
        </form>

        <!-- Course Table -->
        <div class="card shadow-sm mt-4">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0">Course List</h5>
            </div>
            <div class="card-body p-0">
                <table class="table table-bordered mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Course Name</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($courses as $course): ?>
                        <tr>
                            <form method="POST">
                                <td><?= $course['id'] ?></td>
                                <td>
                                    <input type="text" name="course_name" value="<?= htmlspecialchars($course['name']) ?>" class="form-control">
                                    <input type="hidden" name="course_id" value="<?= $course['id'] ?>">
                                </td>
                                <td>
                                    <button type="submit" name="update" class="btn btn-sm btn-success">Update</button>
                                    <button type="submit" name="delete" class="btn btn-sm btn-danger" onclick="return confirm('Delete this course?')">Delete</button>
                                </td>
                            </form>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <br>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
