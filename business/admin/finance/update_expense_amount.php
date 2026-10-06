<?php

require_once '../../../data/admin/report_model.php';

$id       = $_POST['id'];
$category = $_POST['category_id'];
$amount   = $_POST['amount'];
$date     = $_POST['expense_date'];
$remark   = $_POST['remark'];

if (!$id || !$category || !$amount || !$date) {
    echo "All fields are required";
    exit;
}

/*
-----------------------------------
CHECK LIMITS
-----------------------------------
*/

$check = checkExpenseLimit($category, $date, $id);

// if ($check['status'] == false) {
//     echo $check['message'];
//     exit;
// }
if ($check['status'] == false) {

    $_SESSION['alert_message'] = $check['message'];

    header("Location: /decorluxe/presentation/admin/expenses.php");
    exit;
}

/*
---------------------------------------
DAILY WARNING
---------------------------------------
*/

if (isset($check['warning'])) {

    $_SESSION['alert_message'] = $check['message'];
}
/*
-----------------------------------
UPDATE
-----------------------------------
*/

$result = updateExpense($id, $category, $amount, $date, $remark);

if ($result) {
    echo "Expense updated successfully";
} else {
    echo "Update failed";
}
?>