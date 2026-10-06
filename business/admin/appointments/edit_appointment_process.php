<?php
session_start();
require_once '../../../data/admin/appointment_model.php';
require_once '../../../data/admin/quotation_model.php';

header('Content-Type: application/json');

/*
-----------------------------------
PROJECT START LOGIC
-----------------------------------
*/
if (isset($_POST['mode'])) {

    $parent = $_POST['parent_id'];
    $date   = date("Y-m-d H:i:s", strtotime($_POST['date']));

    // CREATE
    if ($_POST['mode'] == "project_start") {

        if (getProjectStartByParent($parent)) {
            echo json_encode([
                "success" => false,
                "message" => "Project already started!"
            ]);
            exit;
        }

        $row = getAppointmentById($parent);

        $save = createProjectStart(
            $parent,
            $row['client_id'],
            $date,
            $row['address_id'],
            'Created from Site Visit'
        );

        echo json_encode([
            "success" => $save,
            "message" => $save ? "Project Start Created" : "Failed"
        ]);
        exit;
    }

    // UPDATE DATE
    if ($_POST['mode'] == "update_project_start") {

        $update = updateProjectStartDate($parent, $date);

        echo json_encode([
            "success" => $update,
            "message" => $update ? "Project Date Updated" : "Update Failed"
        ]);
        exit;
    }
}

/*
-----------------------------------
APPOINTMENT UPDATE
-----------------------------------
*/

$id       = $_POST['appointment_id'];
$client = $_POST['client_id'] ?? $_POST['client'];
// $type     = $_POST['type'];
$date     = date("Y-m-d H:i:s", strtotime($_POST['date']));
$location = $_POST['location'];
$status   = $_POST['status'];
$notes    = $_POST['notes'];
$services = $_POST['services'] ?? [];
if (empty($_POST['type'])) {

    $existing = getAppointmentById($id);

    $type = $existing['appointment_type'];
} else {

    $type = $_POST['type'];
}


if (!canUpdateStatus($id, $status)) {
    echo json_encode([
        "success" => false,
        "message" => "Status cannot be changed after measurements are recorded."
    ]);
    exit;
}

error_log(print_r($_POST, true));

$update = updateAppointment(
    $id,
    $client,
    $type,
    $date,
    $location,
    $status,
    $notes
);
if (!$update) {
    echo json_encode([
        "success" => false,
        "message" => "Appointment update failed"
    ]);
    exit;
}
updateAppointmentServices(
    $id,
    $services
);

$appointment = getAppointmentById($id);

// if (
//     strtolower(trim($appointment['appointment_type']) == "Site Visit") &&
//      strtolower(trim($appointment['status'])) == "completed"
// ) {

//     if (hasMeasurements($id)) {

//         if (!getQuotationByAppointment($id)) {

//             $created = createQuotationFromAppointment($id);

//             if (!$created) {

//                 error_log("Quotation insert failed: ");
//             } else {

//                 error_log("Quotation created successfully");
//             }
//         }
//     } else {

//         error_log("Measurements not found for appointment: " . $id);
//     }
// }

checkAndCreateQuotation($id);


// echo json_encode([
//     "success" => $update,
//     "message" => $update ? "Appointment Updated" : "Update Failed",
//     "openMeasurement" => ($type == "Site Visit" && $status == "Completed"),
//     "id" => $id
// ]);



if ($update) {
    $_SESSION['success_message'] = "✅ Appointment Updated Successfully";
 
} else {
    $_SESSION['alert_message'] = "❌ Appointment Update Failed";
  
}

$redirect = "/decorluxe/presentation/admin/appointment_content.php";

if ($_SESSION['role'] === 'project_incharge') {
    $redirect = "/decorluxe/presentation/project_incharge/appointment_content.php";
}

echo json_encode([
    "success" => $update,
    "redirect" => $redirect,
    "openMeasurement" => ($type == "Site Visit" && $status == "Completed"),
    "id" => $id
]);
exit;
