<?php
session_start();
require_once '../../../data/admin/inventory_model.php';

$id = $_GET['id'] ?? 0;

$sales = getSalesById($id);
$items = getSalesItems($id);

if (!$sales) {
    echo "Sales not found!";
    exit;
}
?>

<form id="viewSalesForm">

    <input type="hidden" value="<?= $sales['sales_id'] ?>">

    <!-- Bill Image -->
    <div class="mb-3">
        <label>Sales Bill</label>

        <?php if (!empty($sales['bill_file'])): ?>

            <div class="text-center">

                <a href="/decorluxe/uploads/invoice/<?= $sales['bill_file'] ?>"
                    target="_blank">

                    <img
                        src="/decorluxe/uploads/invoice/<?= $sales['bill_file'] ?>"
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


    <!-- Sales Info -->

    <div class="row">

        <div class="col-md-6 mb-3">
            <label>Invoice No</label>
            <input type="text"
                class="form-control"
                value="<?= htmlspecialchars($sales['invoice_no']) ?>"
                readonly>
        </div>

        <div class="col-md-6 mb-3">
            <label>Sales Date</label>
            <input type="text"
                class="form-control"
                value="<?= htmlspecialchars($sales['sales_date']) ?>"
                readonly>
        </div>

    </div>


    <hr>

    <h5>Sales Items</h5>

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

            <?php
            $i = 1;
            foreach ($items as $item):
            ?>

                <tr>

                    <td><?= $i++ ?></td>

                    <td>
                        <?= htmlspecialchars($item['itemName']) ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($item['qty']) ?>
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
                value="<?= $sales['discount'] ?>"
                readonly>
        </div>

        <div class="col-md-6 mb-3">
            <label>Total</label>
            <input type="text"
                class="form-control"
                value="<?= $sales['total_amount'] ?>"
                readonly>
        </div>

    </div>


    <div class="text-end">
        <!-- <button
            type="button"
            class="btn btn-secondary"
            onclick="closeModal('viewSalesModal')">

            Close

        </button> -->
    </div>

</form>