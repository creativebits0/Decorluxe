<?php
require_once '../../data/admin/report_model.php';

/*
-----------------------------------------
FILTER
today / month / year
-----------------------------------------
*/
$type = $_GET['type'] ?? 'today';

if ($type == 'month') {

    $expenses = getMonthlyExpenses();
    $wages    = getMonthlyWages();

} elseif ($type == 'year') {

    $expenses = getYearlyExpenses();
    $wages    = getYearlyWages();

} else {

    $expenses = getTodayExpenses();
    $wages    = getTodayWages();
}
?>

<!-- FILTER BUTTONS -->
<div class="mb-3 d-flex gap-2 mt-3">

    <a href="?type=today" class="btn btn-sm btn-primary">
        Today
    </a>

    <a href="?type=month" class="btn btn-sm btn-success">
        Monthly
    </a>

    <a href="?type=year" class="btn btn-sm btn-dark">
        Yearly
    </a>

</div>

<h4 class="text-center"><u>Expense Report</u></h4>
<!-- EXPENSE TABLE -->
<div class="table-responsive">
    <table class="table table-bordered table-hover text-center align-middle">

        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Date</th>
                <th>Expense Name</th>
                <th>Amount</th>
                <th>Remark</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>

            <?php
            $no = 1;
            $expenseTotal = 0;

            if (mysqli_num_rows($expenses) > 0):

                while ($row = mysqli_fetch_assoc($expenses)):
                    $expenseTotal += $row['amount'];
            ?>

                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $row['expense_date'] ?></td>
                        <td><?= $row['expense_name'] ?></td>
                        <td class="fw-bold text-danger">
                            <?= number_format($row['amount'], 2) ?>
                        </td>
                        <td><?= $row['remark'] ?></td>

                        <td>
                            <button class="btn btn-sm btn-warning"
                                onclick="editExpenseAmount(<?= $row['id'] ?>)">
                                Edit
                            </button>
                        </td>
                    </tr>

                <?php endwhile; ?>

                <!-- TOTAL -->
             <?php else: ?>

                <tr>
                    <td colspan="6" class="text-muted">
                        No expense records found
                    </td>
                </tr>

            <?php endif; ?>


            <?php
            /*
            -----------------------------------------
            WAGE TOTAL
            -----------------------------------------
            */
            $wageTotal = 0;

            if ($wages && mysqli_num_rows($wages) > 0) {

                while ($w = mysqli_fetch_assoc($wages)) {

                    $wageTotal += $w['amount'];
                }
            }

            $grandTotal = $expenseTotal + $wageTotal;
            ?>

            <!-- EXPENSE TOTAL -->
            <tr class="table-secondary fw-bold">
                <td colspan="3">
                    TOTAL EXCLUDING WAGES
                </td>

                <td class="text-danger">
                    <?= number_format($expenseTotal, 2) ?>
                </td>

                <td colspan="2"></td>
            </tr>

            <!-- WAGE TOTAL -->
            <tr class="table-warning fw-bold">
                <td colspan="3">
                    TOTAL WAGES
                </td>

                <td class="text-dark">
                    <?= number_format($wageTotal, 2) ?>
                </td>

                <td colspan="2"></td>
            </tr>

            <!-- GRAND TOTAL -->
            <tr class="table-dark fw-bold text-white">
                <td colspan="3">
                    TOTAL INCLUDING WAGES
                </td>

                <td>
                    <?= number_format($grandTotal, 2) ?>
                </td>

                <td colspan="2"></td>
            </tr>

        </tbody>

    </table>
</div>