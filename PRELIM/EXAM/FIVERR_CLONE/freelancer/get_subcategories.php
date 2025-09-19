<?php
require_once 'classloader.php';

header('Content-Type: application/json');

$category_id = isset($_GET['category_id']) ? (int)$_GET['category_id'] : 0;

if ($category_id > 0) {
    $subcategories = $subcategoryObj->getSubcategories(null, $category_id);
    echo json_encode($subcategories);
} else {
    echo json_encode([]);
}
?>