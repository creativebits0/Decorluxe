<?php

require_once '../../../data/admin/report_model.php';

$id             = $_POST['id'];
$expense_name   = trim($_POST['expense_name']);
$expense_period = $_POST['expense_period'];

updateExpenseCategory($id, $expense_name, $expense_period);

header("Location: /decorluxe/presentation/admin/expenses.php");
exit;
?>