<?php
require_once '../../../data/admin/appointment_model.php';
require_once '../../../data/admin/service_model.php';

$id = $_GET['id'];

$appointment = getAppointmentById($id);
$allServices = getServicesForSelect();
$locked = false;

if (
    $appointment['appointment_type'] == 'Site Visit' &&
    $appointment['status'] == 'Completed' &&
    hasMeasurements($id)
) {
    $locked = true;
}

$project = getProjectStartByParent($id);
$requestedServices = getAppointmentServices(
    $appointment['appointment_id']
);
?>

<form id="editAppointmentForm"
    method="POST"
    action="/decorluxe/business/admin/appointments/edit_appointment_process.php">

    <input type="hidden" name="appointment_id" value="<?= $appointment['appointment_id'] ?>">
    <input type="hidden" id="appointment_type" name="type" value="<?= $appointment['appointment_type'] ?>">
    <!-- <div class="mb-2">
        <label for="" class="form-label">Appointment Type : </label>
        <span>Site Visit</span>
    </div> -->
    <div class="mb-2">
        <label class="form-label">Client</label>
        <input type="hidden" name="client_id" value="<?= $appointment['client_id'] ?>">

        <select name="client" id="client" class="form-control">

            <option value="<?= $appointment['client_id'] ?>" selected>
                <?= $appointment['fullname'] ?>
            </option>
        </select>
        <?php if ($locked): ?>
            <input type="hidden" name="client" value="<?= $appointment['client_id'] ?>">
        <?php endif; ?>
    </div>


    <div class="mb-2">
        <label class="form-label">Location</label>
        <select name="location" id="location" class="form-control">
            <option value="<?= $appointment['address_id'] ?>" selected>
                <?= $appointment['address'] ?>
            </option>
        </select>
        <?php if ($locked): ?>
            <input type="hidden" name="location" value="<?= $appointment['address_id'] ?>">
        <?php endif; ?>
    </div>

    <!-- <div class="mb-2">
        <label class="form-label">Appointment Type</label>
        <select name="type" class="form-control" required>
            <option value="Site Visit" <?= $appointment['appointment_type'] == 'Site Visit' ? 'selected' : '' ?>>Site Visit</option>
            <option value="Project Start" <?= $appointment['appointment_type'] == 'Project Start' ? 'selected' : '' ?>>Project Start</option>
        </select>
    </div> -->

    <div class="mb-2">
        <label class="form-label">Date</label>
        <input type="datetime-local"
            name="date"
            class="form-control"
            value="<?= date('Y-m-d\TH:i', strtotime($appointment['appointment_date'])) ?>"
            required <?= $locked ? 'readonly' : '' ?>>
    </div>
    <div class="mb-3">
        <label class="form-label fw-bold">Requested Services</label>

        <div class="row">
            <?php
            $selectedServices = array_column(
                getAppointmentServices($appointment['appointment_id']),
                'service_id'
            );

            foreach ($allServices as $service): ?>

                <div class="col-md-6 mb-2">
                    <div class="form-check">
                        <input
                            class="form-check-input"
                            type="checkbox"
                            name="services[]"
                            value="<?= $service['service_id'] ?>"
                            id="service<?= $service['service_id'] ?>"
                            <?= in_array($service['service_id'], $selectedServices) ? 'checked' : '' ?>>

                        <label class="form-check-label text-light" for="service<?= $service['service_id'] ?>">
                            <?= htmlspecialchars($service['service_name']) ?>
                        </label>
                    </div>
                </div>

            <?php endforeach; ?>
        </div>
    </div>
    <div class="mb-2">
        <label class="form-label">Status</label>
        <select name="status" class="form-control" id="status">

            <option value="Scheduled"
                <?= $appointment['status'] == 'Scheduled' ? 'selected' : '' ?>
                <?= ($appointment['status'] == 'Completed') ? 'disabled' : '' ?>>
                Scheduled
            </option>

            <option value="Completed"
                <?= $appointment['status'] == 'Completed' ? 'selected' : '' ?>>
                Completed
            </option>

            <option value="Cancelled"
                <?= $appointment['status'] == 'Cancelled' ? 'selected' : '' ?>>
                Cancelled
            </option>

        </select>
        <?php if ($locked): ?>
            <input type="hidden" name="status" value="<?= $appointment['status'] ?>">
        <?php endif; ?>
    </div>

    <div class="mb-2">
        <label class="form-label">Notes</label>
        <textarea name="notes" class="form-control" <?= $locked ? 'readonly' : '' ?>><?= $appointment['notes'] ?></textarea>
    </div>

    <?php if (!$locked) { ?>

        <button type="submit" class="btn btn-dark w-100 mt-2" style="background-color: #D4AF37;">
            Update Appointment
        </button>

    <?php } else { ?>

        <div class="alert alert-success mt-3 fs-6">
            Site Visit completed with measurements.
        </div>

    <?php } ?>

    <?php
    $project = getProjectStartByParent($appointment['appointment_id']);
    ?>

    <?php if ($locked) { ?>

        <hr>
        <h5>Project Start</h5>

        <div class="mb-2">
            <label>Date</label>
            <input type="datetime-local"
                id="project_date"
                class="form-control"
                value="<?= $project ? date('Y-m-d\TH:i', strtotime($project['appointment_date'])) : '' ?>">
        </div>

        <?php if ($project) { ?>

            <!-- UPDATE ONLY -->
            <button
                type="button"
                class="btn btn-dark w-100"
                onclick="updateProjectStart(<?= $appointment['appointment_id'] ?>)">
                Update Project Date
            </button>

        <?php } else { ?>

            <!-- CREATE -->
            <button
                type="button"
                class="btn btn-dark w-100"
                onclick="createProjectStart(<?= $appointment['appointment_id'] ?>)">
                Create Project Start
            </button>

        <?php } ?>

    <?php } ?>
</form>