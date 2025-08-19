<?php
// Test cart API directly with proper simulation
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Simulate the exact environment
$_SERVER['REQUEST_METHOD'] = 'POST';
$_SERVER['CONTENT_TYPE'] = 'application/json';

// Test payload from the error
$testPayload = [
    'item_id' => 5,
    'size_id' => '1',
    'quantity' => 1,
    'unit_price' => 130,
    'guest_token' => '0166c56c-a31d-4344-94b3-701b1072070d'
];

// Create a stream for php://input simulation
$jsonData = json_encode($testPayload);
echo "Testing with payload: $jsonData\n\n";

// Test database connection first
require_once __DIR__ . '/database/db2.php';
echo "Database connection: OK\n";

// Test cart model
require_once __DIR__ . '/backend/model/Cart.php';
$cart = new Cart($con);
echo "Cart model: OK\n";

// Test adding directly to cart model
try {
    $result = $cart->addOrUpdateCartItem(
        null, // user_id
        $testPayload['guest_token'],
        $testPayload['item_id'],
        intval($testPayload['size_id']),
        $testPayload['quantity'],
        $testPayload['unit_price']
    );
    
    if ($result) {
        echo "✅ Direct cart model test: SUCCESS\n";
    } else {
        echo "❌ Direct cart model test: FAILED\n";
    }
} catch (Exception $e) {
    echo "❌ Cart model error: " . $e->getMessage() . "\n";
}

// Now test the controller with proper input simulation
echo "\n--- Testing Controller ---\n";

// Mock php://input by creating a temporary stream
$stream = fopen('php://temp', 'r+');
fwrite($stream, $jsonData);
rewind($stream);

// Override php://input for this test
stream_wrapper_unregister('php');
stream_wrapper_register('php', 'MockPhpStream');

class MockPhpStream {
    public static $data = '';
    private $position = 0;
    
    public function stream_open($path, $mode, $options, &$opened_path) {
        return $path === 'php://input';
    }
    
    public function stream_read($count) {
        $ret = substr(self::$data, $this->position, $count);
        $this->position += strlen($ret);
        return $ret;
    }
    
    public function stream_eof() {
        return $this->position >= strlen(self::$data);
    }
    
    public function stream_stat() {
        return array();
    }
}

MockPhpStream::$data = $jsonData;

// Now test the controller
ob_start();
try {
    require_once __DIR__ . '/backend/api/controllers/cartController.php';
} catch (Exception $e) {
    echo "Controller error: " . $e->getMessage() . "\n";
}
$output = ob_get_clean();

echo "Controller output: $output\n";
?>
