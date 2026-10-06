<?php
require_once '../../../data/admin/report_model.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    echo "Invalid request";
    exit;
}

$expense = getExpenseById($id);   
$categories = getExpenseCategories();

if (!$expense) {
    echo "Expense not found";
    exit;
}
?>
<form id="updateExpenseForm"  method="POST" action="/decorluxe/business/admin/finance/update_expense_amount.php">
    <input type="hidden" name="id" value="<?= $expense['id'] ?>">

    <div class="mb-2">
        <label>Expense Type</label>
        <select name="category_id" class="form-control" required>
            <?php foreach ($categories as $cat) { ?>
                <option value="<?= $cat['id'] ?>"
                    <?= ($cat['id'] == $expense['category_id']) ? 'selected' : '' ?>>
                    <?= $cat['expense_name'] ?>
                </option>
            <?php } ?>
        </select>
    </div>

    <div class="mb-2">
        <label>Amount</label>
        <input type="number" step="0.01"
            name="amount"
            value="<?= $expense['amount'] ?>"
            class="form-control"
            required>
    </div>

    <div class="mb-2">
        <label>Date</label>
        <input type="date"
            name="expense_date"
            value="<?= $expense['expense_date'] ?>"
            class="form-control"
            required>
    </div>

    <div class="mb-2">
        <label>Remark</label>
        <input type="text"
            name="remark"
            value="<?= $expense['remark'] ?>"
            class="form-control">
    </div>

    <button class="btn btn-success w-100">Update</button>
</form>

