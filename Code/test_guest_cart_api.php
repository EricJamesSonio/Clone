<?php
// Test guest cart API functionality
$url = 'http://localhost/Clone/starbucks/Code/backend/api/cart';

$payload = json_encode([
    'item_id' => 1,
    'size_id' => 1,
    'quantity' => 1,
    'unit_price' => 140.00,
    'guest_token' => 'test_guest_token_123'
]);

echo "Testing guest cart API...\n";
echo "Payload: $payload\n\n";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json'
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookie.txt');
curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookie.txt');

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "HTTP Status: $httpCode\n";
echo "Raw Response:\n=============\n";
echo $response;
echo "\n=============\n";

// Extract body
$headerEnd = strpos($response, "\r\n\r\n");
if ($headerEnd !== false) {
    $body = substr($response, $headerEnd + 4);
    echo "\nResponse Body: '$body'\n";
    
    if (empty(trim($body))) {
        echo "❌ Empty response body - this causes 'Unexpected end of JSON input'\n";
    } else {
        $decoded = json_decode($body);
        if ($decoded !== null) {
            echo "✅ Valid JSON\n";
            print_r($decoded);
        } else {
            echo "❌ Invalid JSON: " . json_last_error_msg() . "\n";
        }
    }
}
?>
