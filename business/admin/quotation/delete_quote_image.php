<?php
session_start();
require_once '../../../includes/config.php';

header('Content-Type: application/json');

try {

    $id = $_POST['id'] ?? 0;

    if (!$id) {
        throw new Exception("Invalid image id");
    }

    // Get filename
    $stmt = $conn->prepare("
        SELECT image
        FROM quotation_images
        WHERE id=?
    ");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $result = $stmt->get_result();

    if (!$row = $result->fetch_assoc()) {
        throw new Exception("Image not found");
    }

    $image = $row['image'];

    // Delete DB record
    $stmt = $conn->prepare("
        DELETE FROM quotation_images
        WHERE id=?
    ");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    // Delete file
    $path = "../../../uploads/quotation/" . $image;

    if (file_exists($path)) {
        unlink($path);
    }

    echo json_encode([
        "status" => "success"
    ]);
} catch (Exception $e) {

    echo json_encode([
        "status" => "error",
        "message" => $e->getMessage()
    ]);
}
