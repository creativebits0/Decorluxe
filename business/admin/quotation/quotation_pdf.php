<?php
require_once __DIR__ . '/../../../lib/mpdf/vendor/autoload.php';
require_once __DIR__ . '/../../../data/admin/quotation_model.php';
require_once __DIR__ . '/../../../data/admin/client_model.php';
require_once __DIR__ . '/../../../data/admin/appointment_model.php';

$id = $_GET['id'] ?? 0;

$quotation = getQuotationById($id);

if (!$quotation) {
    die("Quotation not found");
}

// Get quotation items
$items = getQuotationItemsGrouped($id);

// Get client details
// CLIENT DETAILS
$client = getClientById($quotation['client_id']);

// APPOINTMENT DETAILS
$appointment = getAppointmentById($quotation['appointment_id']);

// ADDRESS DETAILS
$address = null;

if (!empty($appointment['address_id'])) {

    $address = getAddressById($appointment['address_id']);
}

$html = '
<style>
    body {
        font-family: sans-serif;
        color: #000;
        font-size: 11pt;
    }

    .header-table {
        width: 100%;
        border-bottom: 2px solid #aa8c2c;
        padding-bottom: 10px;
    }

    .logo-text {
        font-size: 24pt;
        font-weight: bold;
        color: #000;
    }

    .gold-text {
        color: #aa8c2c;
    }

    .slogan {
        font-size: 9pt;
        font-style: italic;
        color: #333;
    }

    .quote-title {
        font-size: 18pt;
        font-weight: bold;
    }

    .quote-info-table {
        width: 100%;
        margin-top: 20px;
        margin-bottom: 20px;
        font-size:10pt;
    }

    .quote-info-table td {
        vertical-align: top;
    }

    .items-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }

    .items-table th {
        background: #000;
        color: #fff;
        padding: 10px;
        border: 1px solid #fff;
        text-align: left;
        font-size: 10pt;
    }

    .items-table td {
        border: 1px solid #000;
        padding: 8px;
        vertical-align: top;
        font-size: 10pt;
    }

    .area-title {
        font-size: 11pt;
        font-weight: bold;
        margin-bottom: 4px;
    }

    .desc {
        font-size: 10pt;
        color: #444;
    }

    .total-table {
        width: 100%;
        margin-top: 10px;
        border-collapse: collapse;
    }

    .total-table td {
        padding: 10px;
        font-weight: bold;
        font-size: 12pt;
        background: #000;
        color: #fff;
    }

    .terms-section {
        margin-top: 30px;
        font-size: 9pt;
    }

    .terms-section h4 {
        margin-bottom: 8px;
        text-decoration: underline;
    }

    .footer-info {
        margin-top: 30px;
        font-size: 9pt;
        color: #444;
    }
</style>

<table class="header-table">
    <tr>
        <td width="60%">
            <span class="logo-text">
                <span class="gold-text">DECOR</span>LUXE
            </span><br>

            <span style="font-size:14pt; font-weight:bold;">
                INTERIORS (PVT) LTD
            </span><br>

            <span class="slogan">
                Design beyond imagination
            </span>
        </td>

        <td width="40%" style="text-align:right; vertical-align:bottom;">
            <div class="quote-title">Price Quote</div>
        </td>
    </tr>
</table>

<table class="quote-info-table">
    <tr>
        <td width="60%">
            <strong>Quotation For:</strong><br><br>

          ' . htmlspecialchars($client['fullname'] ?? '') . '<br>

' . htmlspecialchars(
    $client['phone1']
        ?? $client['phone']
        ?? ''
) . '<br>

' . nl2br(htmlspecialchars(
    ($address['address'] ?? '') 

)) . '
        </td>

        <td width="40%" style="text-align:right; line-height:2rem;">
            <strong>Date:</strong> ' . date('F d, Y') . '<br>
            <strong>Quotation #:</strong> ' . htmlspecialchars($quotation['quotation_no']) . '<br>
            <strong>Valid Until:</strong> ' . date('F d, Y', strtotime($quotation['valid_until'])) . '
        </td>
    </tr>
</table>

<table class="items-table">
    <thead>
        <tr>
            <th width="10%">#</th>
            <th width="50%">Description</th>
            <th width="10%">Qty</th>
            <th width="10%">Unit Price</th>
            <th width="20%" style="text-align:center;">Amount</th>
        </tr>
    </thead>

    <tbody>
';
$counter = 1;

foreach ($items as $area_id => $areaData) {

    $areaName = $areaData['area_name'];
    $rows = $areaData['items'];

    // GET AREA TOTALS FROM FIRST ITEM
    $firstRow = $rows[0];

    $materialTotal = $firstRow['material_total'] ?? 0;
    $labourCharge  = $firstRow['labour_charge'] ?? 0;
    $transport     = $firstRow['transport_charge'] ?? 0;
    $otherCharge   = $firstRow['other_charge'] ?? 0;
    $areaTotal     = $firstRow['area_total'] ?? 0;
    $description   = $firstRow['description'] ?? '';

    // AREA HEADER
    $html .= '
    <tr style="background:#f2f2f2;">
        <td colspan="5"
            style="
                font-weight:bold;
                font-size:11pt;
                padding:10px;
                background:#ddd;
            ">
            AREA : ' . htmlspecialchars($areaName) . '
        </td>
    </tr>
    ';


    // AREA SUMMARY
    $html .= '

    <tr>
      
        <td style="text-align:right; font-weight:bold;">
            ' . ($counter++) . '
        </td>

        <td style="text-align: justify; font-weight:normal;">
            ' . ($description) . '
        </td>
        <td style="text-align:right; font-weight:bold;">
            -
        </td>
        <td style="text-align:right; font-weight:bold;">
            -
        </td>


        <td style="text-align:right; font-weight:bold;">
            ' . number_format($areaTotal, 2) . '
        </td>
    </tr>

    ';
}

$html .= '

    <tr>
        <td colspan="4"
            style="
                text-align:right;
                font-weight:bold;
                font-size:12pt;
                background:#000;
                color:#fff;
            ">
            GRAND TOTAL
        </td>

        <td style="
                text-align:right;
                font-weight:bold;
                font-size:12pt;
                background:#000;
                color:#fff;
            ">
            ' . number_format($quotation['total_amount'], 2) . '
        </td>
    </tr>

    </tbody>
</table>

<table class="total-table">
    <tr>
        <td width="70%" style="text-align:right;">
            Total Quoted Amount (LKR)
        </td>

        <td width="30%" style="text-align:right;">
            ' . number_format($quotation['total_amount'], 2) . '
        </td>
    </tr>
</table>

<div class="terms-section">
    <h4>Terms & Conditions</h4>

    <ol>
        <li>
            The above prices are valid for a period of 10 days from the date of this quotation.
        </li>

        <li>
            An advance payment of 60% of the total quoted amount shall be made prior to commencement of work, and the remaining 40% shall be settled upon completion of the work.
        </li>

        <li>
            The client is responsible for providing suitable ladder or access arrangements required for installation.
        </li>

        <li>
            Electrical, civil, plumbing or painting works are excluded unless stated otherwise.
        </li>
    </ol>
</div>

<div class="footer-info">
    <hr style="color:#aa8c2c;">

    0778922711 / 0771293424 |
    181A Avissawella Rd, Wellampitiya |
    decorluxesl@gmail.com
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
    'Quotation_' . $quotation['quotation_no'] . '.pdf',
    'I'
);
