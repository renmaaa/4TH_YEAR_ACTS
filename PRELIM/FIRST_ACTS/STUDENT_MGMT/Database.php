<?php
class Database {
    protected PDO $conn;

    public function __construct() {
        $dsn = "mysql:host=localhost;dbname=stdnt_mgmt;charset=utf8mb4";
        $user = "root";
        $pass = "";

        try {
            $this->conn = new PDO($dsn, $user, $pass);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

    protected function create(string $table, array $data): bool {
        $columns = implode(", ", array_keys($data));
        $placeholders = implode(", ", array_map(fn($key) => ":$key", array_keys($data)));

        $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($data);
    }

    protected function read(string $table, string $condition = "1"): array {
        $sql = "SELECT * FROM $table WHERE $condition";
        return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    protected function update(string $table, array $data, string $condition): bool {
        $set = implode(", ", array_map(fn($key) => "$key = :$key", array_keys($data)));
        $sql = "UPDATE $table SET $set WHERE $condition";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($data);
    }

    protected function delete(string $table, string $condition): bool {
        $sql = "DELETE FROM $table WHERE $condition";
        return $this->conn->exec($sql) !== false;
    }
}
