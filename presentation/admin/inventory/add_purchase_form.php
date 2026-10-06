<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once '../../../data/admin/supplier_model.php';
?>

<form
    id="purchaseForm"
    method="POST"
    enctype="multipart/form-data"
    action="/decorluxe/business/admin/inventory/add_purchase_process.php">
    <div class="mb-3">
        <label>Supplier</label>
        <select name="supplier_id" id="supplier" class="form-select" required>
            <option value="">Loading...</option>
        </select>
    </div>
    <div class="d-flex justify-content-between gap-2 mb-3">
        <div class="">
            <label>Invoice No</label>
            <input type="text" name="invoice_no" class="form-control" required>
        </div>

        <div class="">
            <label>Date</label>
            <input type="date" name="purchase_date" class="form-control" max="<?= date('Y-m-d') ?>" required>
        </div>
        <div class="">
            <label>Status</label>
            <select name="status" id="purchase_status" class="form-control">
                <option value="Pending">Pending</option>
                <option value="Verified">Verified</option>
                <!-- <option value="Cancelled">Cancelled</option> -->
            </select>
        </div>
    </div>
    <div class="mb-3">
        <label>Reference Invoice Image</label>
        <input type="file" name="item_image" class="form-control" accept="image/*">
    </div>

    <button type="button" class="btn btn-warning mb-3" onclick="addPurchaseItem()">Add Item</button>

    <div id="purchaseItemContainer"></div>

    <div class="mb-3">
        <hr>

        <h5>Final Discount</h5>

        <div class="row mb-3">

            <div class="col-md-4">
                <select id="final_discount_type" name="final_discount_type[]" class="form-select">
                    <option value="">Discount Type</option>
                    <option value="percent">%</option>
                    <option value="amount">Amount</option>
                </select>
            </div>

            <div class="col-md-4">
                <input type="number" id="final_discount" name="final_discount"
                    placeholder="Discount" class="form-control">
            </div>

            <div class="col-md-4">
                <input type="number"
                    id="purchase_subtotal"
                    class="form-control"
                    placeholder="Subtotal" readonly>
            </div>

        </div>

        <div class="mb-3">
            <label>Total</label>
            <input type="number" name="total" id="total" class="form-control">
        </div>
    </div>

    <button type="submit" class="btn btn-dark">Save Purchase</button>
</form>


