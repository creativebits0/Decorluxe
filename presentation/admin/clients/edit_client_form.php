<?php
session_start();
require_once '../../../data/admin/client_model.php';

// if ($_SESSION['role'] !== 'admin') {
//     http_response_code(403);
//     exit('Unauthorized access');
// }

$id = $_GET['id'] ?? 0;
$clients = getClientById($id);
if (!$clients) {
    echo "Client not found!";
    exit;
}

$addresses = getClientAddresses($id);
?>


<form id="editUserForm" method="POST" action="/decorluxe/business/admin/clients/edit_client_process.php"
    onsubmit="return validateForm(this)">
    <input type="hidden" name="id" value="<?= htmlspecialchars($clients['client_id']); ?>">
    <div class="mb-3">
        <label for="fullname" class="form-label">Full Name</label>
        <input type="text" name="fullname" id="fullname" class="form-control validate-name" value="<?= htmlspecialchars($clients['fullname']); ?>" required
            onkeypress="validateField(this)">
        <small class="text-light error-msg"></small>

    </div>
    <div class="mb-2">
        <div class="d-flex justify-content-start align-items-center gap-1">
            <label class="form-label">Addresses</label>
            <button
                type="button"
                class="btn btn-sm text-light d-flex justify-content-between align-items-center"
                onclick="addAddress()">
                <i class="material-icons">&#xE147;</i>
            </button>

        </div>

        <div id="addressContainer">

            <?php foreach ($addresses as $addr): ?>

                <div class="d-flex gap-2 mb-2">

                    <input
                        type="hidden"
                        name="address_id[]"
                        value="<?= $addr['address_id'] ?>">

                    <input
                        type="text"
                        name="addresses[]"
                        class="form-control validate-address"
                        value="<?= $addr['address'] ?>"
                        required
                        onkeypress="validateField(this)">


                    <button
                        type="button"
                        class="btn btn-danger"
                        onclick="removeAddress(this)">
                        <i class="material-icons">&#x2212;</i>
                    </button>
                    <small class="text-light error-msg"></small>

                </div>

            <?php endforeach; ?>

        </div>
    </div>
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" name="email" id="email" class="form-control validate-email" value="<?= htmlspecialchars($clients['email']); ?>"
            onkeypress="validateField(this)">
        <small class="text-light error-msg"></small>
    </div>
    <div class="mb-3">
        <label for="phone" class="form-label">Phone</label>
        <div class="d-flex justify-content-between gap-2">
            <input type="tel" name="phone1" id="phone1" class="form-control validate-phone" value="<?= htmlspecialchars($clients['phone1']); ?>"
                onkeypress="validateField(this)"
                required>
            <input type="tel" name="phone2" id="phone2" class="form-control validate-phone" value="<?= htmlspecialchars($clients['phone2']); ?>"
                onkeypress="validateField(this)">
            <small class="text-light error-msg"></small>

        </div>
    </div>

    <div class="text-end">
        <!-- <button type="button" class="btn btn-secondary" onclick="closeModal()">Cancel</button> -->
        <button type="submit" class="btn btn-dark" >Update</button>
    </div>
</form>