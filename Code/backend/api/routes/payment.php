
<?php
require_once __DIR__ . '/../controllers/paymentController.php';
require_once dirname(__DIR__, 3) . '/database/db2.php';

ini_set('display_errors', 0);
ini_set('html_errors', 0);
error_reporting(E_ALL);
header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'POST') {
    handlePayment($con);
} else {
    http_response_code(405);
    echo json_encode(["message" => "Method Not Allowed"]);
}
