<?php
require_once 'db2.php';

try {
    // Remove quantity column from starbucksitem table if it exists
    $sql = "ALTER TABLE starbucksitem DROP COLUMN IF EXISTS quantity";
    
    if (mysqli_query($con, $sql)) {
        echo "Successfully removed quantity column from starbucksitem table\n";
    } else {
        echo "Error removing quantity column: " . mysqli_error($con) . "\n";
    }
    
    // Verify the table structure
    $result = mysqli_query($con, "DESCRIBE starbucksitem");
    echo "\nCurrent starbucksitem table structure:\n";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "- " . $row['Field'] . " (" . $row['Type'] . ")\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

mysqli_close($con);
?>
