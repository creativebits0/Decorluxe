<?php
session_start();
// require_once '../../../data/admin/user_modal.php';
require_once __DIR__ . '/../../../data/admin/client_model.php';

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
$clientAddress = getClientAddresses($id);

?>

<form id="editUserForm">
    <input type="hidden" name="id" value="<?= htmlspecialchars($clients['client_id']); ?>">
    <div class="mb-3">
        <label class="form-label">Fullname</label>
        <input class="form-control" value="<?= htmlspecialchars($clients['fullname']); ?>" readonly>
    </div>
    <div class="mb-3">
        <label class="form-label">Email</label>
        <input class="form-control" value="<?= htmlspecialchars($clients['email']); ?>" readonly>
    </div>

    <div class="mb-3">
        <label class="form-label">Addresses</label>
        <?php foreach ($clientAddress as $address): ?>
            <input
                class="form-control mb-2"
                value="<?= htmlspecialchars($address['address']); ?>"
                readonly>
        <?php endforeach; ?>
    </div>
    <div class="mb-3">
        <label for="phone" class="form-label">Phone</label>
        <div class="d-flex justify-content-between gap-2">
            <input class="form-control" value="<?= htmlspecialchars($clients['phone1']); ?>" readonly>
            <input class="form-control" value="<?= htmlspecialchars($clients['phone2']); ?>" readonly>
        </div>
    </div>

    <div class="text-end">
        <!-- <button type="button" class="btn btn-secondary" onclick="closeModal()">Cancel</button> -->
    </div>
</form>