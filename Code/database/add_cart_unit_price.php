<?php
require_once 'db2.php';

try {
    // Add unit_price column to cart_item table
    $sql = "ALTER TABLE cart_item ADD COLUMN unit_price DECIMAL(10,2) DEFAULT 0.00 AFTER quantity";
    
    if (mysqli_query($con, $sql)) {
        echo "Successfully added unit_price column to cart_item table\n";
    } else {
        echo "Error adding unit_price column: " . mysqli_error($con) . "\n";
    }
    
    // Verify the updated table structure
    $result = mysqli_query($con, "DESCRIBE cart_item");
    echo "\nUpdated cart_item table structure:\n";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "- " . $row['Field'] . " (" . $row['Type'] . ")\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

mysqli_close($con);
?>
