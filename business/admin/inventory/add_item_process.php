<?php
session_start();
require_once __DIR__ . '/../../../data/admin/inventory_model.php';


$redirect = "/decorluxe/presentation/admin/item_content.php";

if ($_SESSION['role'] === 'show_incharge') {
    $redirect = "/decorluxe/presentation/show_incharge/item_content.php";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $itemCode = trim($_POST['item_code']);
    $itemName = trim($_POST['item_name']);
    $unit = trim($_POST['unit']);
    $brand = trim($_POST['brand']);
    $min_stock = (int)($_POST['min_in_stock'] ?? 0);
    $sellPrice = (float)($_POST['sell_price'] ?? 0);

    // Handle image upload
    $uploadDir = __DIR__ . '/../../../uploads/items/';
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

    $imagePath = null;
    if (!empty($_FILES['item_image']['name'])) {
        $fileTmp = $_FILES['item_image']['tmp_name'];
        $fileName = uniqid('item_', true) . '.' . pathinfo($_FILES['item_image']['name'], PATHINFO_EXTENSION);
        $targetPath = $uploadDir . $fileName;

        if (move_uploaded_file($fileTmp, $targetPath)) {
            $imagePath = 'uploads/items/' . $fileName;
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to upload image']);
            exit;
        }
    }

    // Insert item into DB
    $result = addItems($itemCode, $itemName, $brand, $unit, $sellPrice, $min_stock, $imagePath);

    // echo json_encode(['success' => $result, 'message' => $result ? '✅ Item added successfully' : '❌ Failed to add item']);
    if ($result) {
        // $_SESSION['success_message'] = "✅ Item Added Successfully";
        header("Location: $redirect");
        exit;
    } else {
        // $_SESSION['alert_message'] = "❌ Failed to Add Item";
        header("Location: $redirect");
        exit;
    }

    // header("Location: $redirect");
    // exit;
}
