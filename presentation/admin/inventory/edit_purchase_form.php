<?php
session_start();
require_once '../../../data/admin/inventory_model.php';
require_once '../../../data/admin/supplier_model.php';

$id = $_GET['id'];

$purchase = getPurchaseById($id);
$items = getPurchaseItems($id);
$suppliers = getAllSupplier();
?>

<form
    id="editPurchaseForm"
    method="POST"
    enctype="multipart/form-data"
    action="/decorluxe/business/admin/inventory/edit_purchase_process.php">

    <input type="hidden" name="purchase_id" value="<?= $id ?>">

    <div class="mb-3">
        <label>Supplier</label>
        <select name="supplier_id" id="supplier" class="form-select" required>
            <option value="">Select Supplier</option>
            <?php foreach ($suppliers as $sup): ?>
                <option value="<?= $sup['supplier_id'] ?>"
                    <?= ($purchase['supplier_id'] == $sup['supplier_id']) ? 'selected' : '' ?>>
                    <?= $sup['supplier_name'] ?>
                </option>
            <?php endforeach; ?>

        </select>
    </div>

    <div class="d-flex gap-2 mb-3">
        <div>
            <label>Bill No</label>
            <input type="text"
                name="bill_no"
                value="<?= $purchase['bill_no'] ?>"
                class="form-control"
                required>
        </div>

        <div>
            <label>Date</label>
            <input type="date"
                name="purchase_date"
                value="<?= $purchase['purchase_date'] ?>"
                class="form-control"
                required>
        </div>
        <div>
            <label>Updated By</label>
            <input type="text"
                name="updated_by"
                value="<?= $_SESSION['username'] ?>"
                class="form-control"
                disabled>
        </div>
    </div>

    <div class="mb-3">
        <label>Status</label>
        <select name="status" class="form-select" id="purchase_status">

            <option value="Pending"
                <?= $purchase['status'] == "Pending" ? 'selected' : '' ?>>
                Pending
            </option>

            <option value="Verified"
                <?= $purchase['status'] == "Verified" ? 'selected' : '' ?>>
                Verified
            </option>

            <option value="Cancelled"
                <?= $purchase['status'] == "Cancelled" ? 'selected' : '' ?>>
                Cancelled
            </option>

        </select>
    </div>

    <button type="button"
        class="btn btn-warning mb-3"
        onclick="addEditPurchaseItem()">
        Add Item
    </button>


    <div id="editPurchaseItemContainer">

        <?php foreach ($items as $i => $item): ?>

            <div class="itemField border p-3 mb-2 rounded bg-light">

                <h6>Item <?= $i + 1 ?></h6>

                <!-- <select name="item_id[]" class="form-select mb-2 item">
                    <option value="<?= $item['item_id'] ?>" selected>
                        <?= $item['itemName'] ?>
                    </option>
                </select> -->
                <select name="item_id[]"
                    class="form-control mb-2 item"
                    data-selected="<?= $item['item_id'] ?>">
                </select>
                <div class="row">

                    <div class="col-md-3">
                        <input type="number"
                            name="qty[]"
                            value="<?= $item['quantity'] ?>"
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
                    onclick="removePurchaseItem(this)"
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
                <option value="percent" <?= $purchase['discount_type'] == 'percent' ? 'selected' : '' ?>>%</option>
                <option value="amount" <?= $purchase['discount_type'] == 'amount' ? 'selected' : '' ?>>Amount</option>
            </select>
        </div>

        <div class="col-md-4">
            <input type="number"
                name="final_discount"
                id="final_discount"
                value="<?= $purchase['discount'] ?>"
                class="form-control">
        </div>

        <div class="col-md-4">
            <input type="number"
                id="purchase_subtotal"
                class="form-control"
                readonly>
        </div>

    </div>

    <div class="mb-3">
        <label>Total</label>
        <input type="number"
            name="total"
            id="total"
            value="<?= $purchase['total_amount'] ?>"
            class="form-control">
    </div>

    <!-- <button type="submit"
        class="btn btn-dark">
        Update Purchase
    </button> -->

    <?php if ($purchase['status'] == 'Verified' || $purchase['status'] == 'Cancelled') { ?>

        <button class="btn btn-secondary btn-sm" disabled>
            Update Disabled
        </button>

    <?php } else { ?>

        <button type="submit"
            class="btn btn-dark">
            Update Purchase
        </button>

    <?php } ?>

</form>

<script>
    document.querySelectorAll(".item").forEach(select => {
        loadItems(select);
    });

    updatePurchaseTotal();
</script>
