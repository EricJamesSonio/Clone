<?php
// Test cart functionality directly to see the exact error
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "Testing cart functionality directly...\n\n";

try {
    require_once 'database/db2.php';
    echo "✅ Database connection successful\n";
    
    require_once 'backend/model/Cart.php';
    echo "✅ Cart model loaded\n";
    
    $cart = new Cart($con);
    echo "✅ Cart instance created\n";
    
    // Test the method signature
    $result = $cart->addOrUpdateCartItem(1, null, 1, 1, 1, 140.0);
    echo "✅ addOrUpdateCartItem called successfully: " . ($result ? 'true' : 'false') . "\n";
    
} catch (Error $e) {
    echo "❌ Fatal Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . " Line: " . $e->getLine() . "\n";
} catch (Exception $e) {
    echo "❌ Exception: " . $e->getMessage() . "\n";
}
?>
