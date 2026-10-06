<?php
require_once __DIR__ . '/../../../lib/mpdf/vendor/autoload.php';
require_once __DIR__ . '/../../../data/admin/project_model.php';

$id = $_GET['id'];
$project = getProjectById($id);
$areas = getProjectAreas($id);

/* AREA DATA */
$areaData = [];
while ($area = $areas->fetch_assoc()) {
    $areaData[] = [
        'name' => $area['area_name'],
        'subtotal' => $area['subtotal'],
        'description' => $area['description']
    ];
}

$html = '
<style>
    body { font-family: sans-serif; color: #000; font-size: 11pt; }
    .header-table { width: 100%; border-bottom: 2px solid #aa8c2c; padding-bottom: 10px; }
    .logo-text { font-size: 24pt; font-weight: bold; color: #000; }
    .gold-text { color: #aa8c2c; }
    .slogan { font-size: 9pt; font-style: italic; color: #333; }
    
    .quote-info-table { width: 100%; margin-top: 20px; margin-bottom: 20px; line-height:2rem; font-size:10pt; }
    .quote-title { font-size: 18pt; font-weight: bold; margin-bottom: 5px; }
    
    table.items-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
    table.items-table th { 
        border: 1px solid #fff; 
        padding: 10px; 
        text-align: left; 
        background-color: #000; 
        font-weight: bold;
        color: #fff;
    }
    table.items-table td { border: 1px solid #000; padding: 10px; vertical-align: top; }
    
    .total-row { font-weight: bold; font-size: 13pt; }
    .footer-info { font-size: 9pt; color: #444; margin-top: 30px; }
    .terms-section { margin-top: 30px; font-size: 9pt; }
    .terms-section h4 { text-decoration: underline; margin-bottom: 5px; }
</style>

<table class="header-table">
    <tr>
        <td width="60%">
            <span class="logo-text"><span class="gold-text">DECOR</span>LUXE</span><br>
            <span style="font-size: 14pt; font-weight: bold;">INTERIORS (PVT) LTD</span><br>
            <span class="slogan">Design beyond imagination</span>
        </td>
        <td width="40%" style="text-align: right; vertical-align: bottom;">
            <div class="quote-title">Invoice</div>
        </td>
    </tr>
</table>

<table class="quote-info-table">
    <tr>
        <td width="60%">
            <strong>Invoice for:</strong><br>
            ' . htmlspecialchars($project['fullname']) . '<br>
            ' . $project['phone1'] . '<br>
            ' . htmlspecialchars($project['address']) . '
        </td>
        <td width="40%" style="text-align: right;">
            <strong>Date:</strong> ' . date('F d, Y') . '<br>
            <strong>Invoice #:</strong> ' . $project['project_no'] . '
        </td>
    </tr>
</table>

<table class="items-table">
    <thead>
        <tr>
            <th width="10%">Item No.</th>
            <th width="50%">Description</th>
            <th width="10%">Qty</th>
            <th width="10%">Unit Price</th>
            <th width="20%" style="text-align: center;">Amount</th>
        </tr>
    </thead>
    <tbody>';

$counter = 1;
foreach ($areaData as $area) {
    $html .= '
    <tr>
        <td style="text-align: center;">' . $counter . '</td>
        <td>
            <div style="font-weight: bold; font-size: 12pt;">' . 'Item ' . $counter . '-' . htmlspecialchars($area['name']) . '</div>
            <div style="font-size: 10pt; color: #444;">' . nl2br(htmlspecialchars($area['description'] ?? 'No description provided')) . '</div>
        </td>
                <td> </td>
                <td> </td>

        <td style="text-align: right; font-weight: bold;">
            ' . number_format($area['subtotal'], 0) . '
        </td>
    </tr>
';
    $counter++;
}

$html .= '
    <tr>
        <td colspan="4" style="text-align: right; font-weight:bold;">' . ' Total' . '</td>

      <td style="text-align: right; font-weight: bold;">
            ' . number_format($project['total_amount'], 0) . '
        </td>
    </tr>
    ';

$html .= '
    </tbody>
</table>

<table width="100%" style=" background-color: #000; color: #fff;  font-weight: bold;">
    <tr class="total-row">
        <td width="70%" style="text-align: right; padding-right: 10px;">Total Invoice Amount (LKR)</td>
        <td width="30%" style="text-align: right;">
            ' . number_format($project['total_amount'], 0) . '
        </td>
    </tr>
</table>

<div class="terms-section">
    <h4>Terms & Conditions</h4>
    <ol>
        <li>The above prices are valid for a period of 10 days from the date of this quotation.</li>
        <li>An advance payment of 60% of the total quoted amount shall be made prior to commencement of work, and the remaining 40% shall be settled upon completion of the work.</li>
        <li>The client is responsible for providing suitable ladder or access arrangements required for installation.</li>
        <li>Electrical, civil, plumbing or painting works are excluded unless stated otherwise.</li>
    </ol>
</div>

<div class="footer-info">
    <hr style="color: #aa8c2c;">
    0778922711 / 0771293424 | 181A Avissawella rd, Wellampitiya | decorluxesl@gmail.com
</div>
';

$mpdf = new \Mpdf\Mpdf(['margin_top' => 15, 'margin_bottom' => 15]);
$mpdf->WriteHTML($html);
$mpdf->Output('Invoice_' . $project['project_no'] . '.pdf', 'I');
