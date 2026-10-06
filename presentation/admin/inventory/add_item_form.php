<?php
session_start();
require_once '../../../data/admin/inventory_model.php';

// if ($_SESSION['role'] !== 'admin') {
//     http_response_code(403);
//     exit('Unauthorized access');
// }


?>

<form id="addItemForm" method="POST" action="/decorluxe/business/admin/inventory/add_item_process.php" enctype="multipart/form-data">
  <div class="row">
    <div class="col-md-6 mb-3">
      <label>Item Code</label>
      <input type="text" name="item_code" class="form-control" required>
    </div>
    <div class="col-md-6 mb-3">
      <label>Item Name</label>
      <input type="text" name="item_name" class="form-control" required>
    </div>
  </div>
  <!-- 
  <div class="row">
    <div class="col-md-6 mb-3">
      <label>Unit</label>
      <select name="unit" class="form-select" required>
        <option value="">Select Category</option>
        <option value="Metre">Metre</option>
        <option value="Feet">Feet</option>
        <option value="Furniture">Furniture</option>
      </select>
    </div>
    <div class="col-md-6 mb-3">
      <label>Brand</label>
      <input type="text" name="brand" class="form-control">
    </div>
  </div> -->

  <div class="row">
    <div class="col-md-6 mb-3">
      <label>Unit</label>
      <select name="unit" class="form-select" required>
        <option value="">Select Unit</option>
        <option value="Sqft">Sqft</option>
        <option value="Lft">Lft</option>
        <option value="Pcs">Pcs</option>
        <option value="Box">Box</option>
      </select>
    </div>
    <div class="col-md-6 mb-3">
      <label>Category</label>
      <input type="text" name="brand" class="form-control">
    </div>
  </div>

  <div class="row">
    <div class="col-md-6 mb-3">
      <label>Min in Stock</label>
      <input type="number" name="min_in_stock" class="form-control" min="0">
    </div>
    <div class="col-md-6 mb-3">
      <label>Sell Price</label>
      <input type="number" name="sell_price" class="form-control" min="0">
    </div>
  </div>


  <div class=" mb-3">
    <label>Quantity In Stock</label>
    <input type="number" name="qty_in_stock" class="form-control" readonly>
  </div>

<div class="mb-3">
  <label>Item Image</label>
  <input type="file" name="item_image" class="form-control" accept="image/*">
</div>

  <!-- <div class="mb-3">
    <label>Description</label>
    <textarea name="description" class="form-control"></textarea>
  </div> -->

  <div class="text-end">
    <!-- <button type="button" class="btn btn-secondary" onclick="closeModal()">Cancel</button> -->
    <button type="submit" class="btn btn-dark">Add Item</button>
  </div>
</form>