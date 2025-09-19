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

    // New method for students to submit an excuse letter
    public function submitExcuseLetter(PDO $pdo, int $studentId, string $subject, string $reason, ?string $filePath = null): void {
        $stmt = $pdo->prepare("
            INSERT INTO excuse_letters (student_id, subject, reason, file_path)
            VALUES (?, ?, ?, ?)
        ");
        $stmt->execute([$studentId, $subject, $reason, $filePath]);
    }

    // New method for students to view their excuse letter history
    public function getExcuseLetterHistory(PDO $pdo, int $studentId): array {
        $stmt = $pdo->prepare("
            SELECT id, subject, reason, file_path, submission_date, status, admin_notes
            FROM excuse_letters
            WHERE student_id = ?
            ORDER BY submission_date DESC
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

    // New method for admin to view excuse letters by course and status
    public function getExcuseLettersByCourseAndStatus(PDO $pdo, ?int $courseId = null, ?string $status = null): array {
        $sql = "
            SELECT el.id, u.name AS student_name, c.name AS course_name, s.year_level, el.subject, el.reason, el.file_path, el.submission_date, el.status, el.admin_notes
            FROM excuse_letters el
            JOIN students s ON el.student_id = s.id
            JOIN users u ON s.user_id = u.id
            JOIN courses c ON s.course_id = c.id
            WHERE 1=1
        ";
        $params = [];

        if ($courseId !== null && $courseId !== 0) { // 0 can be used for "All Courses"
            $sql .= " AND s.course_id = ?";
            $params[] = $courseId;
        }
        if ($status !== null && $status !== 'all') { // 'all' can be used for "All Statuses"
            $sql .= " AND el.status = ?";
            $params[] = $status;
        }

        $sql .= " ORDER BY el.submission_date DESC";

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // New method for admin to update excuse letter status
    public function updateExcuseLetterStatus(PDO $pdo, int $letterId, string $status, ?string $adminNotes = null): void {
        $stmt = $pdo->prepare("
            UPDATE excuse_letters
            SET status = ?, admin_notes = ?
            WHERE id = ?
        ");
        $stmt->execute([$status, $adminNotes, $letterId]);
    }
}
?>
