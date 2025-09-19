<?php  

require_once 'Database.php';
/**
 * Class for handling User-related operations.
 * Inherits CRUD methods from the Database class.
 */
class User extends Database {

    /**
     * Starts a new session if one isn't already active.
     */
    public function startSession() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Checks if the username already exists in the database.
     * @param string $username The username to check.
     * @return bool True if username exists, false otherwise.
     */
    public function usernameExists($username) {
        $sql = "SELECT COUNT(*) as username_count FROM fiverr_clone_users WHERE username = ?";
        $count = $this->executeQuerySingle($sql, [$username]);
        if ($count['username_count'] > 0) {
            return true;
        }
        else {
            return false;
        }
    }
    

    /**
     * Registers a new user.
     * @param string $username The user's username.
     * @param string $email The user's email.
     * @param string $password The user's password.
     * @param string $contact_number The user's contact number.
     * @param bool $is_client Whether the user is a client (1) or freelancer (0).
     * @param bool $is_fiverr_admin Whether the user is a Fiverr administrator.
     * @return bool True on success, false on failure.
     */
    public function registerUser($username, $email, $password, $contact_number, $is_client = 0, $is_fiverr_admin = 0) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO fiverr_clone_users (username, email, password, is_client, contact_number, is_fiverr_admin) VALUES (?, ?, ?, ?, ?, ?)";
        try {
            $this->executeNonQuery($sql, [$username, $email, $hashed_password, $is_client, $contact_number, $is_fiverr_admin]);
            return true;
        } catch (\PDOException $e) {
            return false;
        }
    }

    /**
     * Logs in a user by verifying credentials.
     * @param string $email The user's email.
     * @param string $password The user's password.
     * @return bool True on success, false on failure.
     */
    public function loginUser($email, $password) {
        $sql = "SELECT user_id, username, password, is_client, is_fiverr_admin FROM fiverr_clone_users WHERE email = ?";
        $user = $this->executeQuerySingle($sql, [$email]);

        if ($user && password_verify($password, $user['password'])) {
            $this->startSession();
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['is_client'] = (bool)$user['is_client'];
            $_SESSION['is_fiverr_admin'] = (bool)$user['is_fiverr_admin']; // Store admin status
            return true;
        }
        return false;
    }

    /**
     * Checks if a user is currently logged in.
     * @return bool
     */
    public function isLoggedIn() {
        $this->startSession();
        return isset($_SESSION['user_id']);
    }

    /**
     * Checks if the logged-in user is an admin (client role).
     * @return bool
     */
    public function isAdmin() {
        $this->startSession();
        return isset($_SESSION['is_client']) && $_SESSION['is_client'];
    }

    /**
     * Checks if the logged-in user is a Fiverr administrator.
     * @return bool
     */
    public function isFiverrAdmin() {
        $this->startSession();
        return isset($_SESSION['is_fiverr_admin']) && $_SESSION['is_fiverr_admin'];
    }

    /**
     * Logs out the current user.
     */
    public function logout() {
        $this->startSession();
        session_unset();
        session_destroy();
    }

    /**
     * Retrieves fiverr_clone_users from the database.
     * @param int|null $id The user ID to retrieve, or null for all fiverr_clone_users.
     * @return array
     */
    public function getUsers($id = null) {
        if ($id) {
            $sql = "SELECT * FROM fiverr_clone_users WHERE user_id = ?";
            return $this->executeQuerySingle($sql, [$id]);
        }
        $sql = "SELECT * FROM fiverr_clone_users";
        return $this->executeQuery($sql);
    }

    /**
     * Updates a user's information.
     * @param string $contact_number The new contact number.
     * @param string $bio_description The new bio description.
     * @param int $user_id The user ID to update.
     * @param string $display_picture The new display picture filename.
     * @return int The number of affected rows.
     */
    public function updateUser($contact_number, $bio_description, $user_id, $display_picture="") {
        if (empty($display_picture)) {
            $sql = "UPDATE fiverr_clone_users SET contact_number = ?, bio_description = ? WHERE user_id = ?";
            return $this->executeNonQuery($sql, [$contact_number, $bio_description, $user_id]);
        }
        // Add logic to update display_picture if provided
        // For now, it only updates contact_number and bio_description
        return 0;
    }

    /**
     * Deletes a user.
     * @param int $id The user ID to delete.
     * @return int The number of affected rows.
     */
    public function deleteUser($id) {
        $sql = "DELETE FROM fiverr_clone_users WHERE user_id = ?";
        return $this->executeNonQuery($sql, [$id]);
    }
}

?>