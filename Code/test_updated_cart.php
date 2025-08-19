<?php
// Test updated cart functionality
require_once __DIR__ . '/database/db2.php';
require_once __DIR__ . '/backend/model/Cart.php';

echo "=== Testing Updated Cart Functionality ===\n";

$cart = new Cart($con);

// Test adding item without unit_price
try {
    $result = $cart->addOrUpdateCartItem(
        null, // user_id
        'test-guest-token-123',
        5, // item_id
        1, // size_id
        2  // quantity
    );
    
    if ($result) {
        echo "✅ Successfully added item to cart\n";
    } else {
        echo "❌ Failed to add item to cart\n";
    }
} catch (Exception $e) {
    echo "❌ Error adding item: " . $e->getMessage() . "\n";
}

// Test retrieving cart items with calculated prices
try {
    $items = $cart->getCartItemsByGuestToken('test-guest-token-123');
    echo "\n📦 Cart items:\n";
    foreach ($items as $item) {
        echo "- {$item['name']} (Size: {$item['size_name']}) x{$item['quantity']}\n";
        echo "  Base price: ₱{$item['base_price']}, Size modifier: ₱{$item['size_modifier']}, Total: ₱{$item['price']}\n";
    }
} catch (Exception $e) {
    echo "❌ Error retrieving cart: " . $e->getMessage() . "\n";
}

echo "\n=== Test Complete ===\n";
?>
