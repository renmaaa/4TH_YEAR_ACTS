<?php  
require_once 'classes/Article.php';
require_once 'classes/Database.php';
require_once 'classes/User.php';

$databaseObj= new Database();
$userObj = new User($databaseObj->connect()); // Pass PDO connection to User
$articleObj = new Article($databaseObj->connect()); // Pass PDO connection to Article

$userObj->startSession();

// Fetch categories for use in forms
$categories = $articleObj->getCategories();
?>
