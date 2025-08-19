<?php

require_once dirname(__DIR__, 2) . '/model/Item.php';

function getItems($con) {
    header('Content-Type: application/json');
    try {
        $category_id = isset($_GET['category_id']) ? intval($_GET['category_id']) : 0;
        $subcategory_id = isset($_GET['subcategory_id']) ? intval($_GET['subcategory_id']) : 0;

        $itemModel = new Item($con);
        $items = $itemModel->getFilteredItems($category_id, $subcategory_id);

        echo json_encode([
            "status" => true,
            "data" => $items
        ]);
    } catch (Throwable $e) {
        http_response_code(500);
        echo json_encode([
            "status" => false,
            "message" => "Failed to load items",
            "error" => $e->getMessage()
        ]);
    }
}

function addItem($con) {
    $data = json_decode(file_get_contents("php://input"), true);
    $itemModel = new Item($con);
    $success = $itemModel->addItem(
        $data['name'],
        floatval($data['price']),
        intval($data['category_id']),
        intval($data['subcategory_id']),
        $data['description']
    );

    echo json_encode(["status" => $success]);
}

function updateItem($con) {
    $data = json_decode(file_get_contents("php://input"), true);
    $itemModel = new Item($con);
    $success = $itemModel->updateItem(
        intval($data['id']),
        $data['name'],
        floatval($data['price']),
        $data['description']
    );

    echo json_encode(["status" => $success]);
}

function deleteItem($con) {
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    $itemModel = new Item($con);
    $success = $itemModel->deleteItem($id);

    echo json_encode(["status" => $success]);
}

function searchItems($con) {
    $query = isset($_GET['query']) ? trim($_GET['query']) : '';

    if ($query === '') {
        echo json_encode(["status" => false, "message" => "No search query"]);
        return;
    }

    $itemModel = new Item($con);
    $results = $itemModel->searchByName($query);

    header('Content-Type: application/json');
    echo json_encode([
        "status" => true,
        "data" => $results
    ]);
}

function searchInventoryItems($con) {
    $query = isset($_GET['query']) ? trim($_GET['query']) : '';

    if ($query === '') {
        echo json_encode(["status" => false, "message" => "No search query"]);
        return;
    }

    $itemModel = new Item($con);
    $results = $itemModel->searchInventoryByName($query);

    header('Content-Type: application/json');
    echo json_encode([
        "status" => true,
        "data" => $results
    ]);
}

