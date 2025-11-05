<?php
require_once '../models/Product.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $image = $_FILES['image']['name'];
    move_uploaded_file($_FILES['image']['tmp_name'], "../public/images/$image");

    Product::add($_POST['name'], $_POST['price'], $image, $_SESSION['user']['id']);
    header('Location: ../views/products.php');
}
?>