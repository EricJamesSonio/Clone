<?php
// Remove unit_price column from cart_item table
require_once __DIR__ . '/database/db2.php';

echo "=== Removing unit_price column from cart_item table ===\n";

// Check if unit_price column exists
$result = $con->query("SHOW COLUMNS FROM cart_item LIKE 'unit_price'");
if ($result->num_rows > 0) {
    echo "Removing unit_price column...\n";
    
    $alterQuery = "ALTER TABLE cart_item DROP COLUMN unit_price";
    
    if ($con->query($alterQuery)) {
        echo "✅ unit_price column removed successfully\n";
    } else {
        echo "❌ Failed to remove unit_price column: " . $con->error . "\n";
    }
} else {
    echo "✅ unit_price column doesn't exist\n";
}

// Show updated table structure
echo "\nUpdated table structure:\n";
$result = $con->query("DESCRIBE cart_item");
while ($row = $result->fetch_assoc()) {
    echo "- {$row['Field']}: {$row['Type']}\n";
}
?>
