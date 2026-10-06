<?php
require_once '../../../data/admin/report_model.php';

$report_type    = $_GET['report_type'] ?? '';
$selected_date  = $_GET['selected_date'] ?? '';
$selected_month = $_GET['selected_month'] ?? '';
$selected_year  = $_GET['selected_year'] ?? '';
$item_id        = $_GET['item_id'] ?? '';

$where = [];

/* Daily */
if ($report_type == "daily" && $selected_date != '') {
    $where[] = "s.sales_date = '$selected_date'";
}

/* Monthly */
if ($report_type == "monthly" && $selected_month != '') {

    $month = date('m', strtotime($selected_month));
    $year  = date('Y', strtotime($selected_month));

    $where[] = "MONTH(s.sales_date) = '$month'";
    $where[] = "YEAR(s.sales_date) = '$year'";
}

/* Yearly */
if ($report_type == "yearly" && $selected_year != '') {
    $where[] = "YEAR(s.sales_date) = '$selected_year'";
}

/* Item */
if ($item_id != '') {
    $where[] = "si.item_id = '$item_id'";
}

$whereSQL = '';

if (count($where) > 0) {
    $whereSQL = "WHERE " . implode(" AND ", $where);
}

$sales = getSalesReport($whereSQL);
?>

<div class="card shadow-sm">

    <div class="card-body">

        <table class="table table-bordered table-striped">

            <thead class="table-dark">
                <tr>
                    <th>Date</th>
                    <th>Invoice No</th>
                    <th>Item</th>
                    <th>Qty</th>
                    <th>Unit Price</th>
                    <th>Total</th>
                    <!-- <th>Total</th> -->
                </tr>
            </thead>

            <tbody>

                <?php
                $grandTotal = 0;

                if (count($sales) > 0) {

                    foreach ($sales as $row) {

                        $grandTotal += $row['total'];
                ?>

                        <tr>
                            <td><?= $row['sales_date']; ?></td>
                            <td><?= $row['invoice_no']; ?></td>
                            <td><?= $row['itemName']; ?></td>
                            <td><?= $row['qty']; ?></td>
                            <td><?= number_format($row['unit_price'], 2); ?></td>
                            <td><?= number_format($row['total'], 2); ?></td>
                            <!-- <td><?= number_format($row['total_amount'], 2); ?></td> -->
                        </tr>

                    <?php
                    }
                } else {
                    ?>

                    <tr>
                        <td colspan="8" class="text-center text-danger">
                            No Records Found
                        </td>
                    </tr>

                <?php } ?>

            </tbody>

            <tfoot>
                <tr class="table-success">
                    <th colspan="5" class="text-end">Grand Total</th>
                    <th><?= number_format($grandTotal, 2); ?></th>
                </tr>
            </tfoot>

        </table>

    </div>
</div>