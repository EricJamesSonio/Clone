<?php
// Debug the route parsing logic
$testUrls = [
    '/Clone/starbucks/Code/backend/api/index2.php/items?subcategory_id=1',
    '/Clone/starbucks/Code/backend/api/items?subcategory_id=1',
    '/api/items?subcategory_id=1'
];

foreach ($testUrls as $testUri) {
    echo "Testing URI: $testUri\n";
    
    $uriParts = explode('/', trim(parse_url($testUri, PHP_URL_PATH), '/'));
    echo "URI Parts: " . implode(' | ', $uriParts) . "\n";
    
    $index = array_search('api', $uriParts);
    echo "API index: " . ($index !== false ? $index : 'not found') . "\n";
    
    $route = isset($uriParts[$index + 1]) ? $uriParts[$index + 1] : '';
    echo "Route: '$route'\n";
    echo "---\n\n";
}
?>
