<?php
session_start();
require_once __DIR__ . '/../../../data/admin/supplier_model.php';

$redirect = "/decorluxe/presentation/admin/suppliers.php";

if ($_SESSION['role'] === 'show_incharge') {
    $redirect = "/decorluxe/presentation/show_incharge/suppliers.php";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $supplier_name = trim($_POST['supplier_name']);
    $email = trim($_POST['email']);
    $address = trim($_POST['address']);
    $phone1 = trim($_POST['phone1']);
    $phone2 = trim($_POST['phone2']);
    $item_id = trim($_POST['item_id']);


    $success = addSupplier($supplier_name, $email, $address, $phone1, $phone2, $item_id);
    // $success = addUser($firstName, $username, $email, $hashed, $role, $phone);

    // echo $success ? "✅ Supplier added successfully" : "❌ Failed to add user";

    if ($success) {
        $_SESSION['success_message'] = "✅ Supplier Details Added Successfully";
    } else {
        $_SESSION['alert_message'] = "❌ Failed to Add Supplier Details";
    }

    header("Location: $redirect");
    exit;
}
