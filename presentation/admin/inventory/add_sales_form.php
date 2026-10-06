<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once '../../../data/admin/supplier_model.php';
?>

<form
    id="addSalesForm"
    method="POST"
    enctype="multipart/form-data"
    action="/decorluxe/business/admin/inventory/add_sales_process.php">

    <div class="d-flex justify-content-between gap-2 mb-3">
        <div class="">
            <label>Invoice No</label>
            <input type="text" name="invoice_no" class="form-control" required>
        </div>

        <div class="">
            <label>Date</label>
            <input type="date" name="sales_date" class="form-control" required>
        </div>
    </div>
    <div class="mb-3">
        <label>Reference Invoice Image</label>
        <input type="file" name="item_image" class="form-control" accept="image/*">
    </div>
 
    <button type="button" class="btn btn-warning mb-3" onclick="addSalesItem()">Add Item</button>

    <div id="salesItemContainer"></div>

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
                    id="sales_subtotal"
                    class="form-control"
                    placeholder="Subtotal" readonly>
            </div>

        </div>

        <div class="mb-3">
            <label>Total</label>
            <input type="number" name="total" id="total" class="form-control">
        </div>
    </div>

    <button type="submit" class="btn btn-dark">Save Sales</button>
</form>