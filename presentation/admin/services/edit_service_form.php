<?php
session_start();
require_once '../../../data/admin/service_model.php';

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

<form id="editServiceForm" method="POST" action="/decorluxe/business/admin/services/edit_service_process.php">

  <input type="hidden" name="id" value="<?= htmlspecialchars($service['service_id']); ?>">

  <div class="mb-3">
    <label>Service Name</label>
    <input
      type="text"
      name="service_name"
      class="form-control"
      value="<?= htmlspecialchars($service['service_name']); ?>"
      required>
  </div>

  <div class="mb-3">
    <label>Base Price (LKR)</label>
    <input
      type="number"
      step="0.01"
      name="base_price"
      class="form-control"
      value="<?= htmlspecialchars($service['base_price']); ?>"
      required>
  </div>

  <div class="mb-3">
    <label>Description</label>
    <textarea
      name="description"
      class="form-control"><?= htmlspecialchars($service['description']); ?></textarea>
  </div>

  <div class="text-center">
    <!-- <button type="button" class="btn btn-secondary" onclick="closeModal('editServiceModal')">Cancel</button> -->
    <button type="submit" class="btn btn-dark w-100">Update</button>
  </div>

</form>