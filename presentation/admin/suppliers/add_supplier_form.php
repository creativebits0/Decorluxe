<?php
require_once '../../../data/admin/inventory_model.php';
?>

<form id="addQuoteForm" method="POST" action="/decorluxe/business/admin/suppliers/add_supplier_process.php"
    onsubmit="return validateForm(this)">
    <div class="mb-3">
        <label for="supplier_name" class="form-label">Name</label>
        <input type="text" name="supplier_name" id="supplier_name" class="form-control validate-companyname" required
            onkeypress="validateField(this)">
        <small class="text-light error-msg"></small>

    </div>

    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" name="email" id="email" class="form-control validate-email"
            onkeypress="validateField(this)" required>
        <small class="text-light error-msg"></small>


    </div>
    <div class="mb-3">
        <label for="address" class="form-label">Address</label>
        <textarea name="address" id="address" class="form-control validate-address" required
            onkeypress="validateField(this)"></textarea>
        <small class="text-light error-msg"></small>

    </div>
    <div class="mb-3">
        <label for="phone" class="form-label">Phone</label>
        <div class="d-flex justify-content-between gap-2">
            <input type="tel" name="phone1" id="phone1" class="form-control validate-phone"
                onkeypress="validateField(this)" required>
            <input type="tel" name="phone2" id="phone2" class="form-control validate-phone"
                onkeypress="validateField(this)" >
            <small class="text-light error-msg"></small>

        </div>
    </div>

    <!-- <div class="mb-3">
        <label for="item" class="form-label">Item Name</label>
        <select name="item_id" id="item" class="form-select" required>
            <option value="">Loading...</option>
        </select>
    </div> -->


    <div class="text-center">
        <!-- <button type="button" class="btn btn-secondary" onclick="closeModal()">Cancel</button> -->
        <button type="submit" class="btn btn-dark w-100">Add Supplier</button>
    </div>
</form>


<script>
    document.getElementById("addQuoteForm").addEventListener("submit", function(e) {
        e.preventDefault();
        fetch(this.action, {
                method: "POST",
                body: new FormData(this)
            })
            .then(res => res.text())
            .then(alert)
            .catch(err => alert("Error: " + err));
    });
</script>