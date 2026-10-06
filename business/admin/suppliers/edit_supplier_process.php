<?php
session_start();
require_once __DIR__ . '/../../../data/admin/supplier_model.php';

$redirect = "/decorluxe/presentation/admin/suppliers.php";

if ($_SESSION['role'] === 'show_incharge') {
    $redirect = "/decorluxe/presentation/show_incharge/suppliers.php";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $firstName = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $address = trim($_POST['address']);
    $phone1 = trim($_POST['phone1']);
    $phone2 = trim($_POST['phone2']);



    $success = updateSupplier($id, $firstName, $email, $address, $phone1, $phone2);


    // echo $success ? "✅ Supplier updated successfully" : "❌ Failed to update Supplier";
    if ($success) {
        $_SESSION['success_message'] = "✅ Supplier Details Updaed Successfully";
    } else {
        $_SESSION['alert_message'] = "❌ Failed to Update Supplier Details";
    }

    header("Location: $redirect");
    exit;
}
