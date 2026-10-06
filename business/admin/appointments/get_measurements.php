<?php
require_once '../../../data/admin/appointment_model.php';

$id = $_GET['id'];

$data = getMeasurementsByAppointment($id);

echo json_encode($data);