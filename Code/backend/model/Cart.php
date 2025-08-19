<?php
class Cart {
    private $con;

    public function __construct(mysqli $con) {
        $this->con = $con;
    }

    public function getCartItems(int $userId): array {
        $sql = "
            SELECT
              ci.id           AS cart_item_id,
              si.id           AS item_id,
              si.name,
              si.price        AS base_price,
              COALESCE(sz.price_modifier, 0) AS size_modifier,
              (si.price + COALESCE(sz.price_modifier, 0)) AS price,
              ci.quantity,
              ci.size_id,
              sz.name        AS size_name
            FROM cart_item ci
            JOIN starbucksitem si ON ci.item_id = si.id
            LEFT JOIN size sz ON ci.size_id = sz.id
            WHERE ci.user_id = ?
        ";
        $stmt = $this->con->prepare($sql);
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $this->con->error);
        }

        $stmt->bind_param("i", $userId);
        $stmt->execute();

        $res = $stmt->get_result();
        $rows = $res->fetch_all(MYSQLI_ASSOC);

        $stmt->close();
        return $rows;
    }

    public function addOrUpdateCartItem(?int $userId, ?string $guestToken, int $itemId, ?int $sizeId, int $quantity): bool {
    $sqlWhere = $userId !== null
        ? "user_id = ? AND guest_token IS NULL"
        : "user_id IS NULL AND guest_token = ?";

    $check = $this->con->prepare(
        "SELECT id FROM cart_item WHERE $sqlWhere AND item_id = ? AND (size_id <=> ?)"
    );

    if ($userId !== null) {
        $check->bind_param("iii", $userId, $itemId, $sizeId);
    } else {
        $check->bind_param("sii", $guestToken, $itemId, $sizeId);
    }

    $check->execute();
    $exists = $check->get_result()->fetch_assoc();
    $check->close();

    if ($exists) {
        $upd = $this->con->prepare(
            "UPDATE cart_item SET quantity = quantity + ? WHERE id = ?"
        );
        $upd->bind_param("ii", $quantity, $exists['id']);
        $ok = $upd->execute();
        $upd->close();
        return $ok;
    } else {
        $ins = $this->con->prepare(
            "INSERT INTO cart_item (user_id, guest_token, item_id, size_id, quantity)
             VALUES (?, ?, ?, ?, ?)"
        );
        $ins->bind_param("isiii", $userId, $guestToken, $itemId, $sizeId, $quantity);
        $ok = $ins->execute();
        $ins->close();
        return $ok;
    }
}


    public function removeCartItem(int $userId, int $itemId, ?int $sizeId): bool {
        $stmt = $this->con->prepare(
            "DELETE FROM cart_item WHERE user_id = ? AND item_id = ? AND (size_id <=> ?)"
        );
        $stmt->bind_param("iii", $userId, $itemId, $sizeId);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    public function clearCart(int $userId): bool {
        $stmt = $this->con->prepare(
            "DELETE FROM cart_item WHERE user_id = ?"
        );
        $stmt->bind_param("i", $userId);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

       public function getCartItemsByGuestToken(string $guestToken): array {
    $sql = "
        SELECT
            ci.id           AS cart_item_id,
            si.id           AS item_id,
            si.name,
            si.price        AS base_price,
            COALESCE(sz.price_modifier, 0) AS size_modifier,
            (si.price + COALESCE(sz.price_modifier, 0)) AS price,
            ci.quantity,
            ci.size_id,
            sz.name        AS size_name
        FROM cart_item ci
        JOIN starbucksitem si ON ci.item_id = si.id
        LEFT JOIN size sz ON ci.size_id = sz.id
        WHERE ci.guest_token = ?
    ";
    $stmt = $this->con->prepare($sql);
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $this->con->error);
    }

    $stmt->bind_param("s", $guestToken);
    $stmt->execute();
    $res = $stmt->get_result();
    $rows = $res->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $rows;
}

}

 
?>

