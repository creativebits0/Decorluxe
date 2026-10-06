<?php

require_once __DIR__ . '/../../../lib/mpdf/vendor/autoload.php';
require_once __DIR__ . '/../../../data/admin/report_model.php';
require_once __DIR__ . '/../../../data/admin/inventory_model.php';
require_once __DIR__ . '/../../../data/admin/supplier_model.php';

$items = getAllitems();
$suppliers = getAllSupplier();

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

/*
|--------------------------------------------------------------------------
| FILTER DETAILS
|--------------------------------------------------------------------------
*/

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

/*
|--------------------------------------------------------------------------
| PDF HTML
|--------------------------------------------------------------------------
*/

$html = '
<style>

body{
    font-family:sans-serif;
    font-size:10pt;
    color:#000;
}

.header{
    border-bottom:2px solid #aa8c2c;
    padding-bottom:10px;
    margin-bottom:20px;
}

.logo{
    font-size:24pt;
    font-weight:bold;
}

.gold{
    color:#aa8c2c;
}

.title{
    text-align:right;
    font-size:18pt;
    font-weight:bold;
}

.filter-box{
    width:100%;
    border-collapse:collapse;
    margin-bottom:20px;
    background:#f5f5f5;
}

.filter-box td{
    border:1px solid #ddd;
    padding:8px;
    font-size:10pt;
}

.report-table{
    width:100%;
    border-collapse:collapse;
}

.report-table th{
    background:#000;
    color:#fff;
    border:1px solid #fff;
    padding:10px;
    font-size:10pt;
}

.report-table td{
    border:1px solid #000;
    padding:8px;
    font-size:9.5pt;
}

.footer{
    margin-top:30px;
    font-size:9pt;
    color:#555;
}

.total-row td{
    font-weight:bold;
    background:#f5f5f5;
}

</style>

<table width="100%" class="header">

    <tr>

        <td width="60%">

            <div class="logo">
                <span class="gold">DECOR</span>LUXE
            </div>

            <div>
                INTERIORS (PVT) LTD
            </div>

            <div style="font-size:9pt;">
                Design beyond imagination
            </div>

        </td>

        <td width="40%" class="title">

            Purchase Report

        </td>

    </tr>

</table>

<table class="filter-box">

    <tr>

        <td>
            <strong>Report Type:</strong> ';

if ($report_type == 'daily') {
    $html .= 'Daily';
} elseif ($report_type == 'monthly') {
    $html .= 'Monthly';
} elseif ($report_type == 'yearly') {
    $html .= 'Yearly';
} else {
    $html .= 'All';
}

$html .= '
        </td>

        <td>
            <strong>Item:</strong> ' . htmlspecialchars($itemName) . '
        </td>

        <td>
            <strong>Supplier:</strong> ' . htmlspecialchars($supplierName) . '
        </td>

    </tr>
';

if ($report_type == 'daily') {

    $html .= '
    <tr>
        <td colspan="3">
            <strong>Date:</strong> ' . $selected_date . '
        </td>
    </tr>
    ';
} elseif ($report_type == 'monthly') {

    $html .= '
    <tr>
        <td colspan="3">
            <strong>Month:</strong> ' . date('F Y', strtotime($selected_month)) . '
        </td>
    </tr>
    ';
} elseif ($report_type == 'yearly') {

    $html .= '
    <tr>
        <td colspan="3">
            <strong>Year:</strong> ' . $selected_year . '
        </td>
    </tr>
    ';
}

$html .= '
</table>

<table class="report-table">

    <thead>

        <tr>
            <th width="12%">Date</th>
            <th width="13%">Bill</th>
            <th width="20%">Supplier</th>
            <th width="20%">Item</th>
            <th width="10%">Qty</th>
            <th width="10%">Price</th>
            <th width="15%">Total</th>
        </tr>

    </thead>

    <tbody>
';

$total = 0;

foreach ($purchase as $row) {

    $total += $row['total'];

    $html .= '
    <tr>

        <td>' . $row['purchase_date'] . '</td>

        <td>' . $row['bill_no'] . '</td>

        <td>' . htmlspecialchars($row['supplier_name']) . '</td>

        <td>' . htmlspecialchars($row['itemName']) . '</td>

        <td>' . $row['quantity'] . '</td>

        <td>' . number_format($row['unit_price'], 2) . '</td>

        <td>' . number_format($row['total'], 2) . '</td>

    </tr>
    ';
}

if (count($purchase) == 0) {

    $html .= '
    <tr>
        <td colspan="7" style="text-align:center; color:red;">
            No records found
        </td>
    </tr>
    ';
}

$html .= '
<tr class="total-row">

    <td colspan="6" style="text-align:right;">
        Grand Total
    </td>

    <td>
        ' . number_format($total, 2) . '
    </td>

</tr>

</tbody>

</table>

<div class="footer">

    <hr>

    Generated on ' . date('F d, Y h:i A') . '<br>

    DECORLUXE Interiors (PVT) LTD

</div>
';

$mpdf = new \Mpdf\Mpdf([
    'margin_top' => 15,
    'margin_bottom' => 15,
    'margin_left' => 10,
    'margin_right' => 10
]);

$mpdf->WriteHTML($html);

$mpdf->Output(
    'Purchase_Report.pdf',
    'I'
);
