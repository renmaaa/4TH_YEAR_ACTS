<?php
require_once 'Database.php';

class Attendance extends Database {
    public function markAttendance(int $studentId, string $date): bool {
        return $this->create("attendance", ["student_id" => $studentId, "date" => $date]);
    }

    public function getAttendance(): array {
        return $this->read("attendance");
    }

    public function updateAttendance(int $id, string $date): bool {
        return $this->update("attendance", ["date" => $date], "id = $id");
    }

    public function deleteAttendance(int $id): bool {
        return $this->delete("attendance", "id = $id");
    }
}
