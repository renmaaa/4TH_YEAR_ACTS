<?php
require_once 'course.php';

class Student {
    private string $name;
    private array $courses = [];

    public function __construct(string $name = 'Renma') {
        $this->name = $name;
    }

    public function getName(): string {
        return $this->name;
    }

    public function enrollCourse(Course $course): void {
        foreach ($this->courses as $c) {
            if ($c->getName() === $course->getName()) {
                return; // Prevent duplicates
            }
        }
        $this->courses[] = $course;
    }

    public function dropCourse(string $courseName): void {
        foreach ($this->courses as $index => $course) {
            if ($course->getName() === $courseName) {
                unset($this->courses[$index]);
                $this->courses = array_values($this->courses); // Reindex
                break;
            }
        }
    }

    public function getCourses(): array {
        return $this->courses;
    }

    public function getTotalFee(): int {
        return count($this->courses) * 1450;
    }
}
