<?php
require_once 'course.php';
require_once 'student.php';
session_start();

$availableCourses = ["Mathematics", "Science", "Literature", "History", "Art", "Computer Science"];

if (isset($_SESSION['student']) && $_SESSION['student'] instanceof Student) {
    $student = $_SESSION['student'];
} else {
    $student = new Student();
    $_SESSION['student'] = $student;
}

if (!empty($_POST['add_course']) && in_array($_POST['add_course'], $availableCourses)) {
    $student->enrollCourse(new Course($_POST['add_course']));
}

if (!empty($_POST['drop_course'])) {
    $student->dropCourse($_POST['drop_course']);
}

if (isset($_POST['reset'])) {
    session_destroy();
    header("Location: index.php");
    exit;
}

$_SESSION['student'] = $student;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Enrollment</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>Welcome, <?= htmlspecialchars($student->getName()) ?>!</h1>

    <form method="post">
        <label for="add_course">Enroll in a course:</label>
        <select name="add_course" id="add_course">
            <?php foreach ($availableCourses as $course): ?>
                <option value="<?= $course ?>"><?= $course ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Add</button>
    </form>

    <form method="post">
        <label for="drop_course">Drop a course:</label>
        <input type="text" name="drop_course" id="drop_course" placeholder="Course name">
        <button type="submit">Drop</button>
    </form>

    <form method="post">
        <button name="reset" type="submit">Reset Enrollment</button>
    </form>

    <h2>Available Courses</h2>
    <table>
        <tr><th>Course</th><th>Fee (₱)</th></tr>
        <?php foreach ($availableCourses as $course): ?>
            <tr><td><?= $course ?></td><td>₱1450</td></tr>
        <?php endforeach; ?>
    </table>

    <h2>Enrolled Courses</h2>
    <table>
        <tr><th>Course</th><th>Fee (₱)</th></tr>
        <?php foreach ($student->getCourses() as $course): ?>
            <tr><td><?= $course->getName() ?></td><td>₱<?= $course->getCost() ?></td></tr>
        <?php endforeach; ?>
        <tr><td><strong>Total</strong></td><td><strong>₱<?= $student->getTotalFee() ?></strong></td></tr>
    </table>
</div>
</body>
</html>
