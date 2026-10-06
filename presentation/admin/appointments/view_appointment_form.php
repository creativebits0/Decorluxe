<?php
require_once '../../../data/admin/appointment_model.php';

$id = $_GET['id'];
$appointment = getAppointmentById($id);
$measurements = getMeasurementsByAppointment($id);
$services = getAppointmentServices($id);
?>

<form>
    <div class="d-flex justify-content-between mb-2">
        <div class="">
            <label class="form-label">Client</label>
            <input
                type="text"
                class="form-control"
                value="<?= htmlspecialchars($appointment['fullname']) ?>"
                readonly>
        </div>

        <div class="">
            <label class="form-label">Date</label>
            <input
                type="text"
                class="form-control"
                value="<?= date('d M Y h:i A', strtotime($appointment['appointment_date'])) ?>"
                readonly>
        </div>
    </div>

    <div class="d-flex justify-content-start mb-2 gap-4">

        <div class="">
            <label class="form-label">Appointment Type</label>
            <input
                type="text"
                class="form-control"
                value="<?= htmlspecialchars($appointment['appointment_type']) ?>"
                readonly>
        </div>
        <?php
        $status = $appointment['status'];

        $badge = "secondary";

        if ($status == "Scheduled") $badge = "warning";
        if ($status == "Completed") $badge = "success";
        if ($status == "Cancelled") $badge = "danger";
        ?>

        <div class="">
            <label class="form-label">Status</label><br>
            <span class="badge bg-<?= $badge ?> p-2">
                <?= $status ?>
            </span>
        </div>
    </div>


    <div class="mb-2">
        <label class="form-label">Location</label>
        <input
            type="text"
            class="form-control"
            value="<?= htmlspecialchars($appointment['address']) ?>"
            readonly>
    </div>
    <!-- <div class="mb-2">
        <label class="form-label">Requested Services</label>
        <br>
  
    </div> -->
    <?php
    $serviceNames = array_column($services, 'service_name');
    ?>
    <div class="mb-2">
        <label class="form-label">Requested Services</label>

        <input type="text"
            class="form-control"
            value="<?= htmlspecialchars(implode(', ', $serviceNames)) ?>"
            readonly>
    </div>

    <div class="mb-2">
        <label class="form-label">Notes</label>
        <textarea
            class="form-control"
            rows="3"
            readonly><?= htmlspecialchars($appointment['notes']) ?></textarea>
    </div>
    <hr class="text-light">
    <div class="mb-2">
        <label class="form-label">Measurements</label>

        <?php if (empty($measurements)) { ?>
            <p class="text-muted">No measurements added.</p>
        <?php } else { ?>

            <?php if (!empty($measurements)) { ?>

                <h5 class="mt-3 text-light">Site Measurements</h5>

                <div class="row g-3 mb-4">

                    <?php foreach ($measurements as $m) { ?>

                        <div class="col-md-3">

                            <div class="card bg-dark text-light border-secondary h-100">

                                <img src="/decorluxe/uploads/measurements/<?= htmlspecialchars($m['image_path']) ?>"
                                    class="card-img-top"
                                    style="height:120px; object-fit:cover; cursor:pointer;"
                                    onclick="openImageModal(this.src)">

                                <div class="card-body">

                                    <h6 class="card-title">
                                        <?= htmlspecialchars($m['area_name']) ?>
                                    </h6>

                                    <?php if (!empty($m['notes'])) { ?>

                                        <p class="card-text small">
                                            <?= nl2br(htmlspecialchars($m['notes'])) ?>
                                        </p>

                                    <?php } ?>

                                </div>

                            </div>

                        </div>

                    <?php } ?>

                </div>

            <?php } ?>


        <?php } ?>
    </div>


    <div class="text-end mt-3">
        <!-- <button
            type="button"
            class="btn btn-secondary"
            onclick="closeModal('viewAppointmentModal')">
            Close
        </button> -->
    </div>

</form>