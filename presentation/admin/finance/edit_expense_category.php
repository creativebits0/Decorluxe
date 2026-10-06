
<?php
require_once '../../../data/admin/report_model.php';

$id = $_GET['id'];

$row = getExpenseCategoryById($id);
?>

<form action="/decorluxe/business/admin/finance/update_expense_category.php"
      method="POST">

    <input type="hidden"
           name="id"
           value="<?= $row['id'] ?>">

    <label class="text-light">
        Expense Type
    </label>

    <input type="text"
        name="expense_name"
        value="<?= $row['expense_name'] ?>"
        class="form-control mb-3"
        required>

    <label class="text-light d-block mb-2">
        Expense Period
    </label>

    <div class="d-flex gap-3 text-white mb-3">

        <div class="form-check">
            <input class="form-check-input"
                   type="radio"
                   name="expense_period"
                   value="Daily"
                   <?= ($row['expense_period'] == 'Daily') ? 'checked' : '' ?>>

            <label class="form-check-label">
                Daily
            </label>
        </div>

        <div class="form-check">
            <input class="form-check-input"
                   type="radio"
                   name="expense_period"
                   value="Monthly"
                   <?= ($row['expense_period'] == 'Monthly') ? 'checked' : '' ?>>

            <label class="form-check-label">
                Monthly
            </label>
        </div>

        <div class="form-check">
            <input class="form-check-input"
                   type="radio"
                   name="expense_period"
                   value="Yearly"
                   <?= ($row['expense_period'] == 'Yearly') ? 'checked' : '' ?>>

            <label class="form-check-label">
                Yearly
            </label>
        </div>

    </div>

    <div class="text-center">
        <button class="btn btn-dark">
            Update
        </button>
    </div>

</form>