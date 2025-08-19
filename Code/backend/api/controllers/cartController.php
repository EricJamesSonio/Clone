<?php
ini_set('display_errors', 0);
ini_set('html_errors', 0);
header("Content-Type: application/json");
error_reporting(E_ALL);

require_once dirname(__DIR__, 2) . '/model/Cart.php';
require_once dirname(__DIR__, 3) . '/database/db2.php';

class CartController {
    private $cartModel;
    private $isLoggedIn;
    private $userId;
    private $guestToken;

    public function __construct($dbConnection) {
        session_start();
        $this->cartModel = new Cart($dbConnection);
        $this->isLoggedIn = isset($_SESSION['user_id']);
        $this->userId = $this->isLoggedIn ? (int) $_SESSION['user_id'] : null;
        $this->guestToken = $_SESSION['guest_token'] ?? null;
    }

    private function respond(array $data, int $status = 200): void {
        http_response_code($status);
        echo json_encode($data);
        exit;
    }

    public function handleRequest(): void {
        $method = $_SERVER['REQUEST_METHOD'];
        switch ($method) {
            case 'GET':
                $this->handleGet();
                break;
            case 'POST':
                $this->handlePost();
                break;
            case 'DELETE':
                $this->handleDelete();
                break;
            default:
                $this->respond(["error" => "Method not allowed"], 405);
        }
    }

    private function handleGet(): void {
        if ($this->isLoggedIn) {
            $this->respond($this->cartModel->getCartItems($this->userId));
        } elseif (!empty($this->guestToken)) {
            $this->respond($this->cartModel->getCartItemsByGuestToken($this->guestToken));
        } else {
            $this->respond([]);
        }
    }

    private function handlePost(): void {
        $input = file_get_contents('php://input');
        $payload = json_decode($input, true);

        if (!is_array($payload) || json_last_error() !== JSON_ERROR_NONE) {
            $this->respond(["error" => "Invalid JSON payload", "debug" => $input], 400);
        }

        $itemId = filter_var($payload['item_id'] ?? null, FILTER_VALIDATE_INT);
        $sizeId = isset($payload['size_id']) ? filter_var($payload['size_id'], FILTER_VALIDATE_INT) : null;
        $quantity = filter_var($payload['quantity'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);

        if (!$itemId || !$quantity) {
            $this->respond(["error" => "Missing item_id or quantity"], 400);
        }

        // Ensure guest token exists
        if (!$this->isLoggedIn && !$this->guestToken) {
            $this->guestToken = bin2hex(random_bytes(16));
            $_SESSION['guest_token'] = $this->guestToken;
        }

        // Logged-in or guest DB cart
        if ($this->userId !== null || $this->guestToken !== null) {
            $ok = $this->cartModel->addOrUpdateCartItem($this->userId, $this->guestToken, $itemId, $sizeId, $quantity);
            if ($ok) {
                $this->respond(["success" => true, "message" => "Item added to cart"]);
            } else {
                $this->respond(["error" => "Failed to add item"], 500);
            }
        } else {
            // Guest session-only cart - calculate price dynamically
            if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

            $found = false;
            foreach ($_SESSION['cart'] as &$item) {
                if ($item['item_id'] === $itemId && ($item['size_id'] ?? null) === $sizeId) {
                    $item['quantity'] += $quantity;
                    $found = true;
                    break;
                }
            }

            if (!$found) {
                $_SESSION['cart'][] = [
                    "item_id" => $itemId,
                    "size_id" => $sizeId,
                    "quantity" => $quantity
                ];
            }

            $this->respond(["success" => true, "message" => "Item added to guest cart"]);
        }
    }

    private function handleDelete(): void {
        if ($this->isLoggedIn && $this->userId !== null) {
            $this->cartModel->clearCart($this->userId);
        }
        unset($_SESSION['cart']); // Clear guest cart too
        $this->respond(["success" => true, "message" => "Cart cleared"]);
    }
}

// Run the controller
$controller = new CartController($con);
$controller->handleRequest();
