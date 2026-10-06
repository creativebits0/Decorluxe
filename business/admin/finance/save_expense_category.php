
<?php

require_once __DIR__ . '/../../../includes/db.inc.php';

$redirect = "/decorluxe/presentation/admin/expenses.php";

if ($_SESSION['role'] === 'show_incharge') {
    $redirect = "/decorluxe/presentation/show_incharge/expenses.php";
}

if (isset($_POST['expense_name'])) {

    $names = $_POST['expense_name'];
    $periods = $_POST['expense_period'];

    foreach ($names as $index => $name) {

        $name = trim($name);

        $period = $periods[$index] ?? 'Daily';

        if ($name != '') {

            $stmt = mysqli_prepare(
                $conn,
                "INSERT INTO expense_category(expense_name, expense_period)
                 VALUES(?, ?)"
            );

            mysqli_stmt_bind_param($stmt, "ss", $name, $period);

            mysqli_stmt_execute($stmt);
        }
    }

$_SESSION['success_message'] = "Expense Category saved successfully";

// header("Location: /decorluxe/presentation/admin/expenses.php");
// exit;
header("Location: $redirect");
exit;}
?>