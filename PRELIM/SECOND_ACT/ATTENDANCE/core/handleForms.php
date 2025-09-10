<?php
require_once 'dbConfig.php';
require_once 'models.php';

$allowedRoles = ['admin', 'student'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Secure password handling
    $course = $_POST['course'];
    $year_level = $_POST['year_level'];

    $stmt = $pdo->prepare("INSERT INTO users (name, email, password, course, year_level, role) VALUES (?, ?, ?, ?, ?, 'student')");
    $stmt->execute([$name, $email, $password, $course, $year_level]);

    $_SESSION['user'] = [
        'id' => $pdo->lastInsertId(),
        'name' => $name,
        'email' => $email,
        'course' => $course,
        'year_level' => $year_level,
        'role' => 'student'
    ];
    header("Location: index.php");
    exit;
}


        // Hash the password securely
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insert into users table
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
        $stmt->execute([$name, $email, $hashedPassword, $role]);
        $userId = $pdo->lastInsertId();

        // If student, insert into students table
        if ($role === 'student') {
            $courseId = intval($course);
            $stmt = $pdo->prepare("INSERT INTO students (user_id, course_id, year_level) VALUES (?, ?, ?)");
            $stmt->execute([$userId, $courseId, $year]);
        }

        $_SESSION['user'] = ['id' => $userId, 'name' => $name, 'role' => $role];
        header("Location: {$role}/index.php");
        exit;
    }

    // LOGIN BLOCK
    if (isset($_POST['login'])) {
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'];

        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            if (!in_array($user['role'], $allowedRoles)) {
                die("Invalid role.");
            }

            $_SESSION['user'] = ['id' => $user['id'], 'name' => $user['name'], 'role' => $user['role']];
            header("Location: {$user['role']}/index.php");
            exit;
        } else {
            echo "Invalid login credentials.";
        }
    }
}
// Handle course update
if (isset($_POST['update'])) {
    $id = $_POST['course_id'];
    $name = $_POST['course_name'];
    $stmt = $pdo->prepare("UPDATE courses SET name = ? WHERE id = ?");
    $stmt->execute([$name, $id]);
}

// Handle course delete
if (isset($_POST['delete'])) {
    $id = $_POST['course_id'];
    $stmt = $pdo->prepare("DELETE FROM courses WHERE id = ?");
    $stmt->execute([$id]);
}

// Fetch all courses
$courses = $pdo->query("SELECT * FROM courses")->fetchAll();
?>
