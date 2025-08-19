<?php
require_once dirname(__DIR__, 2) . '/database/db2.php';


class Item {
    private $conn;

    public function __construct($con) {
        $this->conn = $con;
    }

    public function getAllItems() {
        $sql = "SELECT * FROM starbucksitem";
        $result = $this->conn->query($sql);

        $items = [];
        while ($row = $result->fetch_assoc()) {
            $items[] = $row;
        }

        return $items;
    }

public function getFilteredItems($category_id = 0, $subcategory_id = 0) {
    $sql = "
        SELECT i.id, i.name, i.price,
               i.description, i.category_id, i.subcategory_id,
               c.name AS category_name,
               s.name AS subcategory_name
        FROM starbucksitem i
        LEFT JOIN category c ON i.category_id = c.id
        LEFT JOIN subcategory s ON i.subcategory_id = s.id
        WHERE 1=1
    ";
    $stmt = $this->conn->prepare($sql
        . ($category_id ? " AND i.category_id = ?" : "")
        . ($subcategory_id ? " AND i.subcategory_id = ?" : "")
    );

    // Bind explicitly to avoid issues with variadics and by-reference requirements
    if ($category_id && $subcategory_id) {
        $stmt->bind_param("ii", $category_id, $subcategory_id);
    } elseif ($category_id) {
        $stmt->bind_param("i", $category_id);
    } elseif ($subcategory_id) {
        $stmt->bind_param("i", $subcategory_id);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    $items = [];
    while ($row = $result->fetch_assoc()) {
        $items[] = $row;
    }
    return $items;
}


public function addItem($name, $price, $category_id, $subcategory_id, $description) {
    $sql = "INSERT INTO starbucksitem (name, price, category_id, subcategory_id, description)
            VALUES (?, ?, ?, ?, ?)";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("sdiis", $name, $price, $category_id, $subcategory_id, $description);
    return $stmt->execute();
}

public function updateItem($id, $name, $price, $description) {
    $sql = "UPDATE starbucksitem SET name=?, price=?, description=? WHERE id=?";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("sdsi", $name, $price, $description, $id);
    return $stmt->execute();
}

public function deleteItem($id) {
    $sql = "DELETE FROM starbucksitem WHERE id=?";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}

public function searchByName($query) {
    $sql = "SELECT id, name, price, image_url 
            FROM starbucksitem 
            WHERE name LIKE CONCAT('%', ?, '%') 
            LIMIT 10";

    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("s", $query);
    $stmt->execute();
    $result = $stmt->get_result();

    $items = [];
    while ($row = $result->fetch_assoc()) {
        $items[] = $row;
    }
    return $items;
}

public function searchInventoryByName($query) {
    $sql = "
        SELECT i.id, i.name, i.price,
               i.description, i.category_id, i.subcategory_id,
               c.name AS category_name,
               s.name AS subcategory_name
        FROM starbucksitem i
        LEFT JOIN category c ON i.category_id = c.id
        LEFT JOIN subcategory s ON i.subcategory_id = s.id
        WHERE i.name LIKE CONCAT('%', ?, '%')
    ";

    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("s", $query);
    $stmt->execute();
    $result = $stmt->get_result();

    $items = [];
    while ($row = $result->fetch_assoc()) {
        $items[] = $row;
    }
    return $items;
}




}


?>