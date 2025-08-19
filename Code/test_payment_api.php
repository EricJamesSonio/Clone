<?php
// Test the payment API endpoint directly
$url = 'http://localhost/Clone/starbucks/Code/backend/api/payment';

$payload = json_encode([
    'type' => 'cash',
    'amountPaid' => 200.00,
    'total' => 150.00,
    'discount' => 15.00,
    'finalAmount' => 135.00
]);

echo "Testing payment API with payload:\n";
echo $payload . "\n\n";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Content-Length: ' . strlen($payload),
    'Cookie: PHPSESSID=test123'
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "HTTP Status Code: $httpCode\n";
echo "Raw Response:\n";
echo "=============\n";
echo $response;
echo "\n=============\n\n";

// Extract just the body
$headerEnd = strpos($response, "\r\n\r\n");
if ($headerEnd !== false) {
    $body = substr($response, $headerEnd + 4);
    echo "Response Body:\n";
    echo $body . "\n\n";
    
    if (strpos($body, 'Fatal error') !== false) {
        echo "❌ FOUND: Fatal error in response\n";
    }
    
    $decoded = json_decode($body);
    if ($decoded !== null) {
        echo "✅ Valid JSON response!\n";
        print_r($decoded);
    } else {
        echo "❌ Invalid JSON: " . json_last_error_msg() . "\n";
    }
}
?>
