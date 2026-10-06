<?php
require_once '../../../data/admin/report_model.php';
require_once '../../../data/admin/inventory_model.php';
require_once '../../../data/admin/supplier_model.php';

$report_type   = $_GET['report_type'] ?? '';
$selected_date = $_GET['selected_date'] ?? '';
$selected_month = $_GET['selected_month'] ?? '';
$selected_year = $_GET['selected_year'] ?? '';
$item_id       = $_GET['item_id'] ?? '';
$supplier_id   = $_GET['supplier_id'] ?? '';

$where = [];

if ($report_type == "daily" && $selected_date != '') {
    $where[] = "p.purchase_date='$selected_date'";
}

if ($report_type == "monthly" && $selected_month != '') {
    $month = date('m', strtotime($selected_month));
    $year  = date('Y', strtotime($selected_month));

    $where[] = "MONTH(p.purchase_date)='$month'";
    $where[] = "YEAR(p.purchase_date)='$year'";
}

if ($report_type == "yearly" && $selected_year != '') {
    $where[] = "YEAR(p.purchase_date)='$selected_year'";
}

if ($item_id != '') {
    $where[] = "pi.item_id='$item_id'";
}

if ($supplier_id != '') {
    $where[] = "p.supplier_id='$supplier_id'";
}

$whereSQL = '';

if (count($where) > 0) {
    $whereSQL = "WHERE " . implode(" AND ", $where);
}

$purchase = getReportPurchase($whereSQL);
$items = getAllitems();
$suppliers = getAllSupplier();


// filter details
$itemName = 'All Items';

foreach ($items as $item) {

    if ($item['item_id'] == $item_id) {
        $itemName = $item['itemName'];
    }
}

$supplierName = 'All Suppliers';

foreach ($suppliers as $sup) {

    if ($sup['supplier_id'] == $supplier_id) {
        $supplierName = $sup['supplier_name'];
    }
}

?>

<div class="card shadow-sm mb-3">

    <div class="card-body">

        <div class="row g-3 text-start">

            <!-- Report Type -->
            <div class="col-md-2">
                <strong>Report Type: </strong>

                <?php
                if ($report_type == 'daily') {
                    echo 'Daily';
                } elseif ($report_type == 'monthly') {
                    echo 'Monthly';
                } elseif ($report_type == 'yearly') {
                    echo 'Yearly';
                } else {
                    echo 'All';
                }
                ?>
            </div>

            <!-- Daily -->
            <?php if ($report_type == 'daily') { ?>

                <div class="col-md-2">
                    <strong>Date :</strong>
                    <?= $selected_date ?: 'N/A'; ?>
                </div>

            <?php } ?>

            <!-- Monthly -->
            <?php if ($report_type == 'monthly') { ?>

                <div class="col-md-2">
                    <strong>Month : </strong>

                    <?=
                    $selected_month
                        ? date('F Y', strtotime($selected_month))
                        : 'N/A';
                    ?>
                </div>

            <?php } ?>

            <!-- Yearly -->
            <?php if ($report_type == 'yearly') { ?>

                <div class="col-md-2">
                    <strong>Year : </strong>
                    <?= $selected_year ?: 'N/A'; ?>
                </div>

            <?php } ?>

            <!-- Item -->
            <div class="col-md-3">
                <strong>Item : </strong>
                <?= htmlspecialchars($itemName); ?>
            </div>

            <!-- Supplier -->
            <div class="col-md-3">
                <strong>Supplier :</strong>
                <?= htmlspecialchars($supplierName); ?>
            </div>

        </div>

    </div>

</div>


<table width="100%" class="table table-bordered table-hover">
    <tr>
        <thead class="table-dark border">
            <th>Date</th>
            <th>Bill</th>
            <th>Supplier</th>
            <th>Item</th>
            <th>Quantity</th>
            <th>Unit Price</th>
            <th>Discount</th>
            <th>Total</th>
        </thead>

    </tr>

    <?php
    $total = 0;

    foreach ($purchase as $row) {
        $total += $row['total'];
    ?>

        <tr>
            <td><?= $row['purchase_date']; ?></td>
            <td><?= $row['bill_no']; ?></td>
            <td><?= $row['supplier_name']; ?></td>
            <td><?= $row['itemName']; ?></td>
            <td><?= $row['quantity']; ?></td>
            <td><?= $row['unit_price']; ?></td>
            <td><?= $row['discount']; ?></td>
            <td class="text-end"><?= number_format($row['total'], 2); ?></td>
        </tr>

    <?php } ?>

    <tr class="border fw-bold">
        <td colspan="7"  class="text-end">Grand Total</td>
        <td class="text-end"><?= number_format($total, 2); ?></td>
    </tr>

</table>