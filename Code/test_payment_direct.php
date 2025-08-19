<?php
// Test payment functionality directly to see the exact error
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "Testing payment functionality directly...\n\n";

try {
    require_once 'database/db2.php';
    echo "✅ Database connection successful\n";
    
    require_once 'backend/model/Payment.php';
    echo "✅ Payment model loaded\n";
    
    // Start session to simulate logged in user
    session_start();
    $_SESSION['user_id'] = 1; // Test user ID
    
    $payment = new Payment($con);
    echo "✅ Payment instance created\n";
    
    // Test the saveReceipt method
    $result = $payment->saveReceipt('cash', 200.00, 150.00, 15.00, 135.00);
    echo "✅ saveReceipt called\n";
    
    echo "Result: ";
    print_r($result);
    
} catch (Error $e) {
    echo "❌ Fatal Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . " Line: " . $e->getLine() . "\n";
} catch (Exception $e) {
    echo "❌ Exception: " . $e->getMessage() . "\n";
}
?>
