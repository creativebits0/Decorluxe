<?php
session_start();
require_once __DIR__ . '/../../../data/admin/user_modal.php';

// if ($_SESSION['role'] !== 'admin') {
//     http_response_code(403);
//     exit('Unauthorized');
// }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $firstName = trim($_POST['firstName']);
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $role = trim($_POST['role']);
    $address = trim($_POST['address']);
    $phone1 = trim($_POST['phone1']);
    $phone2 = trim($_POST['phone2']);
    // $status = trim($_POST['status']) ?? '0';



    $success = updateUser($id, $firstName, $username, $email, $role, $address, $phone1, $phone2);


    // echo $success ? "✅ User updated successfully" : "❌ Failed to update user";
    if ($success) {
        // $_SESSION['success_message'] = "✅ User updated Successfully";
        header("Location: /decorluxe/presentation/admin/user_content.php");
        exit();
    } else {
        // $_SESSION['alert_message'] = "❌ Failed to update User";
        header("Location: /decorluxe/presentation/admin/user_content.php");
        exit();
    }
}
