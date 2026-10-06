<?php
require_once '../../../data/admin/inventory_model.php';

$id = $_GET['id'];

$sales = getSalesById($id);
$items = getSalesItems($id);

foreach ($items as $item): 

$returned = getReturnedQty($id, $item['item_id']);
$remaining = $item['qty'] - $returned;
?>

<form id="returnSalesForm" method="POST" action="/decorluxe/business/admin/inventory/return_sales_process.php">

    <input type="hidden" name="sales_id" value="<?= $id ?>">

    <label>Return Date</label>
    <input type="date"
        name="return_date"
        class="form-control mb-3"
        value="<?= date('Y-m-d') ?>">

    <label>Reason</label>
    <textarea
        name="reason"
        class="form-control mb-3"></textarea>


    <table class="table table-bordered">

        <thead>
            <tr>
                <th>Item</th>
                <th>Sold Qty</th>
                <th>Returned</th>
                <th>Remaining</th>
                <th>Return Qty</th>
            </tr>
        </thead>

        <tbody>

            <?php foreach ($items as $item): ?>

                <tr>

                    <td><?= $item['itemName'] ?></td>

                    <td><?= $item['qty'] ?></td>

                    <td><?= $returned ?></td>

                    <td><?= $remaining ?></td>

                    <td>
                        <!-- <input
                            type="number"
                            name="return_qty[]"
                            class="form-control"
                            max="<?= $item['qty'] ?>"
                            min="0"
                            value="0"> -->
                        <input
                            type="number"
                            name="return_qty[]"
                            class="form-control"
                            value="0"
                            min="0"
                            max="<?= $remaining ?>"
                            <?= $remaining == 0 ? 'disabled' : '' ?>>

                        <input type="hidden"
                            name="item_id[]"
                            value="<?= $item['item_id'] ?>">

                    </td>

                </tr>

            <?php endforeach ?>

        </tbody>

    </table>

    <button type="submit" class="btn btn-dark">
        Process Return
    </button>

</form>

<?php endforeach; ?>