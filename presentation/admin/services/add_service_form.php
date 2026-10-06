<?php
session_start();
require_once '../../../data/admin/service_model.php';

// if ($_SESSION['role'] !== 'admin') {
//     http_response_code(403);
//     exit('Unauthorized access');
// }


?>

<form id="addServiceForm" method="POST" action="/decorluxe/business/admin/services/add_service_process.php">
  <div class="mb-3">
    <label>Service Name</label>
    <input type="text" name="service_name" class="form-control" required>
  </div>
  <div class="mb-3">
    <label>Base Price (LKR)(Per Feet)</label>
    <input type="number" step="0.01" name="base_price" class="form-control" min="0" required>
  </div>
  <div class="mb-3">
    <label>Description</label>
    <textarea name="description" class="form-control"></textarea>
  </div>
  <div class="text-center">
    <!-- <button type="button" class="btn btn-secondary" onclick="closeModal()">Cancel</button> -->
    <button type="submit" class="btn btn-dark w-100">Add Service</button>
  </div>
</form>
