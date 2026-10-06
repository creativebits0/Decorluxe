<?php
session_start();
require_once __DIR__ . '/../../../data/admin/user_modal.php';

// Only admin can perform this
// if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
//     echo json_encode(['success' => false, 'error' => 'Unauthorized']);
//     exit;
// }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id'] ?? 0);
    $status = intval($_POST['status'] ?? 0);

    if ($id > 0) {
        $updated = updateUserStatus($id, $status);
        echo json_encode(['success' => $updated]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Invalid ID']);
    }
    exit;
}

echo json_encode(['success' => false, 'error' => 'Invalid request']);
?>
