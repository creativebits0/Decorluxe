<?php
session_start();
// require_once '../../../data/admin/user_modal.php';
require_once __DIR__ . '/../../../data/admin/service_model.php';

// if ($_SESSION['role'] !== 'admin') {
//     http_response_code(403);
//     exit('Unauthorized access');
// }

$id = $_GET['id'] ?? 0;
$service = getServiceById($id);
if (!$service) {
    echo "Service not found!";
    exit;
}

?>

<form id="viewServiceForm">
    <input type="hidden" name="id" value="<?= htmlspecialchars($service['service_id']); ?>">
    <div class="mb-3">
        <label class="form-label">Service</label>
        <input class="form-control" value="<?= htmlspecialchars($service['service_name']); ?>" readonly>
    </div>
    <div class="mb-3">
        <label class="form-label">Base Price</label>
        <input class="form-control" value="<?= htmlspecialchars($service['base_price']); ?>" readonly>
    </div>

    <div class="mb-3">
        <label for="address" class="form-label">Description</label>
        <input class="form-control" value="<?= htmlspecialchars($service['description']); ?>" readonly>
    </div>
    <?php
    $status = $service['status'];
    $bgColor = $status == 1 ? '#28a745' : '#dc3545';
    $statusText = $status == 1 ? 'Service Available' : 'Service Not Available';
    ?>

    <div class="mb-3 col-6">
        <label class="form-label">Status</label>
        <input
            class="form-control text-white fw-semibold text-center"
            style="background-color: <?= $bgColor ?>;"
            value="<?= $statusText ?>"
            readonly>
    </div>

    <div class="text-end">
        <!-- <button type="button" class="btn btn-secondary" onclick="closeModal()">Cancel</button> -->
    </div>
</form>