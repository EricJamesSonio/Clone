<?php
// Test guest cart functionality directly
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "Testing guest cart directly...\n\n";

try {
    require_once 'database/db2.php';
    require_once 'backend/model/Cart.php';
    
    session_start();
    
    $cart = new Cart($con);
    
    // Test guest cart functionality
    $guestToken = 'test_guest_token_123';
    $result = $cart->addOrUpdateCartItem(null, $guestToken, 1, 1, 1, 140.0);
    
    echo "addOrUpdateCartItem result: " . ($result ? 'true' : 'false') . "\n";
    
} catch (Error $e) {
    echo "❌ Fatal Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . " Line: " . $e->getLine() . "\n";
} catch (Exception $e) {
    echo "❌ Exception: " . $e->getMessage() . "\n";
}
?>
