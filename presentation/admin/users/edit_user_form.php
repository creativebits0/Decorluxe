<?php
session_start();
require_once '../../../data/admin/user_modal.php';

$block ='';
if ($_SESSION['role'] !== 'admin') {
    $block = 'd-none';
}

$id = $_GET['id'] ?? 0;
$user = getUserById($id);
if (!$user) {
    echo "User not found!";
    exit;
}
?>

<form id="editUserForm" method="POST" action="/decorluxe/business/admin/users/edit_user_process.php"
    onsubmit="return validateForm(this)">
    <input type="hidden" name="id" value="<?= htmlspecialchars($user['id']); ?>">
    <div class="mb-3">
        <label for="firstName" class="form-label">First Name</label>
        <input type="text" name="firstName" id="firstName" class="form-control validate-name" value="<?= htmlspecialchars($user['firstname']); ?>"
            onkeypress="validateField(this)" required>
        <small class="text-light error-msg"></small>
    </div>

    <div class="mb-3">
        <label class="form-label">Username</label>
        <input type="text" name="username" value="<?= htmlspecialchars($user['username']); ?>" class="form-control" required>
    </div>
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" name="email" id="email" class="form-control validate-email" value="<?= htmlspecialchars($user['email']); ?>"
            onkeypress="validateField(this)" required>
        <small class="text-light error-msg"></small>

    </div>
    <div class="mb-3 <?= $block ?>">
        <label class="form-label">Role</label>
        <select name="role" class="form-select">
            <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : ''; ?>>Admin</option>
            <option value="project_incharge" <?= $user['role'] === 'project_incharge' ? 'selected' : ''; ?>>Project Incharge</option>
            <option value="show_incharge" <?= $user['role'] === 'show_incharge' ? 'selected' : ''; ?>>Showroom Incharge</option>
            <option value="designer" <?= $user['role'] === 'designer' ? 'selected' : ''; ?>>Designer</option>
            <option value="staff" <?= $user['role'] === 'staff' ? 'selected' : ''; ?>>Project Staff</option>
        </select>
    </div>
    <div class="mb-3">
        <label for="address" class="form-label">Address</label>
        <textarea name="address" id="address" class="form-control validate-address text-start"  required
            onkeypress="validateField(this)" required><?= htmlspecialchars($user['address']); ?></textarea>

        <small class="text-light error-msg"></small>
    </div>
    <div class="mb-3">
        <label for="phone" class="form-label">Phone</label>
        <div class="d-flex justify-content-between gap-2">
            <input type="tel" name="phone1" id="phone1" class="form-control validate-phone" value="<?= htmlspecialchars($user['phone1']); ?>"
                onkeypress="validateField(this)" required>
            <input type="tel" name="phone2" id="phone2" class="form-control validate-phone" value="<?= htmlspecialchars($user['phone2']); ?>"
                onkeypress="validateField(this)" >

            <small class="text-light error-msg"></small>
        </div>
    </div>
    <div class="text-center">
        <!-- <button type="button" class="btn btn-secondary" onclick="closeModal()">Cancel</button> -->
        <button type="submit" class="btn btn-dark">Update</button>
    </div>
</form>