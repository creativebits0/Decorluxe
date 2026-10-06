<?php
session_start();
require_once __DIR__ . '/../../../data/admin/user_modal.php';

// if ($_SESSION['role'] !== 'admin') {
//     http_response_code(403);
//     exit('Unauthorized access');
// }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = trim($_POST['firstName']);
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $email = trim($_POST['email']);
    $role = trim($_POST['role']);
    $address = trim($_POST['address']);
    $phone1 = trim($_POST['phone1']);
    $phone2 = trim($_POST['phone2']);
    $status = isset($_POST['status']) ? trim($_POST['status']) : '0';

    // Default password: same as username (must be changed later)
    $hashed = password_hash($password, PASSWORD_DEFAULT);

    $success = addUser($firstName, $username, $hashed, $email, $role, $address, $phone1, $phone2, $status);
    // $success = addUser($firstName, $username, $email, $hashed, $role, $phone);

    // echo $success ? "✅ User added successfully" : "❌ Failed to add user";

    if ($success) {
        // $_SESSION['success_message'] = "✅ User Added Successfully";
        header("Location: /decorluxe/presentation/admin/user_content.php");
        exit();
    } else {
        // $_SESSION['alert_message'] ="❌ Failed to add User";
        header("Location: /decorluxe/presentation/admin/user_content.php");
        exit();
    }
}
