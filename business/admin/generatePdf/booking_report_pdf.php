<?php
require_once __DIR__ . '/../../../lib/mpdf/vendor/autoload.php';
require_once __DIR__ . '/../../../business/admin/reports/booking_controller.php';

$filters = $_GET ?? [];

$report = getBookingReport($filters);

$reportType = $_GET['report_type'] ?? '';
$fromDate = $_GET['from_date'] ?? '';
$toDate = $_GET['to_date'] ?? '';
$selectedMonth = $_GET['selected_month'] ?? '';
$year = $_GET['year'] ?? '';
$status = $_GET['status'] ?? '';

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
    padding:2px;
    background:#f5f5f5;
    border:1px solid #ddd;
}

.filter-box td{
    padding:3px;
    font-size:10pt;
    text-align:left;
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
            Booking Report
        </td>

    </tr>
</table>

<table width="100%" class="filter-box">

    <tr>
        <td><strong>Report Type:</strong>';


if ($reportType == 'daily') {
    $html .= 'Daily';
}
elseif ($reportType == 'monthly') {
    $html .= 'Monthly';
}
elseif ($reportType == 'yearly') {
    $html .= 'Yearly';
}
else {
    $html .= 'All';
}

$html .= '</td>';

if ($reportType == 'daily') {

    $html .= '
        <td><strong>From:</strong>
        '.$fromDate.'</td>

        <td><strong>To:</strong>
        '.$toDate.'</td>
    ';
}

elseif ($reportType == 'monthly') {

    $html .= '
        <td><strong>Month:</strong>
        '.date('F Y', strtotime($selectedMonth)).'</td>
    ';
}

elseif ($reportType == 'yearly') {

    $html .= '
        <td><strong>Year:</strong>
        '.$year.'</td>
    ';
}

$html .= '
    </tr>

</table>

<table class="report-table">

    <thead>

        <tr>
            <th width="5%">#</th>
            <th width="15%">Date</th>
            <th width="25%">Client</th>
            <th width="25%">Service</th>
            <th width="15%">Status</th>
            <th width="15%">Type</th>
        </tr>

    </thead>

    <tbody>
';

$i = 1;

foreach ($report as $row) {

    $html .= '
    <tr>

        <td>'.$i++.'</td>

        <td>
            '. date('d M Y', strtotime($row['appointment_date'])).' 
        </td>

        <td>
            '.htmlspecialchars($row['fullname']).'
        </td>

        <td>
            '.htmlspecialchars($row['service_name']).'
        </td>

        <td>
            '.$row['status'].'
        </td>

        <td>
            '.$row['appointment_type'].'
        </td>

    </tr>
    ';
}

if (count($report) == 0) {

    $html .= '
    <tr>
        <td colspan="7" style="text-align:center; color:red;">
            No records found
        </td>
    </tr>
    ';
}

$html .= '
    </tbody>

</table>

<div class="footer">

    <hr>

    Generated on '.date('F d, Y h:i A').'<br>

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
    'Booking_Report.pdf',
    'I'
);
?>