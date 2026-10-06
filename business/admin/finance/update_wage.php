<?php
session_start();

require_once '../../../data/admin/report_model.php';

$id          = $_POST['id'];
$amount      = $_POST['amount'];
$date        = $_POST['work_date'];
$description = $_POST['description'];

$result = updateWage($id, $amount, $date, $description);


if ($result) {
    $_SESSION['success_message'] = "Wage updated successfully!";
    header("Location: /decorluxe/presentation/admin/wages.php");
    exit();
} else {

    $_SESSION['alert_message'] = "Failed to update wage!";
    header("Location: /decorluxe/presentation/admin/wages.php");
    exit();
}

// header("Location: /decorluxe/presentation/admin/wages.php");
// exit;

// echo $result ? "Updated successfully" : "Error";