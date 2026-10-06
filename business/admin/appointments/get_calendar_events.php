
<?php

require_once '../../../data/admin/appointment_model.php';

$appointments = getAllAppointments();

$events = [];

foreach ($appointments as $row) {

    $events[] = [

        'id' => $row['appointment_id'],
        'title' => $row['fullname'] . " - " . $row['appointment_type'],
        'start' => $row['appointment_date']

    ];
}

echo json_encode($events);
