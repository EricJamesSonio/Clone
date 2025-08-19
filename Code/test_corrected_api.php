<?php
// Test the corrected API URL
$url = 'http://localhost/Clone/starbucks/Code/backend/api/items?subcategory_id=1';

echo "Testing corrected API URL: $url\n\n";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_NOBODY, false);
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
    
    $decoded = json_decode($body);
    if ($decoded !== null) {
        echo "✅ Valid JSON response!\n";
        echo "Status: " . ($decoded->status ? 'true' : 'false') . "\n";
        if (isset($decoded->data)) {
            echo "Items count: " . count($decoded->data) . "\n";
        }
    } else {
        echo "❌ Invalid JSON: " . json_last_error_msg() . "\n";
    }
}
?>
