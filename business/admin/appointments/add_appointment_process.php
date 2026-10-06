
<?php
session_start();
require_once '../../../data/admin/appointment_model.php';


$redirect = "/decorluxe/presentation/admin/appointment_content.php";

if ($_SESSION['role'] === 'project_incharge') {
    $redirect = "/decorluxe/presentation/project_incharge/appointment_content.php";
}


$client     = $_POST['client_id'];
$date       = $_POST['date'];
$address_id = $_POST['location'];
$notes      = $_POST['notes'];
$services = $_POST['services'] ?? [];
$parent_id = $_POST['parent_appointment_id'] ?? '';

// FIRST APPOINTMENT = SITE VISIT
if (empty($parent_id)) {

    $type = "Site Visit";

    $appointment_id = addAppointment(
        $client,
        $type,
        $date,
        $address_id,
        $notes
    );
} else {

    // SECOND APPOINTMENT = PROJECT START
    $save = createProjectStart(
        $parent_id,
        $client,
        $date,
        $address_id,
        $notes
    );
}

if ($appointment_id) {

    foreach ($services as $service_id) {

        addAppointmentService(
            $appointment_id,
            $service_id
        );
    }

    $_SESSION['success_message'] =
        "✅ Appointment Added Successfully!";
     header("Location: $redirect");
    exit();
} else {

    $_SESSION['alert_message'] =
        "❌ Failed to Add Appointment!";
     header("Location: $redirect");
    exit();
}

// header("Location: /decorluxe/presentation/admin/appointment_content.php");
// exit();
?>