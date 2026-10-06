<?php
session_start();

require_once '../../../data/admin/quotation_model.php';
require_once '../../../data/admin/client_model.php';
require_once '../../../data/admin/appointment_model.php';
require_once '../../../data/admin/service_model.php';

$user = $_SESSION['username'];
$user_id =  $_SESSION['id'];



$id = $_GET['id'] ?? 0;

$quotation = getQuotationById($_GET['id']);

// $quotation = getQuotationById($id);
$items = getQuotationItemsGrouped($id);
$measurements = getMeasurementsByAppointment($quotation['appointment_id']);
$appointment = getAppointmentById($quotation['appointment_id']);
$client = getClientById($quotation['client_id']);
// if ($quotation['created_by'] != $_SESSION['id']) {
//     exit("You are not allowed to edit this quotation.");
// }

$allServices = getServicesForSelect();
$selectedServices = getQuotationServices($quotation['quotation_id']);

$canEdit = ($quotation['created_by'] == $_SESSION['id']);


$locked = !empty($quotation['project_status'])
    && $quotation['project_status'] !== 'Pending';


if (!$canEdit): ?>
    <div class="alert alert-warning">
        This quotation was created by another user. You can view the details, but you are not allowed to modify it.
    </div>
<?php endif;

?>
<style>
    /* Container styling */
    .quotation-header-section {
        border-top: 4px solid #D4AF37;
        /* Gold accent top */
        margin-bottom: 30px;
    }

    /* Label styling */
    .info-label {
        font-size: 0.85rem;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-weight: 700;
        margin-right: 15px;
        min-width: 100px;
    }

    /* The Luxe Input: Underline only */
    .luxe-input {
        border: none;
        border-bottom: 2px solid #dee2e6;
        border-radius: 0;
        padding: 8px 5px;
        background-color: transparent;
        outline: none;
        font-weight: 500;
        color: #2D332D;
        transition: border-color 0.3s ease;
    }

    /* Focus effect: Underline turns gold */
    .luxe-input:focus {
        border-bottom: 2px solid #D4AF37;
        background-color: #fffdf5;
        /* Very subtle gold tint on focus */
    }

    /* Specific styling for readonly fields */
    .luxe-input[readonly] {
        color: #888;
        border-bottom-style: dashed;
    }

    /* Adjusting Textarea to behave like an input */
    textarea.luxe-input {
        resize: none;
        overflow-y: hidden;
    }

    .note-text {
        max-height: 70px;
        overflow-y: auto;
    }
</style>

<form id="editQuoteForm" method="POST" action="/decorluxe/business/admin/quotation/edit_quote_process.php" enctype="multipart/form-data">

    <input type="hidden" name="quotation_id" value="<?= $quotation['quotation_id'] ?>">
    <input type="hidden" name="appointment_id" value="<?= $quotation['appointment_id'] ?>">
    <input type="hidden" name="created_by" value="<?= $user_id; ?>">
    <input type="hidden" name="client_id" value="<?= $quotation['client_id'] ?>">


    <div class="quotation-header-section p-3 bg-white shadow-sm rounded">
        <div class="row">
            <!-- LEFT SIDE: Technical Details -->
            <div class="col-md-5 border-end">
                <div class="mb-2 d-flex align-items-center">
                    <label class="info-label">Quote Num:</label>
                    <input type="text" class="luxe-input flex-grow-1" value="Q-2026-001" readonly>
                </div>
                <div class="mb-2 d-flex align-items-center">
                    <label class="info-label">Date:</label>
                    <input type="date" name="date" class="luxe-input flex-grow-1" value="<?= date('Y-m-d') ?>">

                </div>
                <div class="mb-2 d-flex align-items-center">
                    <label class="info-label">Valid Until:</label>
                    <input type="date" class="luxe-input flex-grow-1" name="valid_till" value="<?= $quotation['valid_until'] ?>">
                </div>
                <div class="mb-2 d-flex align-items-center">
                    <label class="info-label">Updated By:</label>
                    <input type="text" class="luxe-input flex-grow-1" value="<?= $user ?>" readonly>
                </div>
            </div>

            <!-- RIGHT SIDE: Project Details -->
            <div class="col-md-7 ps-md-4">
                <div class="mb-3 d-flex align-items-center">
                    <label class="info-label">Client Name</label>
                    <input type="text" class="luxe-input w-100 flex-grow-1" value="<?= htmlspecialchars($client['fullname']) ?>" readonly>
                </div>
                <div class="mb-3 d-flex align-items-center">
                    <label class="info-label">Project Address</label>
                    <textarea class="luxe-input w-100 flex-grow-1" rows="1" readonly><?= htmlspecialchars($appointment['address'] ?? '') ?></textarea>
                </div>
                <div class="mb-3 d-flex align-items-center">
                    <input
                        type="hidden"
                        name="old_status"
                        value="<?= $quotation['status'] ?>"> <label class="info-label">Status</label>
                    <select class="luxe-input w-100 flex-grow-1" name="status" id="quotation_status">
                        <option value="Pending" <?= $quotation['status'] == 'Pending' ? 'selected' : '' ?>
                            <?= ($quotation['status'] == 'Approved') ? 'disabled' : '' ?>>
                            Pending</option>
                        <option value="Approved" <?= $quotation['status'] == 'Approved' ? 'selected' : '' ?>>Approved</option>
                        <option value="Rejected" <?= $quotation['status'] == 'Rejected' ? 'selected' : '' ?>>Rejected</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
    <hr class="bg-light">
    <?php if (!empty($measurements)) { ?>

        <h5 class="mt-3 text-light">Site Measurements</h5>

        <div class="row g-3 mb-4">

            <?php foreach ($measurements as $m) { ?>

                <div class="col-md-3">

                    <div class="card bg-dark text-light border-secondary h-100">

                        <img src="/decorluxe/uploads/measurements/<?= $m['image_path'] ?>"
                            class="card-img-top"
                            style="height:120px; object-fit:cover; cursor:pointer;"
                            onclick="openImageModal(this.src)">

                        <div class="card-body">

                            <h6 class="card-title">
                                <?= htmlspecialchars($m['area_name']) ?>
                            </h6>

                            <?php if (!empty($m['notes'])) { ?>

                                <p class="card-text small note-text">
                                    <?= nl2br(htmlspecialchars($m['notes'])) ?>
                                </p>

                            <?php } ?>

                        </div>

                    </div>

                </div>

            <?php } ?>

        </div>

    <?php } ?>
    <hr>
    <div class="mb-3">
        <label class="form-label text-light fs-4">Requested Services</label>

        <div class="row">
            <?php foreach ($allServices as $service): ?>
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input
                            class="form-check-input"
                            type="checkbox"
                            name="services[]"
                            value="<?= $service['service_id'] ?>"
                            <?= in_array($service['service_id'], $selectedServices) ? 'checked' : '' ?>>

                        <label class="form-check-label">
                            <?= htmlspecialchars($service['service_name']) ?>
                        </label>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <button type="button"
        onclick="addArea()"
        class="btn btn-sm text-light mb-3 d-flex justify-content-center align-items-center gap-2 addAreaBtn"
        style="background-color:#b6a12c;">
        <i class="material-icons">&#xE147;</i> Add Area
    </button>

    <div id="areaContainer">

        <?php
        $areaIndex = 0;
        ?>

        <?php foreach ($items as $area_id => $areaData): ?>

            <?php
            $areaName = $areaData['area_name'] ?? '';
            $rows = $areaData['items'] ?? [];

            $prevAreaTotal = $areaData['area_total'] ?? 0;
            $prevMaterial = $areaData['material_total'] ?? 0;
            $prevLabour = $areaData['labour_charge'] ?? 0;
            $prevTransport = $areaData['transport_charge'] ?? 0;
            $prevOther = $areaData['other_charge'] ?? 0;
            $prevDescription = $areaData['description'] ?? '';
            ?>

            <div class="areaBox border p-3 mb-3">

                <!-- AREA HEADER -->
                <div class="d-flex justify-content-between mb-2">
                    <input type="text"
                        name="area_name[<?= $areaIndex ?>]"
                        class="form-control me-2"
                        value="<?= htmlspecialchars($areaName) ?>">

                    <button type="button"
                        class="btn btn-danger"
                        onclick="this.closest('.areaBox').remove(); updateGrandTotal();">
                        X
                    </button>
                </div>

                <!-- ITEMS TABLE -->
                <table class="table table-bordered align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Item</th>
                            <th width="120">Qty</th>
                            <th width="150">Unit Price</th>
                            <th width="150">Total</th>
                            <th width="70"></th>
                        </tr>
                    </thead>

                    <tbody class="itemBody">

                        <?php foreach ($rows as $row): ?>

                            <tr>
                                <td>
                                    <!-- <select name="item_id[<?= $areaIndex ?>][]"
                                        class="form-control item-name"
                                        data-selected="<?= $row['item_id'] ?>">
                                    </select> -->
                                    <select
                                        name="item_id[<?= $areaIndex ?>][]"
                                        class="form-control item-name"
                                        data-selected="<?= $row['item_id'] ?>"
                                        onchange="setUnitPrice(this)">
                                    </select>
                                </td>

                                <td>
                                    <input type="number"
                                        name="qty[<?= $areaIndex ?>][]"
                                        class="form-control qty"
                                        value="<?= $row['quantity'] ?>"
                                        oninput="calcRow(this)">
                                </td>

                                <td>
                                    <input type="number"
                                        name="unit_price[<?= $areaIndex ?>][]"
                                        class="form-control price"
                                        value="<?= $row['unit_price'] ?>"
                                        oninput="calcRow(this)">
                                </td>

                                <td>
                                    <input type="number"
                                        name="sub_total[<?= $areaIndex ?>][]"
                                        class="form-control total"
                                        value="<?= $row['total'] ?>"
                                        readonly>
                                </td>

                                <td>
                                    <button type="button"
                                        class="btn btn-danger btn-sm"
                                        onclick="this.closest('tr').remove(); updateAreaTotal(this.closest('.areaBox'))">
                                        X
                                    </button>
                                </td>
                            </tr>

                        <?php endforeach; ?>

                    </tbody>
                </table>

                <!-- ADD ITEM BUTTON -->
                <button type="button"
                    class="btn btn-sm btn-success mb-2"
                    onclick="addRow(this)">
                    + Add Item
                </button>

                <!-- AREA TOTALS -->
                <div class="row g-3 p-2 rounded"
                    style="background-color: rgba(255,255,255,0.05);">

                    <div class="col-md-3">
                        <label class="form-label small">Total Material Cost</label>
                        <input type="number"
                            name="material_cost[<?= $areaIndex ?>]"
                            class="form-control material-cost"
                            value="<?= $prevMaterial ?>"
                            readonly>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label small">Labour/Installation</label>
                        <input type="number"
                            name="labour_charge[<?= $areaIndex ?>]"
                            class="form-control labour-charge"
                            value="<?= $prevLabour ?>"
                            oninput="updateAreaTotal(this.closest('.areaBox'))">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label small">Transport</label>
                        <input type="number"
                            name="transport[<?= $areaIndex ?>]"
                            class="form-control transport"
                            value="<?= $prevTransport ?>"
                            oninput="updateAreaTotal(this.closest('.areaBox'))">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label small">Other Charges</label>
                        <input type="number"
                            name="other_charges[<?= $areaIndex ?>]"
                            class="form-control other-charges"
                            value="<?= $prevOther ?>"
                            oninput="updateAreaTotal(this.closest('.areaBox'))">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label small">Area Sub Total</label>
                        <input type="number"
                            name="area_total[<?= $areaIndex ?>]"
                            class="form-control area-total"
                            value="<?= $prevAreaTotal ?>"
                            readonly>
                    </div>

                    <div class="col-md-9">
                        <label class="form-label small">Description</label>
                        <textarea name="area_description[<?= $areaIndex ?>]"
                            class="form-control form-control-sm"
                            rows="2"><?= htmlspecialchars(str_replace(["\r\n", "\n", "\r"], " ", $prevDescription))  ?>
                        </textarea>
                    </div>

                </div>

            </div>

            <?php $areaIndex++; ?>

        <?php endforeach; ?>

    </div>

    <!-- Total -->
    <div style="background-color:#b6a12c;" class="p-2 rounded">
        <div class="mt-3">
            <label>Total</label>
            <input type="number" name="total" id="total" class="form-control"
                value="<?= $quotation['total_amount'] ?>" readonly>
        </div>
        <div class="mb-2">
            <label>Description</label>
            <textarea name="area_description[]" class="form-control"><?= htmlspecialchars(preg_replace('/\r\n|\r|\n/', ' ', $row['description'] ?? '')); ?></textarea>
            <!-- <textarea name="area_description[]" class="form-control"><?= $row['description'] ?? '' ?></textarea> -->
        </div>

        <div class="mb-3">
            <!-- Existing Images Gallery -->
            <label class="form-label text-light">Existing Quotation Images</label>
            <div class="d-flex flex-wrap gap-3 p-3 border border-secondary rounded bg-dark" style="min-height: 100px;">
                <?php
                $existingImages = getQuotesImages($id);
                if (!empty($existingImages)):
                    foreach ($existingImages as $img): ?>
                        <div class="position-relative image-card" id="img-container-<?= $img['id'] ?>">
                            <img src="/decorluxe/uploads/quotation/<?= $img['image'] ?>"
                                class="rounded border border-light"
                                style="width: 150px; height: 150px; object-fit: cover;">

                            <button type="button"
                                class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1 rounded-circle d-flex align-items-center justify-content-center"
                                style="width: 25px; height: 25px; padding: 0;"
                                onclick="markImageForDelete(this, <?= $img['id'] ?>)">
                                <i class="material-icons" style="font-size: 16px;">close</i>
                            </button>
                        </div>
                    <?php endforeach;
                else: ?>
                    <p class="text-secondary small m-auto">No images uploaded yet.</p>
                <?php endif; ?>
            </div>
        </div>
        <div id="deletedImagesContainer"></div>
        <label>Upload Images</label>
        <input type="file" name="images[]" multiple class="form-control">
    </div>
    </div>

    <div class="text-center mt-3">

        <button
            type="submit"
            class="btn btn-dark w-50"
            <?= (!$canEdit || $locked) ? 'disabled' : '' ?>>
            Update Quotation
        </button>

        <?php if (!$canEdit): ?>
            <small class="text-danger d-block mt-2">
                Only the user who created this quotation can update it.
            </small>
        <?php elseif ($locked): ?>
            <small class="text-danger d-block mt-2">
                This quotation cannot be edited because the related project has already started.
            </small>
        <?php endif; ?>
    </div>
    <!-- 

    <div class="text-center mt-3">
        <?php if ($canEdit): ?>
            <button type="submit" class="btn btn-dark w-50">
                Update Quotation
            </button>
        <?php else: ?>
            <button type="button" class="btn btn-secondary w-50" disabled>
                Update Not Allowed
            </button>
            <small class="text-danger d-block mt-2">
                Only the user who created this quotation can update it.
            </small>
        <?php endif; ?>
    </div> -->


</form>