<?php
// Debug script to test the exact API response
$url = 'http://localhost/Clone/starbucks/Code/backend/api/items?subcategory_id=1';

echo "Testing API URL: $url\n\n";

// Method 1: Using cURL to get raw response
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

// Method 2: Check for specific error patterns
if (strpos($response, 'Fatal error') !== false) {
    echo "FOUND: Fatal error in response\n";
}
if (strpos($response, 'Warning') !== false) {
    echo "FOUND: Warning in response\n";
}
if (strpos($response, 'Notice') !== false) {
    echo "FOUND: Notice in response\n";
}

// Method 3: Try to extract just the JSON part
$lines = explode("\n", $response);
foreach ($lines as $line) {
    $trimmed = trim($line);
    if ($trimmed && ($trimmed[0] === '{' || $trimmed[0] === '[')) {
        echo "Potential JSON line found: $trimmed\n";
        $decoded = json_decode($trimmed);
        if ($decoded !== null) {
            echo "Valid JSON found!\n";
        } else {
            echo "Invalid JSON: " . json_last_error_msg() . "\n";
        }
        break;
    }
}
?>
