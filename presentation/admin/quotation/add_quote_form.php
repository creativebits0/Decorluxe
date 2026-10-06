<?php
session_start();
require_once '../../../data/admin/client_model.php';

$user = $_SESSION['username'];

$appointment_id = $_GET['appointment_id'] ?? null;

$appointment = null;
$measurements = [];

if ($appointment_id) {
    require_once '../../../data/admin/appointment_model.php';

    $appointment = getAppointmentById($appointment_id);
    $measurements = getMeasurementsByAppointment($appointment_id);
}

?>

<style>
    .itemField {
        background-color: #f2f2ec22;
        color: white;
        padding: 10px;
        border: 1px solid #ddd;
        margin-bottom: 10px;
        border-radius: 6px;
    }
</style>

<form id="addQuoteForm" method="POST" action="/decorluxe/business/admin/quotation/add_quote_process.php">
    <input type="hidden" name="appointment_id" value="<?= $appointment_id ?>">
    <div class="d-flex justify-content-between gap-2 mb-3">
        <div>
            <label for="quote_no" class="form-label">Quotation No</label>
            <input type="text" name="quote_no" class="form-control" readonly value="<?= 'QTN-' . date('Y') . '-' . rand(1000, 9999) ?>">
        </div>
        <div>
            <label for="date" class="form-label">Date</label>
            <input type="date" name="date" id="date" class="form-control"
                value="<?= date('Y-m-d') ?>">
        </div>
        <div>
            <label for="valid_till" class="form-label">Valid Till</label>
            <input type="date" name="valid_till" id="valid_till" class="form-control">
        </div>
    </div>

    <div class="d-flex justify-content-between  mb-3">

        <div>
            <label class="form-label">Client Name</label>

            <!-- Hidden ID for saving -->
            <input
                type="hidden"
                name="client_id"
                value="<?= $appointment['client_id'] ?>">

            <!-- Visible readonly name -->
            <input
                type="text"
                class="form-control"
                value="<?= htmlspecialchars($appointment['fullname']) ?>"
                readonly>
        </div>
        <div>
            <label for="status" class="form-label">Status</label>
            <select name="status" id="status" class="form-select" required>
                <option value="Pending" selected>Pending</option>
                <option value="Approved">Approved</option>
                <option value="Rejected">Cancelled</option>
            </select>
        </div>
        <div>
            <label class="form-label">Created By</label>
            <input
                type="text"
                name="created_by"
                class="form-control"
                value="<?= htmlspecialchars($_SESSION['username'] ?? '') ?>"
                readonly>
        </div>

    </div>

    <div class="d-flex justify-content-between gap-2 mb-3">
        <!-- <div>
            <label for="status" class="form-label">Status</label>
            <select name="status" id="status" class="form-select" required>
                <option value="Pending" selected>Pending</option>
                <option value="Approved">Approved</option>
                <option value="Rejected">Cancelled</option>
            </select>
        </div> -->
        <!-- <div>
            <label for="valid_till" class="form-label">Valid Till</label>
            <input type="date" name="valid_till" id="valid_till" class="form-control">
        </div> -->
    </div>
    <hr class="bg-light">
    <?php if (!empty($measurements)) { ?>

        <h5 class="mt-3">Site Measurements</h5>

        <table class="table table-sm table-bordered text-white">
            <thead>
                <tr>
                    <th>Area</th>
                    <th>W</th>
                    <th>H</th>
                    <th>L</th>
                    <th>Notes</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($measurements as $m) { ?>
                    <tr>
                        <td><?= $m['area_name'] ?></td>
                        <td><?= $m['width'] ?></td>
                        <td><?= $m['height'] ?></td>
                        <td><?= $m['length'] ?></td>
                        <td><?= $m['notes'] ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

    <?php } ?>

    <button type="button"
        onclick="addArea()"
        class="btn btn-sm text-light d-flex justify-content-between align-items-center mb-3"
        style="background-color: #b6a12c;">
        <i class="material-icons">&#xE147;</i> Add Area
    </button>

    <!-- Dynamic items will be appended here -->
    <div id="areaContainer"></div>

    <div class="mb-3">
        <label for="total" class="form-label">Total </label>
        <input type="number" name="total" id="total" class="form-control bg-secondary text-white" readonly>
    </div>

    <div class="text-end">
        <button type="submit" class="btn btn-dark">Add Quotation</button>
    </div>
</form>


<script>
    document.getElementById("addQuoteForm").addEventListener("submit", function(e) {
        e.preventDefault();
        updateTotals();
        fetch(this.action, {
                method: "POST",
                body: new FormData(this)
            })
            .then(res => res.text())
            .then(alert)
            .catch(err => alert("Error: " + err));
    });
</script>