<?php  
/**
 * Handles secure database connections and queries using PDO.
 */
class Database {
    protected $pdo;
    private $host = 'localhost';
    private $db = 'newspaper';
    private $user = 'root';
    private $pass = '';
    private $charset = 'utf8mb4';

    /**
     * Establishes a PDO connection and assigns it to $this->pdo.
     * @return PDO|null The PDO connection or null on failure.
     */
    public function connect() {
        try {
            $dsn = "mysql:host={$this->host};dbname={$this->db};charset={$this->charset}";
            $this->pdo = new PDO($dsn, $this->user, $this->pass);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $this->pdo;
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
            return null;
        }
    }

    /**
     * Executes a prepared statement and returns the result.
     * @param string $sql The SQL query to execute.
     * @param array $params The parameters to bind to the query.
     * @return array The fetched data.
     */
    protected function executeQuery($sql, $params = []) {
        if ($this->pdo === null) {
            $this->connect();
        }
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Executes a prepared statement and returns a single row.
     * @param string $sql The SQL query to execute.
     * @param array $params The parameters to bind to the query.
     * @return array|null The single fetched row, or null if not found.
     */
    protected function executeQuerySingle($sql, $params = []) {
        if ($this->pdo === null) {
            $this->connect();
        }
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch();
    }

    /**
     * Executes a non-query statement (INSERT, UPDATE, DELETE).
     * @param string $sql The SQL query to execute.
     * @param array $params The parameters to bind to the query.
     * @return bool True on success, false on failure.
     */
    protected function executeNonQuery($sql, $params = []) {
        if ($this->pdo === null) {
            $this->connect();
        }
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }

    /**
     * Returns the ID of the last inserted row.
     * @return string
     */
    protected function lastInsertId() {
        if ($this->pdo === null) {
            $this->connect();
        }
        return $this->pdo->lastInsertId();
    }
}
?>