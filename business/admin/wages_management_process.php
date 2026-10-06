<?php
require_once '../../data/admin/report_model.php';

/*
-----------------------------------------
FILTER
today / month / year
-----------------------------------------
*/
$type = $_GET['type'] ?? 'today';

if ($type == 'today') {
    $wages = getTodayWages();
} else {
    $wages = getMonthlyWages();
}
?>

<!-- FILTER BUTTONS -->
<div class="mb-3 d-flex gap-2">

    <a href="?type=today" class="btn btn-sm btn-primary">
        Today
    </a>

    <a href="?type=month" class="btn btn-sm btn-success">
        Monthly
    </a>

</div>


<!-- EXPENSE TABLE -->
<div class="table-responsive">
    <table class="table table-bordered table-hover text-center align-middle">

        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Date</th>
                <th>Employee Name</th>
                <th>Amount</th>
                <th>Remark</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>

            <?php
            $no = 1;
            $total = 0;

            if (mysqli_num_rows($wages) > 0):

                while ($row = mysqli_fetch_assoc($wages)):
                    $total += $row['amount'];
            ?>

                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $row['work_date'] ?></td>
                        <td><?= $row['firstname'] ?></td>
                        <td class="fw-bold text-danger">
                            <?= number_format($row['amount'], 2) ?>
                        </td>
                        <td><?= $row['description'] ?></td>

                        <td>
                            <button class="btn btn-sm btn-warning"
                              onclick="editWage(<?= $row['id'] ?>)">
                                Edit
                            </button>
                        </td>
                    </tr>

                <?php endwhile; ?>

                <!-- TOTAL -->
                <tr class="table-secondary fw-bold">
                    <td colspan="3">TOTAL</td>
                    <td class="text-danger">
                        <?= number_format($total, 2) ?>
                    </td>
                    <td colspan="2"></td>
                </tr>

            <?php else: ?>

                <tr>
                    <td colspan="6" class="text-muted">
                        No expense records found
                    </td>
                </tr>

            <?php endif; ?>

        </tbody>

    </table>
</div>



