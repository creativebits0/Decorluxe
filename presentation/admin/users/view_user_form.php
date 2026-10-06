<?php
session_start();
// require_once '../../../data/admin/user_modal.php';
require_once '../../../business/admin/users/view_user_process.php';

// if ($_SESSION['role'] !== 'admin') {
//     http_response_code(403);
//     exit('Unauthorized access');
// }

$id = $_GET['id'] ?? 0;
$user = getUserForView($id);
?>
<style>
    input.bg-success {
        background-color: #28a745 !important;
    }

    input.bg-danger {
        background-color: #dc3545 !important;
    }
</style>
<form id="viewUserForm">
    <input type="hidden" name="id" value="<?= htmlspecialchars($user['id']); ?>">
    <div class="mb-3">
        <label class="form-label">First Name</label>
        <input class="form-control" value="<?= htmlspecialchars($user['firstname']); ?>" readonly>
    </div>

    <div class="d-flex justify-content-between gap-2 mb-3">
        <div class="">
            <label class="form-label">Username</label>
            <input value="<?= htmlspecialchars($user['username']); ?>" class="form-control" readonly>
        </div>
        <div class="">
            <label class="form-label">Role</label>
            <input class="form-control" value="<?= htmlspecialchars($user['role']); ?>" readonly>
        </div>
    </div>
    <div class="mb-3">
        <label class="form-label">Email</label>
        <input class="form-control" value="<?= htmlspecialchars($user['email']); ?>" readonly>
    </div>

    <div class="mb-3">
        <label for="address" class="form-label">Address</label>
        <input class="form-control" value="<?= htmlspecialchars($user['address']); ?>" readonly>
    </div>
    <div class="mb-3">
        <label for="phone" class="form-label">Phone</label>
        <div class="d-flex justify-content-between gap-2">
            <input class="form-control" value="<?= htmlspecialchars($user['phone1']); ?>" readonly>
            <input class="form-control" value="<?= htmlspecialchars($user['phone2']); ?>" readonly>
        </div>
    </div>
    <div class="d-flex justify-content-between gap-2 mb-3">
        <div>
            <label class="form-label">Password Changed</label>
            <input class="form-control <?= htmlspecialchars($user['password_changed_class']); ?>"
                value="<?= htmlspecialchars($user['password_changed_text']); ?>" readonly>
        </div>
        <div>
            <label class="form-label">Joined</label>
            <input class="form-control"
                value="<?= htmlspecialchars($user['created_at']); ?>" readonly>
        </div>
    </div>

    <div class="text-end">
        <!-- <button type="button" class="btn btn-secondary" onclick="closeModal()">Cancel</button> -->
    </div>
</form>