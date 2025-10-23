<?php
session_start();
require 'config.php';

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

        $stmt = $pdo->prepare("SELECT id, username, firstname, lastname, is_admin, password FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user'] = $user;
            echo json_encode(['success' => true, 'message' => 'Login successful.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid username or password.']);
        }
        break;

    case 'register':
        $username = trim($_POST['username'] ?? '');
        $firstname = trim($_POST['firstname'] ?? '');
        $lastname = trim($_POST['lastname'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        $isAdmin = isset($_POST['is_admin']) ? 1 : 0;

        // Server-side validations
        if (empty($username) || empty($firstname) || empty($lastname) || empty($password)) {
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

        // Check username uniqueness
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->execute([$username]);
        if ($stmt->fetch()) {
            echo json_encode(['success' => false, 'message' => 'Username already exists.']);
            exit;
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (username, firstname, lastname, is_admin, password) VALUES (?, ?, ?, ?, ?)");
        if ($stmt->execute([$username, $firstname, $lastname, $isAdmin, $hashedPassword])) {
            echo json_encode(['success' => true, 'message' => 'Registration successful.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Registration failed.']);
        }
        break;

    case 'get_users':
        if (!isset($_SESSION['user']) || !$_SESSION['user']['is_admin']) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized.']);
            exit;
        }

        $search = $_POST['search'] ?? '';
        $query = "SELECT id, username, firstname, lastname, is_admin, date_added FROM users";
        $params = [];
        if (!empty($search)) {
            $query .= " WHERE username LIKE ? OR firstname LIKE ? OR lastname LIKE ?";
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

        // Similar to register, but for adding users
        $username = trim($_POST['username'] ?? '');
        $firstname = trim($_POST['firstname'] ?? '');
        $lastname = trim($_POST['lastname'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        $isAdmin = isset($_POST['is_admin']) ? 1 : 0;

        if (empty($username) || empty($firstname) || empty($lastname) || empty($password)) {
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
        $stmt = $pdo->prepare("INSERT INTO users (username, firstname, lastname, is_admin, password) VALUES (?, ?, ?, ?, ?)");
        if ($stmt->execute([$username, $firstname, $lastname, $isAdmin, $hashedPassword])) {
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
