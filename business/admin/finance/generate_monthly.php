<?php
require_once '../../../data/admin/report_model.php';

$user_id = $_GET['user_id'];
$month   = date('m');
$year    = date('Y');

$result = generateMonthlySalary($user_id, $month, $year);

if ($result == "duplicate") {
    echo "Salary already generated for this month";
}
elseif ($result == "success") {
    echo "Monthly salary added successfully";
}
else {
    echo "Error occurred";
}
?>