<?php
session_start();
require_once '../../../data/admin/inventory_model.php';

// if ($_SESSION['role'] !== 'admin') {
//     http_response_code(403);
//     exit('Unauthorized access');
// }

$id = $_GET['id'] ?? 0;
$items = getItemsById($id);
if (!$items) {
    echo "Item not found!";
    exit;
}
$item = getStockItemById($id);
?>

<form id="viewItemForm">
    <input type="hidden" name="id" value="<?= htmlspecialchars($items['item_id']); ?>">

    <div class="mb-3">
        <label>Item Image</label>

        <?php if (!empty($items['image_path'])): ?>
            <div class="mb-2 text-center">
                <img src="/decorluxe/<?= htmlspecialchars($items['image_path']); ?>"
                    alt="Item Image"
                    class="rounded border"
                    width="150" height="120">
            </div>
        <?php else: ?>
            <p class="text-muted text-center">No image available</p>
        <?php endif; ?>
    </div>
    <div class="row">
        <div class="col-md-6 mb-3">
            <label>Item Code</label>
            <input type="text" name="item_code" class="form-control" value="<?= htmlspecialchars($items['itemCode']); ?>" readonly>
        </div>
        <div class="col-md-6 mb-3">
            <label>Item Name</label>
            <input type="text" name="item_name" class="form-control" value="<?= htmlspecialchars($items['itemName']); ?>" readonly>
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
            <input type="text" name="brand" class="form-control" value="<?= htmlspecialchars($items['unit']); ?>" readonly>
        </div>
        <div class="col-md-6 mb-3">
            <label>Brand</label>
            <input type="text" name="brand" class="form-control" value="<?= htmlspecialchars($items['brand']); ?>" readonly>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label>Min in Stock</label>
            <input type="number" name="min_in_stock" class="form-control" min="0" value="<?= htmlspecialchars($items['min_stock']); ?>" readonly>
        </div>
        <div class="col-md-6 mb-3">
            <label>Sell Price</label>
            <input type="number" name="sell_price" class="form-control" min="0" value="<?= htmlspecialchars($items['sellPrice']); ?>" readonly>
        </div>
    </div>


    <div class=" mb-3">
        <label>Quantity In Stock</label>
        <input type="number" name="qty_in_stock" class="form-control" value="<?= number_format($item['stock_qty'] ?? 0, 0); ?>" readonly>
    </div>

    <!-- <div class="mb-3">
        <label>Item Image</label>
        <input type="file" name="item_image" class="form-control" accept="image/*">
    </div> -->


    <!-- <div class="mb-3">
    <label>Description</label>
    <textarea name="description" class="form-control"></textarea>
  </div> -->

    <div class="text-end">
        <!-- <button type="button" class="btn btn-secondary" onclick="closeModal()">Cancel</button> -->
    </div>
</form>