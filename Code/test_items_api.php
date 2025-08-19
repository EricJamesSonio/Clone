<?php
// Test the items API endpoint directly
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "Testing items API endpoint...\n\n";

// Test 1: Direct inclusion
echo "=== Test 1: Direct API call ===\n";
$_SERVER['REQUEST_METHOD'] = 'GET';
$_GET['subcategory_id'] = 1; // Test with subcategory ID 1

ob_start();
try {
    require_once 'backend/api/routes/items.php';
} catch (Exception $e) {
    echo "Exception caught: " . $e->getMessage() . "\n";
} catch (Error $e) {
    echo "Fatal error caught: " . $e->getMessage() . "\n";
}
$output = ob_get_clean();

echo "API Response:\n";
echo $output . "\n";

// Test 2: Check database connection
echo "\n=== Test 2: Database Connection ===\n";
try {
    require_once 'database/db2.php';
    echo "Database connection successful\n";
    
    // Check if starbucksitem table exists
    $result = mysqli_query($con, "SHOW TABLES LIKE 'starbucksitem'");
    if (mysqli_num_rows($result) > 0) {
        echo "starbucksitem table exists\n";
        
        // Check table structure
        $result = mysqli_query($con, "DESCRIBE starbucksitem");
        echo "Table structure:\n";
        while ($row = mysqli_fetch_assoc($result)) {
            echo "- " . $row['Field'] . " (" . $row['Type'] . ")\n";
        }
    } else {
        echo "ERROR: starbucksitem table does not exist\n";
    }
    
} catch (Exception $e) {
    echo "Database error: " . $e->getMessage() . "\n";
}

// Test 3: Check Item model
echo "\n=== Test 3: Item Model ===\n";
try {
    require_once 'backend/model/Item.php';
    $itemModel = new Item($con);
    echo "Item model instantiated successfully\n";
    
    $items = $itemModel->getFilteredItems(0, 1);
    echo "Filtered items result: " . json_encode($items) . "\n";
    
} catch (Exception $e) {
    echo "Item model error: " . $e->getMessage() . "\n";
} catch (Error $e) {
    echo "Item model fatal error: " . $e->getMessage() . "\n";
}
?>
