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
     * Establishes and returns a PDO connection.
     * @return PDO|null
     */
    public function connect() {
        if ($this->pdo !== null) {
            return $this->pdo;
        }

        try {
            $dsn = "mysql:host={$this->host};dbname={$this->db};charset={$this->charset}";
            $this->pdo = new PDO($dsn, $this->user, $this->pass);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $this->pdo;
        } catch (PDOException $e) {
            error_log("Database connection failed: " . $e->getMessage());
            echo "Connection failed: " . $e->getMessage();
            return null;
        }
    }

    /**
     * Executes a prepared SELECT query and returns all rows.
     * @param string $sql
     * @param array $params
     * @return array
     */
    public function executeQuery($sql, $params = []) {
        $this->connect(); // Ensure connection is established
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Executes a prepared SELECT query and returns a single row.
     * @param string $sql
     * @param array $params
     * @return array|null
     */
    public function executeQuerySingle($sql, $params = []) {
        $this->connect();
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch();
    }

    /**
     * Executes a non-SELECT query (INSERT, UPDATE, DELETE).
     * @param string $sql
     * @param array $params
     * @return bool
     */
    public function executeNonQuery($sql, $params = []) {
        $this->connect();
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }

    /**
     * Returns the ID of the last inserted row.
     * @return string
     */
    public function lastInsertId() {
        $this->connect();
        return $this->pdo->lastInsertId();
    }
}
?>