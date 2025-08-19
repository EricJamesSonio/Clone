<?php
// Final test of cart API with updated payload
ini_set('display_errors', 0);
error_reporting(0);

// Simulate the exact API request
$_SERVER['REQUEST_METHOD'] = 'POST';
$_SERVER['CONTENT_TYPE'] = 'application/json';

// Updated payload without unit_price
$testPayload = [
    'item_id' => 5,
    'size_id' => '1',
    'quantity' => 1,
    'guest_token' => '0166c56c-a31d-4344-94b3-701b1072070d'
];

// Mock php://input
$GLOBALS['HTTP_RAW_POST_DATA'] = json_encode($testPayload);

// Capture output
ob_start();

// Include the cart controller (which will process the request)
require_once __DIR__ . '/backend/api/controllers/cartController.php';

$output = ob_get_clean();
echo $output;
?>
