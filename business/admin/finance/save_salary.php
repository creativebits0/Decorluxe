<?php
require_once '../../../data/admin/report_model.php';

$user_id = $_POST['user_id'];
$type    = $_POST['salary_type'];
$amount  = $_POST['amount'];
$date    = $_POST['effective_from'];



echo saveEmployeeSalary($user_id, $type, $amount, $date)
    ? "Saved successfully"
    : "Error";