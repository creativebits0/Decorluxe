<?php
session_start();
require_once __DIR__ . '/../../../data/admin/supplier_model.php';

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

<form id="editUserForm">
    <input type="hidden" name="id" value="<?= htmlspecialchars($supplier['supplier_id']); ?>">
    <div class="mb-3">
        <label class="form-label">Fullname</label>
        <input class="form-control" value="<?= htmlspecialchars($supplier['supplier_name']); ?>" readonly>
    </div>
    <div class="mb-3">
        <label class="form-label">Email</label>
        <input class="form-control" value="<?= htmlspecialchars($supplier['email']); ?>" readonly>
    </div>

    <div class="mb-3">
        <label for="address" class="form-label">Address</label>
        <input class="form-control" value="<?= htmlspecialchars($supplier['address']); ?>" readonly>
    </div>
    <div class="mb-3">
        <label for="phone" class="form-label">Phone</label>
        <div class="d-flex justify-content-between gap-2">
            <input class="form-control" value="<?= htmlspecialchars($supplier['phone1']); ?>" readonly>
            <input class="form-control" value="<?= htmlspecialchars($supplier['phone2']); ?>" readonly>
        </div>
    </div>
        <?php
    $status = $supplier['status'];
    $bgColor = $status == 'Active' ? '#28a745' : '#dc3545';
    $statusText = $status == 'Active' ? 'Supplier Available' : 'Supplier Not Available';
    ?>

    <div class="mb-3 col-6">
        <label class="form-label">Status</label>
        <input
            class="form-control text-white fw-semibold text-center"
            style="background-color: <?= $bgColor ?>;"
            value="<?= $statusText ?>"
            readonly>
    </div>

    <div class="text-end">
        <!-- <button type="button" class="btn btn-secondary" onclick="closeModal()">Cancel</button> -->
    </div>
</form>