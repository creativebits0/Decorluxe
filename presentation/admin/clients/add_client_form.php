<?php
session_start();
require_once '../../../data/admin/client_model.php';

// if ($_SESSION['role'] !== 'admin') {
//     http_response_code(403);
//     exit('Unauthorized access');
// }


?>

<form id="addClientForm" method="POST" action="/decorluxe/business/admin/clients/add_client_process.php"
    onsubmit="return validateForm(this)">

    <div class="mb-3">
        <label for="fullname" class="form-label">Full Name</label>
        <input type="text" name="fullname" id="fullname" class="form-control validate-name" required
            onkeypress="validateField(this)">
        <small class="text-light error-msg"></small>

    </div>

    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" name="email" id="email" class="form-control validate-email"
            onkeypress="validateField(this)">
        <small class="text-light error-msg"></small>

    </div>
    <div class="mb-3">
        <label class="form-label">Addresses</label>
        <div id="addressContainer">
            <div class="d-flex gap-2 mb-2">
                <input
                    type="text"
                    name="addresses[]"
                    class="form-control validate-address"
                    placeholder="Enter Address"
                    required
                    onkeypress="validateField(this)">
                <button
                    type="button"
                    class="btn btn-danger"
                    onclick="addAddress()">

                    <i class="material-icons">&#x2b;</i>
                </button>
                <small class="text-light error-msg"></small>

            </div>

        </div>

    </div>
    <div class="mb-3">
        <label for="phone" class="form-label">Phone</label>
        <div class="d-flex justify-content-between gap-2">
            <input type="tel" name="phone1" id="phone1" class="form-control validate-phone" required
                onkeypress="validateField(this)">
            <input type="tel" name="phone2" id="phone2" class="form-control validate-phone"
                onkeypress="validateField(this)">
        </div>
        <small class="text-light error-msg"></small>

    </div>

    <div class="text-end">
        <!-- <button type="button" class="btn btn-secondary" onclick="closeModal()">Cancel</button> -->
        <button type="submit" class="btn btn-dark">Add Client</button>
    </div>
</form>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Auto-fill today’s date
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('date').value = today;

        // Load clients from DB
        fetch('/decorluxe/business/admin/clients/get_clients.php')
            .then(res => res.json())
            .then(data => {
                const select = document.getElementById('client');
                select.innerHTML = '<option value="">Select Client</option>';
                data.forEach(client => {
                    const opt = document.createElement('option');
                    opt.value = client.client_id;
                    opt.textContent = client.client_name;
                    select.appendChild(opt);
                });
            });
    });
</script>