<?php
session_start();
require_once '../../../data/admin/inventory_model.php';

$id = $_GET['id'] ?? 0;

$purchase = getPurchaseById($id);
$items = getPurchaseItems($id);

if (!$purchase) {
    echo "Purchase not found!";
    exit;
}
?>

<form id="viewPurchaseForm">

    <input type="hidden" value="<?= $purchase['purchase_id'] ?>">

    <!-- Bill Image -->
    <div class="mb-3">
        <label>Purchase Bill</label>

        <?php if (!empty($purchase['bill_file'])): ?>

            <div class="text-center">

                <a href="/decorluxe/uploads/bills/<?= $purchase['bill_file'] ?>"
                    target="_blank">

                    <img
                        src="/decorluxe/uploads/bills/<?= $purchase['bill_file'] ?>"
                        class="border rounded"
                        width="150"
                        height="120"
                        style="cursor:pointer">

                </a>

                <p class="text-muted small">
                    Click image to view full bill
                </p>

            </div>

        <?php else: ?>

            <p class="text-muted text-center">
                No Bill Uploaded
            </p>

        <?php endif; ?>

    </div>


    <!-- Purchase Info -->

    <div class="row">

        <div class="col-md-6 mb-3">
            <label>Bill No</label>
            <input type="text"
                class="form-control"
                value="<?= htmlspecialchars($purchase['bill_no']) ?>"
                readonly>
        </div>

        <div class="col-md-6 mb-3">
            <label>Purchase Date</label>
            <input type="text"
                class="form-control"
                value="<?= htmlspecialchars($purchase['purchase_date']) ?>"
                readonly>
        </div>

    </div>


    <div class="row">

        <div class="col-md-6 mb-3">
            <label>Supplier</label>
            <input type="text"
                class="form-control"
                value="<?= htmlspecialchars($purchase['supplier_name']) ?>"
                readonly>
        </div>

        <div class="col-md-6 mb-3">
            <label>Status</label>
            <input type="text"
                class="form-control"
                value="<?= htmlspecialchars($purchase['status']) ?>"
                readonly>
        </div>

    </div>


    <hr>

    <h5>Purchased Items</h5>

    <table class="table table-bordered">

        <thead>
            <tr>
                <th>#</th>
                <th>Item</th>
                <th>Qty</th>
                <th>Price</th>
                <th>%</th>
                <th>Total</th>
            </tr>
        </thead>

        <tbody>

            <?php $i = 1;
            foreach ($items as $item): ?>

                <tr>

                    <td><?= $i++ ?></td>

                    <td>
                        <?= htmlspecialchars($item['itemName']) ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($item['quantity']) ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($item['unit_price']) ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($item['discount']) ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($item['total']) ?>
                    </td>

                </tr>

            <?php endforeach; ?>

        </tbody>

    </table>


    <hr>

    <div class="row">

        <div class="col-md-6 mb-3">
            <label>Discount</label>
            <input type="text"
                class="form-control"
                value="<?= $purchase['discount'] ?>"
                readonly>
        </div>

        <div class="col-md-6 mb-3">
            <label>Total</label>
            <input type="text"
                class="form-control"
                value="<?= $purchase['total_amount'] ?>"
                readonly>
        </div>

    </div>


    <div class="text-end">
        <!-- <button
            type="button"
            class="btn btn-secondary"
            onclick="closeModal('viewPurchaseModal')">

            Close

        </button> -->
    </div>

</form>