<?php
// Check database tables
require_once __DIR__ . '/database/db2.php';

echo "=== Database Table Check ===\n";

// Check if cart_item table exists
$result = $con->query("SHOW TABLES LIKE 'cart_item'");
if ($result->num_rows > 0) {
    echo "✅ cart_item table exists\n";
    
    // Show table structure
    $result = $con->query("DESCRIBE cart_item");
    echo "\nTable structure:\n";
    while ($row = $result->fetch_assoc()) {
        echo "- {$row['Field']}: {$row['Type']} {$row['Null']} {$row['Key']}\n";
    }
} else {
    echo "❌ cart_item table does not exist\n";
    
    // Create the table
    $createTable = "
    CREATE TABLE cart_item (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NULL,
        guest_token VARCHAR(255) NULL,
        item_id INT NOT NULL,
        size_id INT NULL,
        quantity INT NOT NULL DEFAULT 1,
        unit_price DECIMAL(10,2) DEFAULT 0.00,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        INDEX idx_user_id (user_id),
        INDEX idx_guest_token (guest_token),
        INDEX idx_item_id (item_id)
    )";
    
    if ($con->query($createTable)) {
        echo "✅ cart_item table created successfully\n";
    } else {
        echo "❌ Failed to create cart_item table: " . $con->error . "\n";
    }
}

// Check other required tables
$tables = ['starbucksitem', 'size'];
foreach ($tables as $table) {
    $result = $con->query("SHOW TABLES LIKE '$table'");
    if ($result->num_rows > 0) {
        echo "✅ $table table exists\n";
    } else {
        echo "❌ $table table missing\n";
    }
}
?>
