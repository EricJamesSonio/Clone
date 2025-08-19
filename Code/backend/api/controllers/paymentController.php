<?php
require_once dirname(__DIR__, 2) . '/model/Payment.php';
require_once dirname(__DIR__, 3) . '/database/db2.php';

header('Content-Type: application/json');
ini_set('display_errors', 1);
error_reporting(E_ALL);

class PaymentController {
    private $con;
    private $payment;

    public function __construct($dbConnection) {
        $this->con = $dbConnection;
        $this->payment = new Payment($dbConnection);
    }

    public function processPayment() {
        $data = json_decode(file_get_contents('php://input'), true);

        $paymentType = $data['type'] ?? '';
        $amountPaid  = $data['amountPaid'] ?? 0;
        $total       = $data['total'] ?? 0;
        $discount    = $data['discount'] ?? 0;
        $finalAmount = $data['finalAmount'] ?? 0;

        $result = $this->payment->saveReceipt($paymentType, $amountPaid, $total, $discount, $finalAmount);

        if (is_array($result) && $result['success']) {
            echo json_encode([
                "message" => "Payment successful!",
                "orderId" => $result['orderId'],
                "receiptId" => $result['receiptId'],
                "receiptCode" => $result['receiptCode']
            ]);
        } else {
            http_response_code(500);
            $errorMsg = is_array($result) && isset($result['error']) ? $result['error'] : "Payment failed.";
            echo json_encode(["message" => "Payment failed.", "error" => $errorMsg]);
        }
    }
}

function handlePayment($con) {
    $controller = new PaymentController($con);
    $controller->processPayment();
}
