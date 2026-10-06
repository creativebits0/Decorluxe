<?php
session_start();
require_once __DIR__ . '/../../../data/admin/service_model.php';

// if ($_SESSION['role'] !== 'admin') {
//     http_response_code(403);
//     exit('Unauthorized access');
// }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $service_name = trim($_POST['service_name']);
    $base_price = trim($_POST['base_price']);
    $description = trim($_POST['description']);
 


    $success = addservice($service_name,$base_price, $description);
    // $success = addUser($firstName, $username, $email, $hashed, $role, $phone);

    // echo $success ? "✅ Client added successfully" : "❌ Failed to add user";

        if ($success) {
        $_SESSION['success_message'] =  "✅ Service added successfully" ;
        header("Location: /decorluxe/presentation/admin/services.php");
        exit();
    } else {
        $_SESSION['alert_message'] = "❌ Failed to add Service";
        header("Location: /decorluxe/presentation/admin/services.php");
        exit();
    }
}
