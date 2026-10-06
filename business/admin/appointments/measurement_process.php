<?php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once '../../../data/admin/appointment_model.php';
require_once '../../../data/admin/quotation_model.php';

try {

    $appointment_id = $_POST['appointment_id'] ?? null;
    // $appointment_id = $_POST['appointment_id'];

    deleteMeasurementsByAppointment($appointment_id);

    if (!$appointment_id) {
        throw new Exception("Missing appointment ID");
    }


    $uploadDir = "../../../uploads/measurements/";

    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $data = json_decode($_POST['data'] ?? '[]', true);

    if (!is_array($data)) {
        throw new Exception("Invalid measurement data");
    }

    foreach ($data as $index => $row) {

        $imageName = $row['existing_image'] ?? null;

        // upload new image if exists
        if (
            isset($_FILES['images']['tmp_name'][$index]) &&
            $_FILES['images']['tmp_name'][$index] != ''
        ) {

            $tmp = $_FILES['images']['tmp_name'][$index];
            $name = time() . "_" . $_FILES['images']['name'][$index];

            move_uploaded_file($tmp, $uploadDir . $name);

            // delete old image
            if (!empty($row['existing_image'])) {
                $oldPath = $uploadDir . $row['existing_image'];
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }

            $imageName = $name;
        }


        saveMeasurement(
            $appointment_id,
            $row['area_name'],
            $imageName,
            $row['notes']
        );
    }

    checkAndCreateQuotation($appointment_id);
    
    echo json_encode([
        "status" => "success",
        "redirect" => "/decorluxe/presentation/admin/appointment_content.php"
    ]);

    exit;
} catch (Exception $e) {

    echo json_encode([
        "status" => "error",
        "message" => $e->getMessage()
    ]);

    exit;
}
