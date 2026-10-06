<?php
require_once '../../../data/admin/report_model.php';

$user_id = $_POST['user_id'];
$date    = $_POST['date'];
$desc    = $_POST['desc'];
$qty     = $_POST['qty'] ?? 1;

// auto logic
$salary = getEmployeeSalary($user_id);

if(!$salary){
    echo "Salary not set!";
    exit;
}

$type = $salary['salary_type'];
$rate = $salary['amount'];

if($type == 'daily'){
    $amount = $rate;
}
elseif($type == 'task'){
    $amount = $rate * $qty;
}
else{
    echo "Monthly employees use monthly generator";
    exit;
}

$result = insertWage($user_id, $date, $amount, $desc);

if ($result == "duplicate") {
    echo "Wage already added for this date";
}
elseif ($result == "success") {
    echo "Wage added successfully";
}
else {
    echo "Error occurred";
}


?>