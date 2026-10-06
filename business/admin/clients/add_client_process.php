<?php
session_start();
require_once __DIR__ . '/../../../data/admin/client_model.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $phone1 = trim($_POST['phone1']);
    $phone2 = trim($_POST['phone2']);

    $addresses = $_POST['addresses'];

    $client_id = addClient(
        $fullname,
        $email,
        $phone1,
        $phone2
    );


    // Insert Addresses

    if ($client_id) {

        foreach ($addresses as $address) {

            addClientAddress(
                $client_id,
                trim($address)
            );
        }

        // echo "✅ Client Added Successfully";

        $_SESSION['success_message'] =
            "✅ Client Added Successfully";
        header("Location: /decorluxe/presentation/admin/clients.php");
        exit();
    } else {
         $_SESSION['alert_message'] =
            "❌ Failed to add client";
        header("Location: /decorluxe/presentation/admin/clients.php");
        exit();
        // echo "❌ Failed to add client";
    }
}
