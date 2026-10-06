<?php
session_start();

require_once '../../../data/admin/report_model.php';


$redirect = "/decorluxe/presentation/admin/expenses.php";

if ($_SESSION['role'] === 'show_incharge') {
    $redirect = "/decorluxe/presentation/show_incharge/expenses.php";
}


$category_id  = $_POST['category_id'];
$amount       = $_POST['amount'];
$expense_date = $_POST['expense_date'];
$remark       = $_POST['remark'];

/*
---------------------------------------
CHECK RULES
---------------------------------------
*/

$check = checkExpenseLimit($category_id, $expense_date);

/*
---------------------------------------
MONTHLY / YEARLY BLOCK
---------------------------------------
*/

if ($check['status'] == false) {

    $_SESSION['alert_message'] = $check['message'];

    header("Location: $redirect");
    exit;
}

/*
---------------------------------------
DAILY WARNING
---------------------------------------
*/

if (isset($check['warning'])) {

    $_SESSION['alert_message'] = $check['message'];

    header("Location: $redirect");
    exit;
}

/*
---------------------------------------
SAVE
---------------------------------------
*/

saveExpense($category_id, $amount, $expense_date, $remark);

$_SESSION['success_message'] = "Expense saved successfully";

// header("Location: /decorluxe/presentation/admin/expenses.php");
// exit;
header("Location: $redirect");
exit;
