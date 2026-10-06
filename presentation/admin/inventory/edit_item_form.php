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
?>

<form id="editItemForm" method="POST" action="/decorluxe/business/admin/inventory/edit_item_process.php" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?= htmlspecialchars($items['item_id']); ?>">
    <div class="row">
        <div class="col-md-6 mb-3">
            <label>Item Code</label>
            <input type="text" name="item_code" class="form-control" value="<?= htmlspecialchars($items['itemCode']); ?>" required>
        </div>
        <div class="col-md-6 mb-3">
            <label>Item Name</label>
            <input type="text" name="item_name" class="form-control" value="<?= htmlspecialchars($items['itemName']); ?>" required>
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
                <option value="Sqft" <?= $items['unit'] === 'Sqft' ? 'selected' : ''; ?>>Sqft</option>
                <option value="Lft" <?= $items['unit'] === 'Lft' ? 'selected' : ''; ?>>Lft</option>
                <option value="Pcs" <?= $items['unit'] === 'Pcs' ? 'selected' : ''; ?>>Pcs</option>
                <option value="Box" <?= $items['unit'] === 'Box' ? 'selected' : ''; ?>>Box</option>
            </select>
        </div>
        <div class="col-md-6 mb-3">
            <label>Category</label>
            <input type="text" name="brand" class="form-control" value="<?= htmlspecialchars($items['brand']); ?>">
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label>Min in Stock</label>
            <input type="number" name="min_in_stock" class="form-control" min="0" value="<?= htmlspecialchars($items['min_stock']); ?>">
        </div>
        <div class="col-md-6 mb-3">
            <label>Sell Price</label>
            <input type="number" name="sell_price" class="form-control" min="0" value="<?= htmlspecialchars($items['sellPrice']); ?>">
        </div>
    </div>

<!-- 
    <div class=" mb-3">
        <label>Quantity In Stock</label>
        <input type="number" name="qty_in_stock" class="form-control" readonly>
    </div> -->

    <!-- <div class="mb-3">
        <label>Item Image</label>
        <input type="file" name="item_image" class="form-control" accept="image/*">
    </div> -->
    <div class="mb-3">
        <label>Item Image</label>

        <?php if (!empty($items['image_path'])): ?>
            <div class="mb-2 text-center">
                <img src="/decorluxe/<?= htmlspecialchars($items['image_path']); ?>"
                    alt="Item Image"
                    class="rounded border"
                    width="150" height="120">

                <div class="mt-2 d-flex  justify-content-center">
                    <button type="button"
                        class="btn btn-danger btn-sm d-flex align-items-center"
                        onclick="deleteItemImage(<?= $items['item_id']; ?>)">
                        <i class="material-icons">delete</i> Delete Image
                    </button>
                </div>
            </div>
        <?php else: ?>
            <p class="text-muted text-center">No image available</p>
        <?php endif; ?>

        <input type="file" name="item_image" class="form-control mt-2" accept="image/*">
    </div>

    <!-- <div class="mb-3">
    <label>Description</label>
    <textarea name="description" class="form-control"></textarea>
  </div> -->

    <div class="text-end">
        <!-- <button type="button" class="btn btn-secondary" onclick="closeModal()">Cancel</button> -->
        <button type="submit" class="btn btn-dark">Edit Item</button>
    </div>
</form>