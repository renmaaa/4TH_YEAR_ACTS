<?php
require_once 'classes/Database.php';
require_once 'classes/User.php';
require_once 'classes/Article.php';

$db = new Database();
$pdo = $db->connect(); 

$userObj = new User($pdo);
$articleObj = new Article($pdo);

// Fetch categories for use in forms
$categories = $articleObj->getCategories();
?>
