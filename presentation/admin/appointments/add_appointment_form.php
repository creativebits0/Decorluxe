<?php
require_once '../../../data/admin/client_model.php';
require_once '../../../data/admin/service_model.php';

$parent_id = $_GET['parent_id'] ?? '';

$services = getServicesForSelect();
?>

<form
    method="POST"
    action="/decorluxe/business/admin/appointments/add_appointment_process.php">
    <input
        type="hidden"
        name="parent_appointment_id"
        value="<?= $parent_id ?>">
    <div class="mb-3">
        <label>Client</label>
        <select name="client_id" id="client" class="form-select"></select>
    </div>
    <div class="mb-3">
        <label>Location</label>
        <select
            name="location"
            id="location"
            class="form-select">

            <option>Select Location</option>
        </select>
    </div>

    <!-- <div class="mb-3">
        <label>Appointment Type</label>
        <select name="type" class="form-select">

            <option value="Site Visit">
                Site Visit
            </option>

            <option value="Project Start">
                Project Start
            </option>

        </select>
    </div> -->

    <div class="mb-3">
        <label>Date & Time</label>
        <input type="datetime-local"
            name="date"
            class="form-control">
    </div>

    <div class="mb-3">
        <label class="form-label fw-bold">Requested Services</label>

        <div class="row">
            <?php foreach ($services as $service): ?>
                <div class="col-md-6">
                    <div class="form-check">
                        <input
                            class="form-check-input"
                            type="checkbox"
                            name="services[]"
                            value="<?= $service['service_id'] ?>"
                            id="service<?= $service['service_id'] ?>">

                        <label
                            class="form-check-label text-light"
                            for="service<?= $service['service_id'] ?>">
                            <?= htmlspecialchars($service['service_name']) ?>
                        </label>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="mb-3">
        <label>Notes</label>
        <textarea
            name="notes"
            class="form-control"></textarea>
    </div>

    <div class="text-end">
        <!-- <button type="button" class="btn btn-secondary" onclick="closeModal()">Cancel</button> -->
        <button type="submit" class="btn btn-dark w-100">Add Appointment</button>
    </div>

</form>