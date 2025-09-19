<?php
require_once 'Database.php'; // Adjust path if needed

class User extends Database {
    protected $pdo;

    public function __construct($pdo = null) {
        if ($pdo instanceof PDO) {
            $this->pdo = $pdo;
        } else {
            $this->pdo = $this->connect();
        }

        if ($this->pdo === null) {
            throw new Exception("Database connection failed in User class.");
        }
    }

    public function createNotification($user_id, $message) {
        $sql = "INSERT INTO notifications (user_id, message) VALUES (?, ?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$user_id, $message]);
    }

    public function getNotifications($user_id) {
        $sql = "SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function markNotificationAsRead($notification_id) {
        $sql = "UPDATE notifications SET is_read = TRUE WHERE notification_id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$notification_id]);
    }

    public function startSession() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function usernameExists($username) {
        $sql = "SELECT COUNT(*) as username_count FROM school_publication_users WHERE username = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$username]);
        $count = $stmt->fetch(PDO::FETCH_ASSOC);
        return $count['username_count'] > 0;
    }

    public function registerUser($username, $email, $password, $is_admin = false) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO school_publication_users (username, email, password, is_admin) VALUES (?, ?, ?, ?)";
        try {
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([$username, $email, $hashed_password, (int)$is_admin]);
        } catch (\PDOException $e) {
            error_log("Registration error: " . $e->getMessage());
            return false;
        }
    }

    public function loginUser($email, $password) {
        $sql = "SELECT user_id, username, password, is_admin FROM school_publication_users WHERE email = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $this->startSession();
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['is_admin'] = (bool)$user['is_admin'];
            return true;
        }
        return false;
    }

    public function isLoggedIn() {
        $this->startSession();
        return isset($_SESSION['user_id']);
    }

    public function isAdmin() {
        $this->startSession();
        return isset($_SESSION['is_admin']) && $_SESSION['is_admin'];
    }

    public function isAdminById($user_id) {
        $sql = "SELECT is_admin FROM school_publication_users WHERE user_id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$user_id]);
        return $stmt->fetchColumn() == 1;
    }

    public function logout() {
        $this->startSession();
        session_unset();
        session_destroy();
    }

    public function getUsers($id = null) {
        if ($id) {
            $sql = "SELECT user_id, username, email, is_admin FROM school_publication_users WHERE user_id = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
        $sql = "SELECT user_id, username, email, is_admin FROM school_publication_users";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateUser($id, $username, $email, $is_admin) {
        $sql = "UPDATE school_publication_users SET username = ?, email = ?, is_admin = ? WHERE user_id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$username, $email, (int)$is_admin, $id]);
    }

    public function deleteUser($id) {
        $sql = "DELETE FROM school_publication_users WHERE user_id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id]);
    }

    public function getUserByEmail($email) {
        $sql = "SELECT * FROM school_publication_users WHERE email = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}