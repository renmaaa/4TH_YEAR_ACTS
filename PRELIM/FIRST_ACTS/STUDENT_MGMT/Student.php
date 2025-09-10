<?php
require_once 'Database.php';

class Student extends Database {
    public function addStudent(string $name, string $email): bool {
        return $this->create("students", ["name" => $name, "email" => $email]);
    }

    public function getStudents(): array {
        return $this->read("students");
    }

    public function updateStudent(int $id, string $name, string $email): bool {
        return $this->update("students", ["name" => $name, "email" => $email], "id = $id");
    }

    public function deleteStudent(int $id): bool {
        return $this->delete("students", "id = $id");
    }
}
