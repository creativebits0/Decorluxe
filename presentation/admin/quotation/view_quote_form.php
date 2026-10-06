<?php

require_once '../../../data/admin/quotation_model.php';
require_once '../../../data/admin/client_model.php';
require_once '../../../data/admin/appointment_model.php';
require_once '../../../data/admin/service_model.php';

$id = $_GET['id'] ?? 0;

$quotation = getQuotationById($id);

// echo "<pre>";
// var_dump($id);
// var_dump($quotation);
// exit;
$items = getQuotationItemsGrouped($id);
$appointment = getAppointmentById($quotation['appointment_id']);
$client = getClientById($quotation['client_id']);
$selectedServiceIds = getQuotationServices($quotation['quotation_id']);
$allServices = getServicesForSelect();
?>
<style>
    .project-images {
        background: white;
        border-radius: 12px;
        /* padding: 10px; */
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
    }

    .project-images img {
        width: 100px;
        height: 100px;
        object-fit: cover;
        border-radius: 12px;
        transition: 0.3s;
        border: #1c1c solid 1px;
        margin: 5px;

    }

    .image i {
        position: absolute;
    }
</style>
<form id="viewQuoteForm" class="px-2">
    <!-- Header Section: Quotation Meta -->
    <div class="row g-3 mb-4 p-3 rounded bg-light border">
        <div class="col-md-3">
            <label class="small text-uppercase fw-bold text-muted mb-1 d-block">Quotation No</label>
            <div class="fw-bold fs-5 text-primary"><?= htmlspecialchars($quotation['quotation_no']); ?></div>
        </div>
        <div class="col-md-3">
            <label class="small text-uppercase fw-bold text-muted mb-1 d-block">Date Issued</label>
            <div><?= date('M d, Y', strtotime($quotation['created_at'])); ?></div>
        </div>
        <div class="col-md-3">
            <label class="small text-uppercase fw-bold text-muted mb-1 d-block">Valid Until</label>
            <div class="text-danger fw-semibold"><?= date('M d, Y', strtotime($quotation['valid_until'])); ?></div>
        </div>
        <div class="col-md-3 text-md-end">
            <label class="small text-uppercase fw-bold text-muted mb-1 d-block">Status</label>
            <span class="badge rounded-pill <?php
                                            echo $quotation['status'] == 'Accepted' ? 'bg-success' : ($quotation['status'] == 'Pending' ? 'bg-warning text-dark' : 'bg-secondary');
                                            ?> px-3">
                <?= htmlspecialchars($quotation['status']); ?>
            </span>
        </div>
    </div>

    <div class="row mb-4">
        <!-- Left Column: Client Details -->
        <div class="col-md-6 border-end rounded bg-light border">
            <h6 class="text-primary border-bottom pb-2 mb-3"><i class="bi bi-person-fill me-2"></i>Client Information</h6>
            <div class="mb-2">
                <label class="form-label small fw-bold mb-0">Full Name</label>
                <p class="form-control-plaintext py-0"><?= htmlspecialchars($client['fullname']); ?></p>
            </div>
            <div class="mb-2">
                <label class="form-label small fw-bold mb-0">Email Address</label>
                <p class="form-control-plaintext py-0 text-decoration-underline text-info"><?= htmlspecialchars($client['email']); ?></p>
            </div>
            <div class="mb-2">
                <label class="form-label small fw-bold mb-0">Contact Numbers</label>
                <p class="form-control-plaintext py-0">
                    <?= htmlspecialchars($client['phone1']); ?> <?= $client['phone2'] ? ' / ' . htmlspecialchars($client['phone2']) : ''; ?>
                </p>
            </div>
            <div class="mb-2">
                <label class="form-label small fw-bold mb-0">Service Address</label>
                <p class="form-control-plaintext py-0 small"><?= nl2br(htmlspecialchars($appointment['address'] ?? 'N/A')) ?></p>
            </div>
        </div>

        <!-- Right Column: Project Details -->
        <div class="col-md-6 ps-md-4 rounded bg-light border">
            <h6 class="text-primary border-bottom pb-2 mb-3"><i class="bi bi-info-circle-fill me-2"></i>Project Details</h6>
            <div class="mb-3">
                <label class="form-label small fw-bold mb-0">Project Name</label>
                <p class="form-control-plaintext py-0 fw-semibold text-white"><?= htmlspecialchars($quotation['project_name']); ?></p>
            </div>
        </div>
    </div>
    <div class="mb-2">
        <h5 class="form-label">Requested Services</h5>

        <?php
        $serviceNames = [];

        foreach ($allServices as $service) {

            if (in_array($service['service_id'], $selectedServiceIds)) {
                $serviceNames[] = $service['service_name'];
            }
        }

        echo htmlspecialchars(implode(', ', $serviceNames));
        ?>
    </div>
    <!-- Items Table-like UI -->
    <h6 class="text-primary border-bottom pb-2 mb-3">
        Area Wise Quotation
    </h6>

    <?php foreach ($items as $area_id => $area): ?>

        <div class="card shadow-sm border mb-4">

            <!-- AREA HEADER -->
            <div class="card-header d-flex justify-content-between align-items-center"
                style="background-color:#b6a12c;color:white;">

                <h5 class="mb-0">
                    <?= htmlspecialchars($area['area_name']) ?>
                </h5>

                <span class="fw-bold">
                    Area Total :
                    Rs. <?= number_format($area['area_total'] ?? 0, 2) ?>
                </span>

            </div>

            <div class="card-body">

                <!-- ITEMS TABLE -->
                <div class="table-responsive">

                    <table class="table table-bordered align-middle">

                        <thead class="table-dark">
                            <tr>
                                <th width="10%">#</th>
                                <th width="40%">Item</th>
                                <th width="15%" class="text-center">Qty</th>
                                <th width="15%" class="text-end">Unit Price</th>
                                <th width="20%" class="text-end">Subtotal</th>
                            </tr>
                        </thead>

                        <tbody>

                            <?php foreach ($area['items'] as $it): ?>

                                <tr>

                                    <td>
                                        <?= $it['item_no'] ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($it['itemName'] ?? 'Item') ?>
                                    </td>

                                    <td class="text-center">
                                        <?= number_format($it['quantity'], 2) ?>
                                    </td>

                                    <td class="text-end">
                                        <?= number_format($it['unit_price'], 2) ?>
                                    </td>

                                    <td class="text-end fw-bold">
                                        <?= number_format($it['total'], 2) ?>
                                    </td>

                                </tr>

                            <?php endforeach; ?>

                        </tbody>

                    </table>

                </div>

                <!-- AREA COST SUMMARY -->
                <div class="row mt-3 g-3">

                    <div class="col-md-3">
                        <div class="border rounded p-3 bg-light">

                            <small class="text-muted d-block">
                                Material Cost
                            </small>

                            <h6 class="fw-bold mb-0">
                                Rs. <?= number_format($area['material_total'] ?? 0, 2) ?>
                            </h6>

                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="border rounded p-3 bg-light">

                            <small class="text-muted d-block">
                                Labour Charge
                            </small>

                            <h6 class="fw-bold mb-0">
                                Rs. <?= number_format($area['labour_charge'] ?? 0, 2) ?>
                            </h6>

                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="border rounded p-3 bg-light">

                            <small class="text-muted d-block">
                                Transport Charge
                            </small>

                            <h6 class="fw-bold mb-0">
                                Rs. <?= number_format($area['transport_charge'] ?? 0, 2) ?>
                            </h6>

                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="border rounded p-3 bg-light">

                            <small class="text-muted d-block">
                                Other Charges
                            </small>

                            <h6 class="fw-bold mb-0">
                                Rs. <?= number_format($area['other_charge'] ?? 0, 2) ?>
                            </h6>

                        </div>
                    </div>

                </div>

                <!-- DESCRIPTION -->
                <?php if (!empty($area['description'])): ?>

                    <div class="mt-3">

                        <label class="fw-bold small text-muted">
                            Description
                        </label>

                        <div class="border rounded p-3 bg-light">
                         <?= htmlspecialchars(preg_replace('/\r\n|\r|\n/', ' ', $area['description'] ?? ''))?>
                            <!-- <?= nl2br(htmlspecialchars($area['description'])) ?> -->
                        </div>

                    </div>

                <?php endif; ?>

            </div>

        </div>

    <?php endforeach; ?>

    <!-- GRAND TOTAL -->
    <div class="card border-0 shadow-sm">

        <div class="card-body d-flex justify-content-between align-items-center">

            <h4 class="mb-0">
                Grand Total
            </h4>

            <h3 class="text-success fw-bold mb-0">
                Rs. <?= number_format($quotation['total_amount'], 2) ?>
            </h3>

        </div>

    </div>

    <!-- PROJECT IMAGES -->

    <?php
    $images = getQuotesImages($id);
    ?>

    <?php if (!empty($images)): ?>

        <div class="info-card">

            <h4 class="fw-bold mb-4">
                Project Images
            </h4>

            <div class="row g-2 project-images">

                <?php foreach ($images as $img): ?>

                    <div class="col-lg-2 image ">
                        <img
                            src="/decorluxe/uploads/quotation/<?= $img['image'] ?>"
                            alt="Draft image" class="border">
                        <i class="material-icons btn  mt-0 me-0">cancel</i>

                    </div>

                <?php endforeach; ?>

            </div>

        </div>

    <?php endif; ?>

    </div>

    <!-- Actions -->
    <div class="text-end mt-4 pt-3 border-top">
        <button type="button" class="btn btn-outline-light px-4 me-2" onclick="window.print()">
            <i class="bi bi-printer me-2"></i>Print
        </button>
        <!-- <button type="button" class="btn btn-secondary px-4" onclick="closeModal('viewQuoteModal')">Close</button> -->
    </div>
</form>