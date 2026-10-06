<?php
require_once '../../../data/admin/report_model.php';

$user_id = $_GET['user_id'];
$salary = getEmployeeSalary($user_id);
?>

<form id="wageForm" method="POST" action="/decorluxe/business/admin/finance/save_wage.php">

    <input type="hidden" name="user_id" value="<?= $user_id ?>">
    <input type="hidden" name="salary" value="<?= $salary ?>">

    <div class="row g-2">

        <div class="col-md-6">
            <label>Date</label>
            <input type="date" name="date"
                value="<?= date('Y-m-d') ?>"
                class="form-control" required>
        </div>

        <div class="col-md-6">
            <label>Work Description</label>
            <input type="text" name="desc"
                class="form-control" required>
        </div>

        <?php if ($salary && $salary['salary_type'] == 'task') { ?>
            <div class="col-md-6">
                <label>Quantity</label>
                <input type="number" name="qty"
                    value="1"
                    class="form-control">
            </div>
        <?php } ?>

        <div class="col-md-12">
            <button class="btn btn-success w-100">
                Save Wage
            </button>
            <?php if ($salary && $salary['salary_type'] == 'monthly') { ?>
                <button type="button" class="btn btn-warning w-20 text-center mt-2"
                    onclick="generateMonthly(<?= $user_id ?>)">
                    Generate Monthly Salary
                </button>
            <?php } ?>
        </div>

    </div>
</form>