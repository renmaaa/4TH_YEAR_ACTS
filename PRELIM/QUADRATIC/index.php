<?php
require_once 'quadraticEquation.php';

$a = $_POST['a'] ?? 1;
$b = $_POST['b'] ?? -3;
$c = $_POST['c'] ?? 2;

$eq = new QuadraticEquation((float)$a, (float)$b, (float)$c);
$disc = $eq->getDiscriminant();
$root1 = $eq->getRoot1();
$root2 = $eq->getRoot2();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Quadratic Equation Solver</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Quadratic Equation Solver</h1>
        <form method="post">
            <label>a:</label>
            <input type="number" step="any" name="a" value="<?= htmlspecialchars($a) ?>" required>
            <label>b:</label>
            <input type="number" step="any" name="b" value="<?= htmlspecialchars($b) ?>" required>
            <label>c:</label>
            <input type="number" step="any" name="c" value="<?= htmlspecialchars($c) ?>" required>
            <button type="submit">Solve</button>
        </form>

        <div class="results">
            <p><strong>Equation:</strong> <?= "{$a}x² + {$b}x + {$c} = 0" ?></p>
            <p><strong>Discriminant:</strong> <?= $disc ?></p>
            <?php if ($root1 !== null && $root2 !== null): ?>
                <p><strong>Root 1:</strong> <?= $root1 ?></p>
                <p><strong>Root 2:</strong> <?= $root2 ?></p>
            <?php else: ?>
                <p><strong>No real roots.</strong></p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
