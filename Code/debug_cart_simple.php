<?php
// Simple cart debug
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Simulate the exact request
$_SERVER['REQUEST_METHOD'] = 'POST';
$_SERVER['CONTENT_TYPE'] = 'application/json';

// Mock the JSON input
$testData = json_encode([
    'item_id' => 5,
    'size_id' => '1',
    'quantity' => 1,
    'unit_price' => 130,
    'guest_token' => '0166c56c-a31d-4344-94b3-701b1072070d'
]);

// Create a temporary file to simulate php://input
$temp = tmpfile();
fwrite($temp, $testData);
rewind($temp);

echo "Testing cart with data: " . $testData . "\n\n";

// Include the cart controller
ob_start();
require_once __DIR__ . '/backend/api/controllers/cartController.php';
$output = ob_get_clean();

echo "Output: " . $output . "\n";
fclose($temp);
?>
