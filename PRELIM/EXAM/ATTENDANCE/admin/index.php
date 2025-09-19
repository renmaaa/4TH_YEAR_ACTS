<?php session_start(); ?>
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
            <a class="navbar-brand d-flex align-items-center" href="index.php   ">
                <img src="../images/icon.jpg" alt="Admin" width="40" height="40" class="me-2">
                <span class="fw-bold text-danger fs-4">Admin Dashboard</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar" aria-controls="adminNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="adminNavbar">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link text-dark fw-medium" href="manageCourse.php">Courses</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-dark fw-medium" href="viewAttendance.php">Attendance</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-dark fw-medium" href="manageExcuseLetters.php">Excuse Letters</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-dark fw-medium" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="text-white d-flex align-items-center justify-content-center"
     style="background-image: url('../images/admin-dashboard.png'); background-size: cover; background-position: center; height: 500px;">
    <h1 class="display-4 fw-bold">Welcome to the Admin Dashboard</h1>
    </div>

        <div class="container my-5">
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <h5 class="card-title text-danger">Manage Courses</h5>
                            <p class="card-text">Create, update, or delete course offerings.</p>
                            <a href="manageCourse.php" class="btn btn-outline-danger">Go</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <h5 class="card-title text-danger">View Attendance</h5>
                            <p class="card-text">Track student attendance records efficiently.</p>
                            <a href="viewAttendance.php" class="btn btn-outline-danger">Go</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <h5 class="card-title text-danger">Manage Excuse Letters</h5>
                            <p class="card-text">Review and manage student excuse letter submissions.</p>
                            <a href="manageExcuseLetters.php" class="btn btn-outline-danger">Go</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>



<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>