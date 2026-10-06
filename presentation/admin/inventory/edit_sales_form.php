<?php
require_once '../../../data/admin/inventory_model.php';
require_once '../../../data/admin/supplier_model.php';

$id = $_GET['id'];

$sales = getSalesById($id);
$items = getSalesItems($id);
?>

<form
    id="editSalesForm"
    method="POST"
    enctype="multipart/form-data"
    action="/decorluxe/business/admin/inventory/edit_sales_process.php">

    <input type="hidden" name="sales_id" value="<?= $id ?>">

    <div class="d-flex gap-2 mb-3">
        <div>
            <label>Bill No</label>
            <input type="text"
                name="bill_no"
                value="<?= $sales['invoice_no'] ?>"
                class="form-control"
                required>
        </div>

        <div>
            <label>Date</label>
            <input type="date"
                name="sales_date"
                value="<?= $sales['sales_date'] ?>"
                class="form-control"
                required>
        </div>
    </div>


    <button type="button"
        class="btn btn-warning mb-3"
        onclick="addSalesItem()">
        Add Item
    </button>

    <div id="salesItemContainer">

        <?php foreach ($items as $i => $item): ?>

            <div class="itemField border p-3 mb-2 rounded bg-light">

                <h6>Item <?= $i + 1 ?></h6>
                <select name="item_id[]"
                    class="form-select mb-2 item"
                    data-selected="<?= $item['item_id'] ?>">
                </select>
                <div class="row">

                    <div class="col-md-3">
                        <input type="number"
                            name="qty[]"
                            value="<?= $item['qty'] ?>"
                            class="form-control qty">
                    </div>

                    <div class="col-md-3">
                        <input type="number"
                            name="unit_price[]"
                            value="<?= $item['unit_price'] ?>"
                            class="form-control price">
                    </div>

                    <div class="col-md-3">
                        <select name="discount_type[]" class="form-select discount_type">
                            <option value="">Discount</option>
                            <option value="percent" <?= ($item['discount_type'] ?? '') == 'percent' ? 'selected' : '' ?>>%</option>
                            <option value="amount" <?= ($item['discount_type'] ?? '') == 'amount' ? 'selected' : '' ?>>Amount</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <input type="number"
                            name="discount[]"
                            value="<?= $item['discount'] ?>"
                            class="form-control discount">
                    </div>

                </div>

                <div class="mt-2">
                    <label>Sub Total</label>
                    <input type="number"
                        name="sub_total[]"
                        value="<?= $item['total'] ?>"
                        class="form-control subtotal">
                </div>

                <button type="button"
                    onclick="removeSalesItem(this)"
                    class="btn btn-danger btn-sm mt-2">
                    Remove
                </button>

            </div>

        <?php endforeach; ?>

    </div>


    <hr>

    <h5>Final Discount</h5>

    <div class="row mb-3">

        <div class="col-md-4">
            <select name="final_discount_type"
                id="final_discount_type"
                class="form-select">
                <option value="">Type</option>
                <option value="percent" <?= $sales['discount_type'] == 'percent' ? 'selected' : '' ?>>%</option>
                <option value="amount" <?= $sales['discount_type'] == 'amount' ? 'selected' : '' ?>>Amount</option>
            </select>
        </div>

        <div class="col-md-4">
            <input type="number"
                name="final_discount"
                id="final_discount"
                value="<?= $sales['discount'] ?>"
                class="form-control">
        </div>
        <div class="col-md-4">
            <input type="number"
                id="sales_subtotal"
                class="form-control"
                readonly>
        </div>
    </div>
    <div class="mb-3">
        <label>Total</label>
        <input type="number"
            name="total"
            id="total"
            value="<?= $sales['total_amount'] ?>"
            class="form-control">
    </div>

    <div class="mb-3">
        <label>Reference Invoice Image</label>

        <?php if (!empty($sales['bill_file'])): ?>

            <div class="text-start">

                <a href="/decorluxe/uploads/invoice/<?= $sales['bill_file'] ?>"
                    target="_blank">

                    <img
                        src="/decorluxe/uploads/invoice/<?= $sales['bill_file'] ?>"
                        class="border rounded"
                        width="150"
                        height="120"
                        style="cursor:pointer">

                </a>

                <p class="text-muted small">
                    Click image to view full bill
                </p>

            </div>

        <?php else: ?>

            <p class="text-muted text-center">
                No Bill Uploaded
            </p>

        <?php endif; ?>
        <input
            type="hidden"
            name="existing_bill_file"
            value="<?= htmlspecialchars($sales['bill_file'] ?? '') ?>">
        <input type="file" name="item_image" class="form-control" accept="image/*">
    </div>

    <button type="submit"
        class="btn btn-dark">
        Update Sales
    </button>

</form>

<!-- <script>
    document.querySelectorAll(".item").forEach(select => {
        loadItems(select);
    });

    pItemCount = document.querySelectorAll(".itemField").length;

    document.querySelectorAll(".item").forEach(select => {
        loadItems(select);
    });

    updateSalesTotal();
</script> -->