<?php
require_once '../../../data/admin/report_model.php';

$id = $_GET['id'];

$data = getWageWithSalary($id);

if(!$data){
    echo "<p class='text-danger'>Data not found</p>";
    exit;
}
?>

<form action="/decorluxe/business/admin/finance/update_wage.php" method="POST">

    <input type="hidden" name="id" value="<?= $data['id'] ?>">

    <div class="mb-2">
        <label>Employee</label>
        <input type="text" class="form-control"
            value="<?= $data['firstname'] ?>" readonly>
    </div>

    <div class="mb-2">
        <label>Salary Type</label>
        <input type="text" class="form-control"
            value="<?= $data['salary_type'] ?>" readonly>
    </div>

    <div class="mb-2">
        <label>Base Salary</label>
        <input type="text" class="form-control"
            value="<?= number_format($data['base_salary'],2) ?>" readonly>
    </div>

    <div class="mb-2">
        <label>Amount</label>
        <input type="number" name="amount"
            value="<?= $data['amount'] ?>"
            class="form-control" required>
    </div>

    <div class="mb-2">
        <label>Date</label>
        <input type="date" name="work_date"
            value="<?= $data['work_date'] ?>"
            class="form-control" required>
    </div>

    <div class="mb-2">
        <label>Remark</label>
        <input type="text" name="description"
            value="<?= $data['description'] ?>"
            class="form-control">
    </div>

    <button class="btn btn-success w-100">Update</button>

</form>