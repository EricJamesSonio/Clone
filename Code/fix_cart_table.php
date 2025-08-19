<?php
// Fix cart_item table by adding missing unit_price column
require_once __DIR__ . '/database/db2.php';

echo "=== Fixing cart_item table ===\n";

// Check if unit_price column exists
$result = $con->query("SHOW COLUMNS FROM cart_item LIKE 'unit_price'");
if ($result->num_rows == 0) {
    echo "Adding unit_price column...\n";
    
    $alterQuery = "ALTER TABLE cart_item ADD COLUMN unit_price DECIMAL(10,2) DEFAULT 0.00 AFTER quantity";
    
    if ($con->query($alterQuery)) {
        echo "✅ unit_price column added successfully\n";
    } else {
        echo "❌ Failed to add unit_price column: " . $con->error . "\n";
    }
} else {
    echo "✅ unit_price column already exists\n";
}

// Show updated table structure
echo "\nUpdated table structure:\n";
$result = $con->query("DESCRIBE cart_item");
while ($row = $result->fetch_assoc()) {
    echo "- {$row['Field']}: {$row['Type']}\n";
}
?>
