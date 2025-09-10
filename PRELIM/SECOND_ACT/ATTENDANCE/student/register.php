<?php
        session_start();
        require_once '../core/dbConfig.php';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'];
            $email = $_POST['email'];
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $courseName = $_POST['course'];
            $yearLevel = $_POST['year_level'];

            try {
                $pdo->beginTransaction();

                $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'student')");
                $stmt->execute([$name, $email, $password]);
                $userId = $pdo->lastInsertId();

                $stmt = $pdo->prepare("SELECT id FROM courses WHERE name = ?");
                $stmt->execute([$courseName]);
                $course = $stmt->fetch();
                $courseId = $course ? $course['id'] : null;

                if (!$courseId) {
                    $stmt = $pdo->prepare("INSERT INTO courses (name) VALUES (?)");
                    $stmt->execute([$courseName]);
                    $courseId = $pdo->lastInsertId();
                }

                $yearLevelInt = 0;
                if (strpos($yearLevel, '1st') !== false) $yearLevelInt = 1;
                else if (strpos($yearLevel, '2nd') !== false) $yearLevelInt = 2;
                else if (strpos($yearLevel, '3rd') !== false) $yearLevelInt = 3;
                else if (strpos($yearLevel, '4th') !== false) $yearLevelInt = 4;

                $stmt = $pdo->prepare("INSERT INTO students (user_id, course_id, year_level) VALUES (?, ?, ?)");
                $stmt->execute([$userId, $courseId, $yearLevelInt]);

                $pdo->commit();

                $_SESSION['user'] = [
                    'id' => $userId,
                    'name' => $name,
                    'email' => $email,
                    'role' => 'student'
                ];
                header("Location: index.php");
                exit;

            } catch (PDOException $e) {
                $pdo->rollBack();
                $error = "Registration failed: " . $e->getMessage();
            }
        }
        ?>
        

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Student Register</title>
    <link href="../css/styles.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .student-container {
            max-width: 400px;
            margin: 80px auto;
        }
    </style>
</head>
<body>
<div class="student-container">
    <div class="card shadow">
        <div class="card-body">
            <h2 class="card-title text-center mb-4">Register Student</h2>
            <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
            <form method="POST">
                <div class="mb-3">
                    <input type="text" name="name" class="form-control" placeholder="Student Name" required>
                </div>
                <div class="mb-3">
                    <input type="email" name="email" class="form-control" placeholder="Student Email" required>
                </div>
                <div class="mb-3">
                    <input type="password" name="password" class="form-control" placeholder="Student Password" required>
                </div>
                <div class="mb-3">
                    <input type="text" name="course" class="form-control" placeholder="Course (e.g., BSCS)" required>
                </div>
                <div class="mb-3">
                    <select name="year_level" class="form-control" required>
                        <option value="" disabled selected>Select Year Level</option>
                        <option value="1st Year">1st Year</option>
                        <option value="2nd Year">2nd Year</option>
                        <option value="3rd Year">3rd Year</option>
                        <option value="4th Year">4th Year</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary w-100">Register</button>
            </form>

            <br>
            <div class="text-center">
                <p class="mb-2">Already have an account?</p>
                <a href="login.php" class="btn btn-outline-secondary w-100">Login here</a>
            </div>
        </div>
    </div>
</div>
</body>
</html>
