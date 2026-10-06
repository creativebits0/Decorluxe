<?php
require_once __DIR__ . '/../../../lib/mpdf/vendor/autoload.php';
require_once __DIR__ . '/../../../data/admin/report_model.php';
require_once __DIR__ . '/../../../data/admin/inventory_model.php';

/*
|--------------------------------------------------------------------------
| GET FILTERS
|--------------------------------------------------------------------------
*/

$report_type    = $_GET['report_type'] ?? '';
$selected_date  = $_GET['selected_date'] ?? '';
$selected_month = $_GET['selected_month'] ?? '';
$selected_year  = $_GET['selected_year'] ?? '';
$item_id        = $_GET['item_id'] ?? '';

/*
|--------------------------------------------------------------------------
| BUILD WHERE
|--------------------------------------------------------------------------
*/

$where = [];

if ($report_type == "daily" && $selected_date != '') {

    $where[] = "s.sales_date = '$selected_date'";
}

if ($report_type == "monthly" && $selected_month != '') {

    $month = date('m', strtotime($selected_month));
    $year  = date('Y', strtotime($selected_month));

    $where[] = "MONTH(s.sales_date) = '$month'";
    $where[] = "YEAR(s.sales_date) = '$year'";
}

if ($report_type == "yearly" && $selected_year != '') {

    $where[] = "YEAR(s.sales_date) = '$selected_year'";
}

if ($item_id != '') {

    $where[] = "si.item_id = '$item_id'";
}

$whereSQL = '';

if (count($where) > 0) {

    $whereSQL = "WHERE " . implode(" AND ", $where);
}

/*
|--------------------------------------------------------------------------
| GET REPORT DATA
|--------------------------------------------------------------------------
*/

$sales = getSalesReport($whereSQL);

/*
|--------------------------------------------------------------------------
| GET ITEM NAME
|--------------------------------------------------------------------------
*/

$items = getAllitems();

$itemName = 'All Items';

foreach ($items as $item) {

    if ($item['item_id'] == $item_id) {

        $itemName = $item['itemName'];
    }
}

/*
|--------------------------------------------------------------------------
| HTML
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
    margin-top:15px;
    margin-bottom:20px;
    background:#f5f5f5;
    border:1px solid #ddd;
    border-collapse:collapse;
}

.filter-box td{
    padding:6px 10px;
    font-size:10pt;
}

.report-table{
    width:100%;
    border-collapse:collapse;
    margin-top:10px;
}

.report-table th{
    background:#000;
    color:#fff;
    padding:10px;
    border:1px solid #fff;
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

.total-row{
    background:#f2f2f2;
    font-weight:bold;
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
            Sales Report
        </td>

    </tr>

</table>

<table width="100%" class="filter-box">

    <tr>

        <td width="20%">
            <strong>Report Type : </strong>

  ';

/*
|--------------------------------------------------------------------------
| REPORT TYPE
|--------------------------------------------------------------------------
*/

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

        <td width="20%">
            <strong>Item : </strong>

            ' . $itemName . '
        </td>

    </tr>
';

/*
|--------------------------------------------------------------------------
| DATE FILTERS
|--------------------------------------------------------------------------
*/

if ($report_type == 'daily') {

    $html .= '
    <tr>

        <td>
            <strong>Date : </strong>

            ' . $selected_date . '
        </td>

    </tr>
    ';
} elseif ($report_type == 'monthly') {

    $html .= '
    <tr>

        <td>
            <strong>Month : </strong>

            ' . date('F Y', strtotime($selected_month)) . '
        </td>

    </tr>
    ';
} elseif ($report_type == 'yearly') {

    $html .= '
    <tr>

        <td>
            <strong>Year : </strong>

            ' . $selected_year . '
        </td>

    </tr>
    ';
}

$html .= '
</table>

<table class="report-table">

    <thead>

        <tr>
            <th width="8%">#</th>
            <th width="18%">Date</th>
            <th width="20%">Invoice No</th>
            <th width="22%">Item</th>
            <th width="10%">Qty</th>
            <th width="12%">Price</th>
            <th width="15%">Total</th>
        </tr>

    </thead>

    <tbody>
';

$grandTotal = 0;
$i = 1;

/*
|--------------------------------------------------------------------------
| REPORT ROWS
|--------------------------------------------------------------------------
*/

foreach ($sales as $row) {

    $grandTotal += $row['total'];

    $html .= '
    <tr>

        <td>' . $i++ . '</td>

        <td>
            ' . $row['sales_date'] . '
        </td>

        <td>
            ' . $row['invoice_no'] . '
        </td>

        <td>
            ' . htmlspecialchars($row['itemName']) . '
        </td>

        <td>
            ' . $row['qty'] . '
        </td>

        <td>
            ' . number_format($row['unit_price'], 2) . '
        </td>

        <td>
            ' . number_format($row['total'], 2) . '
        </td>

    </tr>
    ';
}

/*
|--------------------------------------------------------------------------
| NO RECORDS
|--------------------------------------------------------------------------
*/

if (count($sales) == 0) {

    $html .= '
    <tr>

        <td colspan="7" style="text-align:center; color:red;">
            No records found
        </td>

    </tr>
    ';
}

/*
|--------------------------------------------------------------------------
| GRAND TOTAL
|--------------------------------------------------------------------------
*/

$html .= '
    <tr class="total-row">

        <td colspan="6" style="text-align:right;">
            Grand Total
        </td>

        <td>
            ' . number_format($grandTotal, 2) . '
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

/*
|--------------------------------------------------------------------------
| GENERATE PDF
|--------------------------------------------------------------------------
*/

$mpdf = new \Mpdf\Mpdf([
    'margin_top' => 15,
    'margin_bottom' => 15,
    'margin_left' => 10,
    'margin_right' => 10
]);

$mpdf->WriteHTML($html);

$mpdf->Output(
    'Sales_Report.pdf',
    'I'
);
