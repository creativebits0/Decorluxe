<?php
require_once '../../../data/admin/project_model.php';
require_once '../../../data/admin/inventory_model.php';
require_once '../../../data/admin/service_model.php';

$allItems = getAllItems();


$id = $_GET['id'];

$project = getProjectById($id);
$items = getProjectItems($id);
$areas = getProjectAreas($id);

$projectServices = getProjectServices($id);
$selectedServices = array_column($projectServices, 'service_id');
$allServices = getServicesForSelect();


$itemOptions = '';
foreach ($allItems as $itm) {

    $itemOptions .= '
        <option 
            value="' . $itm['item_id'] . '" 
            data-name="' . htmlspecialchars($itm['itemName']) . '">

            ' . htmlspecialchars($itm['itemName']) . '

        </option>
    ';
}

$areaData = [];

while ($area = $areas->fetch_assoc()) {

    $areaData[$area['area_name']] = [
        'material_cost' => $area['material_cost'],
        'labour_charge' => $area['labour_charge'],
        'transport_charge' => $area['transport_charge'],
        'other_charge' => $area['other_charge'],
        'subtotal' => $area['subtotal'],
        'description' => $area['description']
    ];
}

$groupedItems = [];

while ($item = $items->fetch_assoc()) {

    $area = $item['area_name'] ?? 'General';

    $groupedItems[$area][] = $item;
}
?>

<style>
    .projectBox {
        background: #fff;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 20px;
        border-left: 5px solid #aa8c2c;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
    }

    .areaBox {
        border: #fff solid 1px;
        background: none;
    }

    .area-title {
        background: #aa8c2c;
        color: white;
        padding: 10px 15px;
        border-radius: 5px;
        margin-bottom: 15px;
        font-weight: bold;
    }

    .table th {
        background: #212529;
        color: white;
    }
</style>

<div id="itemOptionsTemplate" style="display:none;">

    <?php foreach ($allItems as $itm): ?>

        <option
            value="<?= $itm['item_id'] ?>"
            data-price="<?= $itm['sellPrice'] ?>"
            data-name="<?= htmlspecialchars($itm['itemName']) ?>">

            <?= $itm['itemCode'] . '-' . htmlspecialchars($itm['itemName'])  . ' (' . $itm['sellPrice'] . ')' ?>


        </option>

    <?php endforeach; ?>

</div>

<form
    id="projectForm"
    method="POST"
    enctype="multipart/form-data"
    action="/decorluxe/business/admin/projects/edit_project_process.php">

    <input type="hidden" name="project_id" value="<?= $id ?>">

    <!-- <input type="hidden" name="deleted_items" id="deleted_items">
    <input type="hidden" name="deleted_areas" id="deleted_areas"> -->
    <!-- Project Header -->

    <div class="projectBox">

        <div class="d-flex justify-content-between align-items-center">

            <div>

                <h4 class="mb-1">
                    <?= $project['project_no']; ?>
                </h4>

                <small class="text-muted">
                    <?= $project['project_name']; ?>
                </small>

            </div>

            <span class="badge bg-dark p-2">
                <?= $project['status']; ?>
            </span>

        </div>

    </div>

    <!-- Status & Dates -->

    <div class="projectBox">

        <div class="row">

            <div class="col-md-4">

                <label class="fw-bold">Status</label>

                <select name="status" id="projectStatus" class="form-control">
                    <option value="Pending"
                        <?= $project['status'] == 'Pending' ? 'selected' : '' ?>>
                        Pending
                    </option>

                    <option value="Started"
                        <?= $project['status'] == 'Started' ? 'selected' : '' ?>>
                        Started
                    </option>

                    <option value="In Progress"
                        <?= $project['status'] == 'In Progress' ? 'selected' : '' ?>>
                        In Progress
                    </option>

                    <option value="Completed"
                        <?= $project['status'] == 'Completed' ? 'selected' : '' ?>>
                        Completed
                    </option>

                </select>

            </div>

            <div class="col-md-4">

                <label class="fw-bold">Start Date</label>

                <input
                    type="datetime-local"
                    id="start_date"
                    name="start_date"
                    value="<?= $project['start_date'] ?>"
                    class="form-control">

            </div>

            <div class="col-md-4">

                <label class="fw-bold">Expected End</label>

                <input
                    type="date"
                    name="expected_end_date"
                    value="<?= substr($project['expected_end_date'], 0, 10) ?>"
                    class="form-control">

            </div>

        </div>

    </div>

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
    <hr>
    <!-- ADD AREA BUTTON -->

    <div class="mb-3 mt-3">

        <button
            type="button"
            class="btn btn-sm text-light mb-3 d-flex justify-content-center align-items-center gap-2"
            onclick=" addAreaContainer()"
            style="background-color:#b6a12c;">
            <i class="material-icons">&#xE147;</i> Add Area
        </button>

    </div>
    <!-- AREA WISE ITEMS -->

    <div id="allAreas">

        <?php foreach ($groupedItems as $area => $areaItems): ?>

            <?php $areaId = md5($area . rand()); ?>

            <div class="projectBox areaBox">

                <div class="d-flex justify-content-between align-items-center mb-3">

                    <div class="">

                        <div style="display: flex; align-items: center; gap: 30px;">

                            <label class="fw-bold mb-1">
                                AreaName
                            </label>

                            <input
                                type="text"
                                name="area_name_group[]"
                                value="<?= htmlspecialchars($area) ?>"
                                class="form-control areaInput">
                        </div>
                    </div>

                    <div class="mt-2">

                        <button
                            type="button"
                            class="btn btn-success btn-sm"
                            onclick="addProjectItem('area_<?= $areaId ?>')">

                            + Add Item

                        </button>

                        <button
                            type="button"
                            class="btn btn-danger btn-sm"
                            onclick="removeArea(this)">

                            Remove Area

                        </button>

                    </div>

                </div>

                <div class="table-responsive">

                    <table class="table table-bordered align-middle">

                        <thead>

                            <tr>
                                <th>Item Name</th>
                                <!-- <th>Description</th> -->
                                <th width="100">Qty</th>
                                <th width="150">Price</th>
                                <th width="150">Amount</th>
                                <th width="80">Action</th>
                            </tr>

                        </thead>

                        <tbody id="area_<?= $areaId ?>">

                            <?php foreach ($areaItems as $item): ?>

                                <tr data-id="<?= $item['project_item_id'] ?>">

                                    <td>
                                        <select
                                            name="item_name[]"
                                            class="form-control itemSelect" onchange="preventDuplicateItems(this.closest('tr'))">

                                            <option value="">Select Item</option>

                                            <?php foreach ($allItems as $itm): ?>

                                                <option
                                                    value="<?= $itm['item_id'] ?>"
                                                    <?= $item['item_id'] == $itm['item_id'] ? 'selected' : '' ?>
                                                    data-name="<?= htmlspecialchars($itm['itemName']) ?>"
                                                    data-price="<?= $itm['sellPrice'] ?>">

                                                    <?= $item['itemCode'] . '-' . $itm['itemName'] . ' (' . $itm['sellPrice'] . ')' ?>

                                                </option>


                                            <?php endforeach; ?>

                                        </select>
                                        <input
                                            type="hidden"
                                            name="item_name_hidden[]"
                                            class="itemNameInput"
                                            value="<?= htmlspecialchars($item['item_name']) ?>">


                                    </td>


                                    <td>

                                        <input
                                            type="number"
                                            name="qty[]"
                                            value="<?= $item['quantity'] ?>"
                                            class="form-control qty">

                                    </td>

                                    <td>

                                        <input
                                            type="number"
                                            name="price[]"
                                            value="<?= $item['price'] ?>"
                                            class="form-control price">

                                            

                                    </td>

                                    <td>

                                        <input
                                            type="text"
                                            class="form-control amount"
                                            value="<?= $item['amount'] ?>"
                                            readonly>

                                    </td>

                                    <td>

                                        <button
                                            type="button"
                                            class="btn btn-danger btn-sm"
                                            onclick="removeRow(this)">

                                            X

                                        </button>

                                    </td>

                                    <input
                                        type="hidden"
                                        name="item_id[]"
                                        value="<?= $item['project_item_id'] ?>">

                                    <input
                                        type="hidden"
                                        name="area_name[]"
                                        value="<?= htmlspecialchars($area) ?>"
                                        class="hiddenAreaName">

                                </tr>

                            <?php endforeach; ?>

                        </tbody>

                    </table>
                    <div class="row g-3 p-2 rounded" style="background-color: rgba(255,255,255,0.05);">

                        <div class="col-md-3">

                            <label class="form-label small">Material Cost</label>
                            <input type="number" name="material_cost[]" class="form-control material-cost" step="0.01"
                                value="<?= $areaData[$area]['material_cost'] ?? 0 ?>">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label small">Labour/Installation</label>
                            <input type="number" name="labour_charge[]" class="form-control labour-charge" value="<?= $areaData[$area]['labour_charge'] ?? 0 ?>" oninput="updateAreaTotal(this.closest('.areaBox'))">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label small">Transport</label>
                            <input type="number" name="transport_charge[]" class="form-control transport" value="<?= $areaData[$area]['transport_charge'] ?? 0 ?>" oninput="updateAreaTotal(this.closest('.areaBox'))">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label small">Other Expenditures</label>
                            <input type="number" name="other_charge[]" class="form-control other-charges" value="<?= $areaData[$area]['other_charge'] ?? 0 ?>" oninput="updateAreaTotal(this.closest('.areaBox'))">
                        </div>
                        <div class="col-md-3">

                            <label class="form-label small">Area Total Cost</label>
                            <input type="number" name="area_total[]" class="form-control areaTotal" step="0.01"
                                value="<?= $areaData[$area]['subtotal'] ?? 0 ?>">
                        </div>
                        <div class="col-md-9">
                            <label>Description</label>
                            <textarea name="description[]" class="form-control" rows="2"><?= htmlspecialchars(preg_replace('/\r\n|\r|\n/', ' ', ($areaData[$area]['description'] ?? ''))) ?></textarea>
                        </div>
                    </div>
                </div>
            </div>

        <?php endforeach; ?>
    </div>

    <!-- Upload Images -->
    <div style="background-color:#b6a12c;" class="p-2 rounded">

        <div class="w-25">

            <label class="fw-bold mb-2">
                Grand Total
            </label>

            <input type="number" name="total" id="total" class="form-control"
                value="<?= $project['total_amount'] ?>" readonly>

        </div>
        <div id="deletedImagesContainer"></div>

        <div class="">
            <!-- Existing Images Gallery -->
            <div class="mb-4">
                <label class="form-label text-light">Project Images</label>
                <div class="d-flex flex-wrap gap-3 p-3 border border-secondary rounded bg-dark" style="min-height: 100px;">
                    <?php
                    $existingImages = getProjectImages($id);
                    if (!empty($existingImages)):
                        foreach ($existingImages as $img): ?>
                            <div class="position-relative image-card" id="img-container-<?= $img['image_id'] ?>">
                                <img src="/decorluxe/uploads/projects/<?= $img['image_path'] ?>"
                                    class="rounded border border-light"
                                    style="width: 150px; height: 150px; object-fit: cover;">

                                <button type="button"
                                    class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1 rounded-circle d-flex align-items-center justify-content-center"
                                    style="width: 25px; height: 25px; padding: 0;"
                                    onclick="deleteImage(this, <?= $img['image_id'] ?>)">
                                    <i class="material-icons" style="font-size: 16px;">close</i>
                                </button>
                            </div>
                        <?php endforeach;
                    else: ?>
                        <p class="text-secondary small m-auto">No images uploaded yet.</p>
                    <?php endif; ?>
                </div>
            </div>

            <label class="fw-bold mb-2">
                Upload Project Images
            </label>

            <input
                type="file"
                name="images[]"
                multiple
                class="form-control">

        </div>

        <!-- Notes -->

        <div class="">

            <label class="fw-bold mb-2">
                Notes
            </label>

            <textarea
                name="notes"
                class="form-control"
                rows="4"><?= htmlspecialchars(preg_replace('/\r\n|\r|\n/', ' ', $project['notes'] ?? '' ))?></textarea>

        </div>
        <div class="d-flex justify-content-center">
            <button class="btn btn-dark w-50 py-2 mt-3">

                Update Project

            </button>
        </div>

    </div>
</form>

