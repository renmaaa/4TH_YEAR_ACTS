<?php
header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['orders']) || !isset($input['cash']) || !is_array($input['orders'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
    exit;
}

$orders = $input['orders'];
$cash = floatval($input['cash']);
$totalCost = 0;
$receiptItems = [];

if (!is_numeric($input['cash'])) {
    echo json_encode(['success' => false, 'message' => 'Cash must be a valid number.']);
    exit;
}
if ($cash < 0) {
    echo json_encode(['success' => false, 'message' => 'Cash cannot be negative.']);
    exit;
}

foreach ($orders as $order) {
    if (!isset($order['name']) || !isset($order['price']) || !isset($order['qty'])) {
        echo json_encode(['success' => false, 'message' => 'Invalid order item.']);
        exit;
    }
    $qty = intval($order['qty']);
    $price = floatval($order['price']);
    if ($qty <= 0 || $price < 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid quantity or price.']);
        exit;
    }
    $itemTotal = $price * $qty;
    $totalCost += $itemTotal;
    $receiptItems[] = "{$order['name']} x{$qty} - {$itemTotal} PHP";
}

if ($cash < $totalCost) {
    echo json_encode(['success' => false, 'message' => 'Insufficient cash.']);
    exit;
}

$change = $cash - $totalCost;
$receipt = "Items: " . implode(', ', $receiptItems) . ". Total: {$totalCost} PHP. Change: {$change} PHP.";

echo json_encode(['success' => true, 'receipt' => $receipt]);
?>
