<?php
session_start();
require_once 'config.php';

header('Content-Type: application/json');

$action = $_POST['action'] ?? '';

switch ($action) {
    case 'login':
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($username) || empty($password)) {
            echo json_encode(['success' => false, 'message' => 'Username and password are required.']);
            exit;
        }

        $stmt = $pdo->prepare("SELECT id, username, first_name, last_name, is_admin, password FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            unset($user['password']);
            $_SESSION['user'] = $user;
            echo json_encode(['success' => true, 'message' => 'Login successful.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid username or password.']);
        }
        break;

    case 'register':
        $username = trim($_POST['username'] ?? '');
        $first_name = trim($_POST['first_name'] ?? '');
        $last_name = trim($_POST['last_name'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        $isAdmin = ($_POST['is_admin'] ?? '') === '1' ? 1 : 0;

        if (empty($username) || empty($first_name) || empty($last_name) || empty($password)) {
            echo json_encode(['success' => false, 'message' => 'All fields are required.']);
            exit;
        }
        if (strlen($password) < 8) {
            echo json_encode(['success' => false, 'message' => 'Password must be at least 8 characters.']);
            exit;
        }
        if ($password !== $confirmPassword) {
            echo json_encode(['success' => false, 'message' => 'Passwords do not match.']);
            exit;
        }

        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->execute([$username]);
        if ($stmt->fetch()) {
            echo json_encode(['success' => false, 'message' => 'Username already exists.']);
            exit;
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $dateAdded = date('Y-m-d H:i:s');
        $stmt = $pdo->prepare("INSERT INTO users (username, first_name, last_name, is_admin, password, date_added) VALUES (?, ?, ?, ?, ?, ?)");
        if ($stmt->execute([$username, $first_name, $last_name, $isAdmin, $hashedPassword, $dateAdded])) {
            echo json_encode(['success' => true, 'message' => 'Registration successful.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Registration failed.']);
        }
        break;

    case 'get_users':
        if (!isset($_SESSION['user'])) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized.']);
            exit;
        }

        $search = $_POST['search'] ?? '';
        $query = "SELECT id, username, first_name, last_name, is_admin, date_added FROM users";
        $params = [];
        if (!empty($search)) {
            $query .= " WHERE username LIKE ? OR first_name LIKE ? OR last_name LIKE ?";
            $params = ["%$search%", "%$search%", "%$search%"];
        }

        $stmt = $pdo->prepare($query);
        $stmt->execute($params);
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'users' => $users]);
        break;

    case 'add_user':
        if (!isset($_SESSION['user']) || !$_SESSION['user']['is_admin']) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized.']);
            exit;
        }

        $username = trim($_POST['username'] ?? '');
        $first_name = trim($_POST['first_name'] ?? '');
        $last_name = trim($_POST['last_name'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        $isAdmin = ($_POST['is_admin'] ?? '') === '1' ? 1 : 0;

        if (empty($username) || empty($first_name) || empty($last_name) || empty($password)) {
            echo json_encode(['success' => false, 'message' => 'All fields are required.']);
            exit;
        }
        if (strlen($password) < 8) {
            echo json_encode(['success' => false, 'message' => 'Password must be at least 8 characters.']);
            exit;
        }
        if ($password !== $confirmPassword) {
            echo json_encode(['success' => false, 'message' => 'Passwords do not match.']);
            exit;
        }

        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->execute([$username]);
        if ($stmt->fetch()) {
            echo json_encode(['success' => false, 'message' => 'Username already exists.']);
            exit;
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $dateAdded = date('Y-m-d H:i:s');
        $stmt = $pdo->prepare("INSERT INTO users (username, first_name, last_name, is_admin, password, date_added) VALUES (?, ?, ?, ?, ?, ?)");
        if ($stmt->execute([$username, $first_name, $last_name, $isAdmin, $hashedPassword, $dateAdded])) {
            echo json_encode(['success' => true, 'message' => 'User added successfully.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to add user.']);
        }
        break;

    case 'logout':
        session_destroy();
        echo json_encode(['success' => true, 'message' => 'Logged out.']);
        break;

    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action.']);
}
?>
