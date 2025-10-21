<?php
header('Content-Type: application/json');

// Get JSON input
$input = json_decode(file_get_contents('php://input'), true);

// Validate inputs
if (!isset($input['cash']) || !isset($input['quantity']) || !isset($input['price'])) {
    echo json_encode(['success' => false, 'message' => 'Missing required fields.']);
    exit;
}

$cash = floatval($input['cash']);
$quantity = intval($input['quantity']);
$price = floatval($input['price']);

if ($cash < 0 || $quantity <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid cash or quantity.']);
    exit;
}

$totalCost = $price * $quantity;

if ($cash >= $totalCost) {
    $change = $cash - $totalCost;
    echo json_encode(['success' => true, 'change' => $change]);
} else {
    echo json_encode(['success' => false, 'message' => 'Insufficient cash.']);
}
?>