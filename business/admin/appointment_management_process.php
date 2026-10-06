<?php
require_once __DIR__ . '/../../data/admin/appointment_model.php';
require_once __DIR__ . '/../../data/admin/user_modal.php';
require_once __DIR__ . '/../../data/admin/quotation_model.php';
require_once __DIR__ . '/../../data/admin/client_model.php';

// $appointments = getAllAppointments();
$search = trim($_GET['search'] ?? '');
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

if ($page < 1) {
    $page = 1;
}

$limit = 10;
$offset = ($page - 1) * $limit;

$appointments = getAppointments(
    $limit,
    $offset,
    $search
);

$totalRows = countAppointments($search);
$totalPages = ceil($totalRows / $limit);


?>

<table class="table table-striped table-hover border">

    <thead>

        <tr>
            <th>Client</th>
            <th>Type</th>
            <th>Date</th>
            <th>Site Visit</th>
            <th>Quotation</th>
            <th>Project</th>
            <th>Action</th>
        </tr>

    </thead>

    <tbody>

        <?php foreach ($appointments as $row): ?>

            <tr class="<?= ($row['status'] === 'Cancelled') ? 'table-danger' : '' ?>">
                <td><?= $row['fullname'] ?></td>

                <td><?= $row['appointment_type'] ?></td>

                <td><?= $row['appointment_date'] ?></td>

                <td><?php
                    $status = $row['status'];

                    $badge = "secondary";

                    if ($status == "Scheduled") $badge = "warning";
                    if ($status == "Completed") $badge = "success";
                    if ($status == "Cancelled") $badge = "danger";
                    ?>
                    <span class="badge bg-<?= $badge ?> p-2">
                        <?= $status ?>
                    </span>


                </td>
                <td>
                    <?php if (!empty($row['quotation_no'])): ?>

                        <button
                            class="btn btn-outline-primary btn-sm"
                            onclick="viewQuote(<?= $row['quotation_id'] ?>)">
                            <?= htmlspecialchars($row['quotation_no']) ?>
                        </button>

                    <?php else: ?>

                        <span class="text-muted">-</span>

                    <?php endif; ?>
                </td>
                <td>
                    <?php if (!empty($row['project_no'])): ?>

                        <button
                            class="btn btn-outline-primary btn-sm"
                            onclick="viewProject(<?= $row['project_id'] ?>)">
                            <?= htmlspecialchars($row['project_no']) ?>
                        </button>

                    <?php else: ?>

                        <span class="text-muted">-</span>

                    <?php endif; ?>
                </td>
                <td class="d-flex justify-content-center gap-2">

                    <button
                        onclick="viewAppointment(<?= $row['appointment_id'] ?>)"
                        class="btn btn-primary btn-sm d-flex align-items-center gap-2" style="background-color: #2D332D;">
                        <i class="material-icons">visibility</i> View
                    </button>
                    <!-- <button onclick="openMeasurementModal(<?= $row['appointment_id'] ?>)"
                        class="btn btn-warning btn-sm d-flex align-items-center gap-2">
                        <i class="material-icons">square_foot</i>Measurement
                    </button> -->
                    <button
                        onclick="openMeasurementModal(<?= $row['appointment_id'] ?>)"
                        class="btn btn-warning btn-sm d-flex align-items-center gap-2"
                        <?= ($row['status'] === 'Cancelled') ? 'disabled' : '' ?>>
                        <i class="material-icons">square_foot</i>
                        Measurement
                    </button>
                    <!-- <button
                        onclick="editAppointment(<?= $row['appointment_id'] ?>)"
                        class="btn btn-success btn-sm d-flex align-items-center gap-2" style="background-color: #6c757d;">
                        <i class="material-icons">edit_square</i> Edit
                    </button> -->
                    <button
                        onclick="editAppointment(<?= $row['appointment_id'] ?>)"
                        class="btn btn-success btn-sm d-flex align-items-center gap-2"
                        style="background-color: #6c757d;"
                        <?= ($row['status'] === 'Cancelled') ? 'disabled' : '' ?>>
                        <i class="material-icons">edit_square</i>
                        Edit
                    </button>

                </td>

            </tr>

        <?php endforeach ?>

    </tbody>

</table>

<nav class="mt-3">
    <ul class="pagination justify-content-center">

        <!-- Previous -->
        <?php if ($page > 1): ?>
            <li class="page-item">
                <a class="page-link"
                    href="#"
                    onclick="loadAppointments(
       <?= $page - 1 ?>,
       '<?= htmlspecialchars($search, ENT_QUOTES) ?>'
   ); return false;">
                    Previous
                </a>
            </li>
        <?php endif; ?>

        <!-- Page Numbers -->
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                <a class="page-link"
                    href="#"
                    onclick="loadAppointments(
       <?= $i ?>,
       '<?= htmlspecialchars($search, ENT_QUOTES) ?>'
   ); return false;">
                    <?= $i ?>
                </a>
            </li>
        <?php endfor; ?>

        <!-- Next -->
        <?php if ($page < $totalPages): ?>
            <li class="page-item">
                <a class="page-link"
                    href="#"
                    onclick="loadAppointments(
       <?= $page + 1 ?>,
       '<?= htmlspecialchars($search, ENT_QUOTES) ?>'
   ); return false;">
                    Next
                </a>
            </li>
        <?php endif; ?>

    </ul>
</nav>


<script>
    function loadAppointments(page = 1, search = '') {

        fetch(
                `/decorluxe/business/admin/appointment_management_process.php?page=${page}&search=${encodeURIComponent(search)}`
            )
            .then(response => response.text())
            .then(html => {

                document.getElementById("dynamic-content").innerHTML = html;

            });
    }
</script>