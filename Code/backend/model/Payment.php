<?php
class Payment {
    private $con;

    public function __construct($con) {
        $this->con = $con;
    }

    public function saveReceipt($type, $paid, $total, $discount, $final) {
    session_start();
    $now = date('Y-m-d H:i:s');

    // 1. Get current user (required for orders)
    if (!isset($_SESSION['user_id'])) {
        return ['success' => false, 'error' => 'User not logged in.'];
    }

    $userId = $_SESSION['user_id'];
    require_once dirname(__DIR__) . '/model/Cart.php'; // correct case
$cartModel = new Cart($this->con);
$cart = $cartModel->getCartItems($userId);

if (empty($cart)) {
    return ['success' => false, 'error' => 'Cart is empty.'];
}

    // 2. Insert new userorder
    $stmt = $this->con->prepare("INSERT INTO userorder (user_id, total_amount, status, placed_at, updated_at) VALUES (?, ?, 'pending', ?, ?)");
    $stmt->bind_param("idss", $userId, $total, $now, $now);
    if (!$stmt->execute()) {
        return ['success' => false, 'error' => 'Failed to insert userorder.'];
    }
    $orderId = $stmt->insert_id;
    $stmt->close();

    // 3. Insert order items (include size_id when available)
    foreach ($cart as $item) {
        $itemId    = $item['item_id'] ?? $item['id'];  // safer
        $qty       = (int)$item['quantity'];
        $unitPrice = $item['unitPrice'] ?? $item['price'];  // fallback if unitPrice doesn't exist
        $sizeId    = isset($item['size_id']) ? $item['size_id'] : null; // nullable

        $stmt = $this->con->prepare("INSERT INTO order_item (order_id, item_id, size_id, quantity, unit_price) VALUES (?, ?, ?, ?, ?)");
        if (!$stmt) {
            return ['success' => false, 'error' => 'Failed to prepare order_item insert.'];
        }
        // Note: binding null is supported; MySQL will store NULL for size_id
        $stmt->bind_param("iiiid", $orderId, $itemId, $sizeId, $qty, $unitPrice);
        if (!$stmt->execute()) {
            return ['success' => false, 'error' => 'Failed to insert order item.'];
        }
        $stmt->close();
    }

    // 4. Calculate values
    $discountAmount = $total - $final;
    $change = $paid - $final;

    // 5. Insert receipt
    $stmt = $this->con->prepare("INSERT INTO receipt (
        order_id, discount_type, discount_value, discount_amount,
        final_amount, payment_amount, change_amount, issued_at
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

    if (!$stmt) {
        return ['success' => false, 'error' => 'Failed to prepare receipt insert'];
    }

    $stmt->bind_param("isddddds", $orderId, $type, $discount, $discountAmount, $final, $paid, $change, $now);

    if (!$stmt->execute()) {
        return ['success' => false, 'error' => 'Failed to insert receipt'];
    }

    $receiptId = $stmt->insert_id;
    $stmt->close();

    // 6. Generate and update receipt code
    $receiptCode = "RCPT-" . date('Ymd') . '-' . str_pad($receiptId, 4, '0', STR_PAD_LEFT);
    $stmt = $this->con->prepare("UPDATE receipt SET receipt_code = ? WHERE id = ?");
    $stmt->bind_param("si", $receiptCode, $receiptId);
    $stmt->execute();
    $stmt->close();

    // 7. Update order status to completed
    $stmt = $this->con->prepare("UPDATE userorder SET status = 'completed', updated_at = ? WHERE id = ?");
    $stmt->bind_param("si", $now, $orderId);
    $stmt->execute();
    $stmt->close();

    // 8. Deduct inventory
    $this->deductItemQuantities($orderId);

  
    $stmt = $this->con->prepare("DELETE FROM cart_item WHERE user_id = ?");
    $stmt->bind_param("i", $userId);
    if (!$stmt->execute()) {
        error_log("❌ Failed to clear cart_item for user $userId: " . $stmt->error);
    }
    $stmt->close();


    return [
        'success' => true,
        'orderId' => $orderId,
        'receiptId' => $receiptId,
        'receiptCode' => $receiptCode
    ];
}
    private function deductItemQuantities($orderId) {
        // Deduct from ready_item_stock instead of starbucksitem
        $query = "
            SELECT item_id, size_id, quantity
            FROM order_item
            WHERE order_id = ?
        ";
        $stmt = $this->con->prepare($query);
        if (!$stmt) {
            error_log("❌ Failed to prepare order_item select: " . $this->con->error);
            return;
        }
        $stmt->bind_param("i", $orderId);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $itemId = (int)$row['item_id'];
            $sizeId = isset($row['size_id']) ? (int)$row['size_id'] : null;
            $qty    = (int)$row['quantity'];

            if ($sizeId === null) {
                // If size is not specified, skip or handle a default mapping if your business logic requires.
                error_log("⚠️ No size_id for order item; skipping ready_item_stock deduction for item $itemId");
                continue;
            }

            // Ensure a stock row exists
            $ins = $this->con->prepare("INSERT IGNORE INTO ready_item_stock (item_id, size_id, quantity) VALUES (?, ?, 0)");
            if ($ins) {
                $ins->bind_param("ii", $itemId, $sizeId);
                $ins->execute();
                $ins->close();
            }

            // Deduct stock; prevent negative values
            $update = $this->con->prepare("
                UPDATE ready_item_stock
                SET quantity = GREATEST(quantity - ?, 0)
                WHERE item_id = ? AND size_id = ?
            ");

            if (!$update) {
                error_log("❌ Failed to prepare ready_item_stock update for item $itemId size $sizeId: " . $this->con->error);
                continue;
            }

            $update->bind_param("iii", $qty, $itemId, $sizeId);
            if (!$update->execute()) {
                error_log("❌ Failed to deduct $qty from ready stock item $itemId size $sizeId: " . $update->error);
            } else {
                error_log("✅ Deducted $qty from ready stock item $itemId size $sizeId");
            }
            $update->close();
        }
    }


}
?>
