<?php
require_once '../../../data/admin/report_model.php';

$user_id = $_GET['user_id'];

$salary = getEmployeeSalary($user_id);
?>

<form id="salaryForm"  method="POST" action="/decorluxe/business/admin/finance/save_salary.php">
    <input type="hidden" name="user_id" value="<?= $user_id ?>">

    <div class="mb-2 row">
        <div class="col-md-6">
            <label>Salary Type</label>
            <select name="salary_type" class="form-control" required>
                <option value="monthly" <?= ($salary['salary_type'] ?? '') == 'monthly' ? 'selected' : '' ?>>Monthly</option>
                <option value="daily" <?= ($salary['salary_type'] ?? '') == 'daily' ? 'selected' : '' ?>>Daily</option>
                <option value="task" <?= ($salary['salary_type'] ?? '') == 'task' ? 'selected' : '' ?>>Task</option>
            </select>
        </div>
        <div class="col-md-6">

            <label>Effective From</label>
            <input type="date"
                name="effective_from"
                value="<?= $salary['effective_from'] ?? date('Y-m-d') ?>"
                class="form-control">
        </div>
    </div>

    <div class="mb-2 row">
        <div class="col-md-6">
            <label>Amount</label>
            <input type="number" step="0.01"
                name="amount"
                value="<?= $salary['amount'] ?? '' ?>"
                class="form-control"
                required>
        </div>
        <div class="col-md-6" mt-4>

            <button class="btn btn-success w-100">
                Save Salary
            </button>
        </div>

</form>