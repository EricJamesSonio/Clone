<?php
// Debug cart API
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "=== Cart API Debug ===\n";

// Test database connection
echo "1. Testing database connection...\n";
try {
    require_once __DIR__ . '/database/db2.php';
    echo "✅ Database connected successfully\n";
} catch (Exception $e) {
    echo "❌ Database connection failed: " . $e->getMessage() . "\n";
    exit;
}

// Test cart model
echo "\n2. Testing Cart model...\n";
try {
    require_once __DIR__ . '/backend/model/Cart.php';
    $cartModel = new Cart($con);
    echo "✅ Cart model instantiated successfully\n";
} catch (Exception $e) {
    echo "❌ Cart model failed: " . $e->getMessage() . "\n";
    exit;
}

// Test cart controller
echo "\n3. Testing CartController...\n";
try {
    // Simulate POST request
    $_SERVER['REQUEST_METHOD'] = 'POST';
    $_POST = [];
    
    // Simulate JSON payload
    $testPayload = [
        'item_id' => 5,
        'size_id' => 1,
        'quantity' => 1,
        'unit_price' => 130,
        'guest_token' => 'test-token-123'
    ];
    
    // Mock php://input
    $GLOBALS['mock_input'] = json_encode($testPayload);
    
    require_once __DIR__ . '/backend/api/controllers/cartController.php';
    echo "✅ CartController loaded successfully\n";
    
} catch (Exception $e) {
    echo "❌ CartController failed: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}

echo "\n=== Debug Complete ===\n";
?>
