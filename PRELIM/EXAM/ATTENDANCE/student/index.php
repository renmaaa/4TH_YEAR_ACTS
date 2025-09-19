<?php session_start(); ?>
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
            <a class="navbar-brand d-flex align-items-center" href="index.php   ">
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

    <div class="text-white d-flex align-items-center justify-content-center"
     style="background-image: url('../images/student-dashboard.png'); background-size: cover; background-position: center; height: 500px;">
    <h1 class="display-4 fw-bold">Welcome to the Student Dashboard</h1>
    </div>

        <div class="container my-5">
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <h5 class="card-title text-danger">View Attendance History</h5>
                            <p class="card-text">View your past attendance records.</p>
                            <a href="viewAttendanceHistory.php" class="btn btn-outline-danger">Go</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <h5 class="card-title text-danger">File Attendance</h5>
                            <p class="card-text">Submit your daily attendance.</p>
                            <a href="fileAttendance.php" class="btn btn-outline-danger">Go</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <h5 class="card-title text-danger">Excuse Letters</h5>
                            <p class="card-text">Submit and track your excuse letters.</p>
                            <a href="submitExcuseLetter.php" class="btn btn-outline-danger">Go</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>



<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
