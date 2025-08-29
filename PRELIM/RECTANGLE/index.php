<?php
require_once 'Rectangle.php';

// Get user input or use defaults
$width = isset($_POST['width']) ? (float)$_POST['width'] : 200;
$height = isset($_POST['height']) ? (float)$_POST['height'] : 100;

// Create rectangle object
$rectangle = new Rectangle($width, $height);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Interactive Rectangle</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Rectangle Visualizer</h1>

        <form method="post">
            <label for="width">Width (px):</label>
            <input type="number" name="width" id="width" value="<?= $rectangle->getWidth() ?>" required>

            <label for="height">Height (px):</label>
            <input type="number" name="height" id="height" value="<?= $rectangle->getHeight() ?>" required>

            <button type="submit">Update Rectangle</button>
        </form>

        <div class="rectangle"
             style="width: <?= $rectangle->getWidth() ?>px; height: <?= $rectangle->getHeight() ?>px;">
        </div>

        <div class="details">
            <p><strong>Width:</strong> <?= $rectangle->getWidth() ?> px</p>
            <p><strong>Height:</strong> <?= $rectangle->getHeight() ?> px</p>
            <p><strong>Area:</strong> <?= $rectangle->getArea() ?> px²</p>
            <p><strong>Perimeter:</strong> <?= $rectangle->getPerimeter() ?> px</p>
        </div>
    </div>
</body>
</html>
