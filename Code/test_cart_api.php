<?php
// Test the cart API endpoint directly to see the exact error
$url = 'http://localhost/Clone/starbucks/Code/backend/api/cart';

$payload = json_encode([
    'item_id' => 1,
    'size_id' => 1,
    'quantity' => 1,
    'unit_price' => 140.00
]);

echo "Testing cart API with payload:\n";
echo $payload . "\n\n";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Content-Length: ' . strlen($payload)
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
    if (strpos($body, 'Warning') !== false) {
        echo "❌ FOUND: Warning in response\n";
    }
    
    $decoded = json_decode($body);
    if ($decoded !== null) {
        echo "✅ Valid JSON response!\n";
    } else {
        echo "❌ Invalid JSON: " . json_last_error_msg() . "\n";
    }
}
?>
