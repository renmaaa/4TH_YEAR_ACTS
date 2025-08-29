<?php
require_once 'Student.php';
$student = new Student();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add'])) {
        $student->addStudent($_POST['name'], $_POST['email']);
    } elseif (isset($_POST['update'])) {
        $student->updateStudent($_POST['id'], $_POST['name'], $_POST['email']);
    } elseif (isset($_POST['delete'])) {
        $student->deleteStudent($_POST['id']);
    }
}

$students = $student->getStudents();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Management</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>Student CRUD</h1>
    <form method="post">
        <input type="hidden" name="id" value="">
        <input type="text" name="name" placeholder="Name" required>
        <input type="email" name="email" placeholder="Email" required>
        <button name="add">Add</button>
        <button name="update">Update</button>
        <button name="delete">Delete</button>
    </form>

    <table>
        <tr><th>ID</th><th>Name</th><th>Email</th></tr>
        <?php foreach ($students as $s): ?>
            <tr>
                <td><?= $s['id'] ?></td>
                <td><?= $s['name'] ?></td>
                <td><?= $s['email'] ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>
</body>
</html>
