<?php
require_once 'database/db2.php';

echo "Checking cart_item table structure...\n\n";

$result = mysqli_query($con, "DESCRIBE cart_item");
if ($result) {
    echo "cart_item table columns:\n";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "- " . $row['Field'] . " (" . $row['Type'] . ")\n";
    }
} else {
    echo "Error: " . mysqli_error($con) . "\n";
}

echo "\nSample cart_item data:\n";
$result = mysqli_query($con, "SELECT * FROM cart_item LIMIT 3");
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        print_r($row);
    }
} else {
    echo "No cart items or error: " . mysqli_error($con) . "\n";
}

mysqli_close($con);
?>
