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
     * Constructor establishes the PDO connection.
     */
    public function __construct() {
        $dsn = "mysql:host={$this->host};dbname={$this->db};charset={$this->charset}";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        try {
            $this->pdo = new PDO($dsn, $this->user, $this->pass, $options);
        } catch (\PDOException $e) {
            throw new \PDOException("Database connection failed: " . $e->getMessage(), (int)$e->getCode());
        }
    }

    /**
     * Returns the active PDO connection.
     * @return PDO
     */
    public function connect() {
        return $this->pdo;
    }

    /**
     * Executes a prepared SELECT query and returns all rows.
     * @param string $sql
     * @param array $params
     * @return array
     */
    public function executeQuery($sql, $params = []) {
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
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }

    /**
     * Returns the ID of the last inserted row.
     * @return string
     */
    public function lastInsertId() {
        return $this->pdo->lastInsertId();
    }
}
?>
