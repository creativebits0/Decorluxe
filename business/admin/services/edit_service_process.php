<?php
session_start();
require_once __DIR__ . '/../../../data/admin/service_model.php';

// if ($_SESSION['role'] !== 'admin') {
//     http_response_code(403);
//     exit('Unauthorized');
// }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $service_id = intval($_POST['id']); 
    $service_name = trim($_POST['service_name']);
    $base_price = trim($_POST['base_price']);
    $description = trim($_POST['description']);



    $success = updateservice($service_id,$service_name,$base_price, $description);


    // echo $success ? "✅ Service updated successfully" : "❌ Failed to update Service";

    if ($success) {
        $_SESSION['success_message'] =  "✅ Service updated successfully" ;
        header("Location: /decorluxe/presentation/admin/services.php");
        exit();
    } else {
        $_SESSION['alert_message'] = "❌ Failed to update Service";
        header("Location: /decorluxe/presentation/admin/services.php");
        exit();
    }
}
?>

