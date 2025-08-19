<?php
// ✅ Error settings for API safety
ini_set('display_errors', 0); // Turn off error display for clean JSON
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);
ini_set('html_errors', 0); // prevents HTML tags in errors
ini_set('log_errors', 1); // Log errors instead of displaying them

// ✅ Start output buffering to catch warnings
ob_start();

header("Access-Control-Allow-Origin: http://localhost");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');

require_once dirname(__DIR__, 2) . '/database/db2.php';

// ✅ Route parsing
$uriParts = explode('/', trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/'));
$index = array_search('api', $uriParts);
$route = isset($uriParts[$index + 1]) ? $uriParts[$index + 1] : '';

switch ($route) {
    case 'items':
        require __DIR__ . '/routes/items.php';
        break;
    case 'checkout':
        require __DIR__ . '/routes/checkout.php';
        break;
    case 'payment':
        require __DIR__ . '/routes/payment.php';
        break;
    case 'receipt':
        require __DIR__ . '/routes/receipt.php';
        break;
    case 'sizes':
        require __DIR__ . '/routes/sizes.php';
        break;
    case 'login':
        require __DIR__ . '/routes/login.php';
        break;
    case 'signup':
        require __DIR__ . '/routes/signup.php';
        break;
    case 'check_login':
        require __DIR__ . '/routes/check_login.php';
        break;
    case 'logout':
        require __DIR__ . '/routes/logout.php';
        break;
    case 'history':
        require __DIR__ . '/routes/history.php';
        break;
    case 'cart':
        require __DIR__ . '/routes/cart.php';
        break;
    case 'topselling':
        require __DIR__ . '/routes/topselling.php';
        break;
    case 'guest':
        require __DIR__ . '/routes/guest.php';
        break;
    case 'getCities':
        require __DIR__ . '/routes/getCities.php';
        break;
    case 'getCountries':
        require __DIR__ . '/routes/getCountries.php';
        break;
    case 'getProvince':
        require __DIR__ . '/routes/getProvince.php';
        break;
    case 'subcategories':
        require __DIR__ . '/routes/subcategories.php';
        break;
    case 'inventory':
        require __DIR__ . '/routes/inventory.php';
        break;
    case 'categories':
        require __DIR__ . '/routes/categories.php';
        break;

    case 'salesreport':
        require __DIR__ . '/routes/salesreport.php';
        break;

    case 'search':
        require __DIR__ . '/routes/search.php';
        break;


    default:
        http_response_code(404);
        echo json_encode(["status" => false, "message" => "Route not found"]);
}

// ✅ Final cleanup: catch stray warnings
$output = ob_get_clean();
if (!empty($output) && json_decode($output) === null) {
    echo json_encode([
        "status" => false,
        "message" => "Server output before JSON",
        "debug" => $output
    ]);
} elseif (!empty($output)) {
    echo $output;
}
