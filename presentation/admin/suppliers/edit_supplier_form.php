<?php
session_start();
require_once '../../../data/admin/supplier_model.php';

// if ($_SESSION['role'] !== 'admin') {
//     http_response_code(403);
//     exit('Unauthorized access');
// }

$id = $_GET['id'] ?? 0;
$supplier = getSupplierById($id);
if (!$supplier) {
    echo "Supplier not found!";
    exit;
}

?>


<form id="editUserForm" method="POST" action="/decorluxe/business/admin/suppliers/edit_supplier_process.php"
    onsubmit="return validateForm(this)">
    <input type="hidden" name="id" value="<?= htmlspecialchars($supplier['supplier_id']); ?>">
    <div class="mb-3">
        <label for="fullname" class="form-label">Name</label>
        <input type="text" name="fullname" id="fullname" class="form-control validate-companyname" value="<?= htmlspecialchars($supplier['supplier_name']); ?>" required
            onkeypress="validateField(this)">
        <small class="text-light error-msg"></small>

    </div>
    <div class="mb-3">
        <label for="address" class="form-label">Address</label>
        <textarea name="address" id="address" class="form-control validate-address" value="" required
            onkeypress="validateField(this)" required><?= htmlspecialchars($supplier['address']); ?></textarea>

        <small class="text-light error-msg"></small>

    </div>

    <div class="mb-3">
        <label for="phone" class="form-label">Phone</label>
        <div class="d-flex justify-content-between gap-2">
            <input type="tel" name="phone1" id="phone1" class="form-control validate-phone" value="<?= htmlspecialchars($supplier['phone1']); ?>"
                onkeypress="validateField(this)"
                required>
            <input type="tel" name="phone2" id="phone2" class="form-control validate-phone" value="<?= htmlspecialchars($supplier['phone2']); ?>"
                onkeypress="validateField(this)">
            <small class="text-light error-msg"></small>

        </div>
    </div>

    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" name="email" id="email" class="form-control validate-email" value="<?= htmlspecialchars($supplier['email']); ?>"
            onkeypress="validateField(this)">
        <small class="text-light error-msg"></small>

    </div>
    <div class="text-center">
        <!-- <button type="button" class="btn btn-secondary" onclick="closeModal()">Cancel</button> -->
        <button type="submit" class="btn btn-dark w-100">Update</button>
    </div>
</form>