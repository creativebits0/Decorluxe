<?php
session_start();

// echo "<pre>";
// print_r($_SESSION);
// exit;

require_once __DIR__ . '/../../../data/admin/user_modal.php';


$redirect = "";

if ($_SESSION['role'] === 'project_incharge') {
    $redirect = "/decorluxe/presentation/project_incharge/default_dashboard.php";
} elseif ($_SESSION['role'] === 'show_incharge') {
    $redirect = "/decorluxe/presentation/show_incharge/default_dashboard.php";
} else {
    $redirect = "/decorluxe/presentation/admin/default_dashboard.php";
}


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
        header("Location: $redirect");
        exit();
    } else {
        // $_SESSION['alert_message'] = "❌ Failed to update User";
        header("Location: $redirect");
        exit();
    }
}
