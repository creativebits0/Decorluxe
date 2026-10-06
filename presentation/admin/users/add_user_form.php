<?php
session_start();
require_once '../../../data/admin/user_modal.php';

// if ($_SESSION['role'] !== 'admin') {
//     http_response_code(403);
//     exit('Unauthorized access');
// }


?>

<form id="addUserForm" method="POST" action="/decorluxe/business/admin/users/add_user_process.php"
    onsubmit="return validateForm(this)">
    <div class="mb-3">
        <label for="firstName" class="form-label">First Name</label>
        <input type="text" name="firstName" id="firstName" class="form-control validate-name" required
            onkeypress="validateField(this)">
        <small class="text-light error-msg"></small>

    </div>

    <div class="d-flex justify-content-between gap-2 mb-3">
        <div class="">
            <label for="username" class="form-label">Username</label>
            <input type="text" name="username" id="username" class="form-control" required>
        </div>
        <div class="">
            <label for="password" class="form-label">Password</label>
            <input type="password" name="password" id="password" class="form-control validate-password" required  onkeypress="validateField(this)">

            <small class="text-light error-msg"></small>
        </div>
    </div>

    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" name="email" id="email" class="form-control validate-email"
             onkeypress="validateField(this)">
        <small class="text-light error-msg"></small>

    </div>

    <div class="mb-3">
        <label for="role" class="form-label">Role</label>
        <select name="role" id="role" class="form-select" required>
            <option value="">Select Role</option>
            <option value="admin">Admin</option>
            <option value="project_incharge">Project Incharge</option>
            <option value="show_incharge">Showroom Incharge</option>
            <option value="designer">Designer</option>
            <option value="staff">Project Staff</option>
        </select>
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
            <input type="tel" name="phone1" id="phone1" class="form-control validate-phone" required
                 onkeypress="validateField(this)">
            <input type="tel" name="phone2" id="phone2" class="form-control validate-phone"
                 onkeypress="validateField(this)">
            <small class="text-light error-msg"></small>

        </div>
    </div>

    <div class="text-center">
        <!-- <button type="button" class="btn btn-secondary" onclick="closeModal()">Cancel</button> -->
        <button type="submit" class="btn btn-dark w-100">Add User</button>
    </div>
</form>