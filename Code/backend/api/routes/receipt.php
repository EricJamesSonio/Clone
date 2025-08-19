<?php
header("Content-Type: application/json");

require_once dirname(__DIR__, 3) . '/database/db2.php';




//require_once dirname(__DIR__, 2) . '/tcpdf/tcpdf.php'; // Adjust path as needed (currently unused)
// echo json_encode($receiptData);

$orderId = $_GET['orderId'] ?? null;
if (!$orderId) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Missing orderId"]);
    exit;
}

// Fetch receipt info and order date
$sql = "
    SELECT r.*, u.placed_at 
    FROM receipt r 
    JOIN userorder u ON r.order_id = u.id 
    WHERE r.order_id = ?
";
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $orderId);
$stmt->execute();
$result = $stmt->get_result();
$receipt = $result->fetch_assoc();

if (!$receipt) {
    http_response_code(404);
    echo json_encode(["success" => false, "message" => "Receipt not found"]);
    exit;
}

// Fetch ordered items
$itemSql = "
    SELECT i.name, oi.quantity, oi.unit_price
    FROM order_item oi
    JOIN starbucksitem i ON oi.item_id = i.id
    WHERE oi.order_id = ?
";
$stmtItems = $con->prepare($itemSql);
$stmtItems->bind_param("i", $orderId);
$stmtItems->execute();
$itemResult = $stmtItems->get_result();

$items = [];
while ($row = $itemResult->fetch_assoc()) {
    $items[] = [
        "name" => $row['name'],
        "quantity" => (int)$row['quantity'],
        "price" => (float)$row['unit_price'],
        "total" => (float)($row['quantity'] * $row['unit_price']),
        "price_formatted" => number_format($row['unit_price'], 2),
        "total_formatted" => number_format($row['quantity'] * $row['unit_price'], 2)
    ];
}

// Respond to frontend
echo json_encode([
    "success" => true,
    "message" => "Receipt fetched",
    "order_id" => (int)$receipt['order_id'],
    "discount_type" => $receipt['discount_type'],
    "discount_value" => (float)$receipt['discount_value'],
    "discount_amount" => (float)$receipt['discount_amount'],
    "final" => (float)$receipt['final_amount'],
    "final_formatted" => number_format($receipt['final_amount'], 2),
    "paid" => (float)$receipt['payment_amount'],
    "paid_formatted" => number_format($receipt['payment_amount'], 2),
    "change" => (float)$receipt['change_amount'],
    "change_formatted" => number_format($receipt['change_amount'], 2),
    "total" => (float)$receipt['original_amount'],
    "total_formatted" => number_format($receipt['original_amount'], 2),
    "date" => $receipt['issued_at'],
    "items" => $items
]);
