<?php
session_start();

require_once __DIR__ . '/../../../data/admin/client_model.php';

$redirect = "/decorluxe/presentation/admin/clients.php";

if ($_SESSION['role'] === 'project_incharge') {
    $redirect = "/decorluxe/presentation/project_incharge/clients.php";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id       = intval($_POST['id']);
    $fullname = trim($_POST['fullname']);
    $email    = trim($_POST['email']);
    $phone1   = trim($_POST['phone1']);
    $phone2   = trim($_POST['phone2']);

    // Address arrays
    $address_ids = $_POST['address_id'];   // hidden ids
    $addresses   = $_POST['addresses'];    // address values

    // Update Client
    $success = updateClient(
        $id,
        $fullname,
        $email,
        $phone1,
        $phone2
    );


    if ($success) {

        // Step 1 — Delete removed addresses
        deleteRemovedAddresses($id, $address_ids);


        // Step 2 — Update existing / Insert new
        for ($i = 0; $i < count($addresses); $i++) {

            $address_id = $address_ids[$i];
            $address    = trim($addresses[$i]);

            if ($address == '') continue;

            if ($address_id == "") {

                // Insert New Address
                addClientAddress($id, $address);
            } else {

                // Update Existing Address
                updateClientAddress($address_id, $address);
            }
        }
        $_SESSION['success_message'] =
            "✅  Client Details Updated Successfully!";
        header("Location: $redirect");
        exit();
    } else {
        $_SESSION['alert_message'] =
            "❌ Failed to Edit Client Details!";
        header("Location: $redirect");
        exit();
    }
}
