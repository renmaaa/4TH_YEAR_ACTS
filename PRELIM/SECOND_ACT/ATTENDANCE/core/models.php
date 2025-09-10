<?php
class User {
    protected $name, $email, $role;

    public function __construct($name, $email, $role) {
        $this->name = $name;
        $this->email = $email;
        $this->role = $role;
    }
}

class Student extends User {
    public $course, $yearLevel;

    public function __construct($name, $email, $course, $yearLevel) {
        parent::__construct($name, $email, 'student');
        $this->course = $course;
        $this->yearLevel = $yearLevel;
    }

    public function fileAttendance(PDO $pdo, int $studentId, string $status): void {
        $stmt = $pdo->prepare("
            INSERT INTO attendance (student_id, date, time_in, status)
            VALUES (?, CURRENT_DATE, CURRENT_TIME(), ?)
        ");
        $stmt->execute([$studentId, $status]);
    }

    public function getAttendanceHistory(PDO $pdo, int $studentId): array {
        $stmt = $pdo->prepare("
            SELECT date, time_in, status
            FROM attendance
            WHERE student_id = ?
            ORDER BY date DESC
        ");
        $stmt->execute([$studentId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

class Admin extends User {
    public function __construct($name, $email) {
        parent::__construct($name, $email, 'admin');
    }

    public function addCourse(PDO $pdo, string $courseName): void {
        $stmt = $pdo->prepare("INSERT INTO courses (name) VALUES (?)");
        $stmt->execute([$courseName]);
    }

    public function viewAttendance(PDO $pdo, int $courseId, int $yearLevel): array {
        $stmt = $pdo->prepare("
            SELECT u.name, a.date, a.time_in, a.status
            FROM attendance a
            JOIN students s ON a.student_id = s.id
            JOIN users u ON s.user_id = u.id
            WHERE s.course_id = ? AND s.year_level = ?
            ORDER BY a.date DESC
        ");
        $stmt->execute([$courseId, $yearLevel]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
