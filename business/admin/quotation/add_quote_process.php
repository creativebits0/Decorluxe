<?php
// var_dump($_POST);
// exit;

session_start();
require_once __DIR__ . '/../../../data/admin/quotation_model.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $appointment_id = $_POST['appointment_id'];
    $quote_no     = $_POST['quote_no'] ?? '';
    $client_id    = $_POST['client_id'] ?? null;
    $valid_until  = $_POST['valid_till'] ?? null; // fix: your form field name is valid_till (not valid_until)
    $total        = $_POST['total'] ?? 0;
    $created_by   = $_SESSION['username'] ?? 'admin';
    $status       = $_POST['status'] ?? 'Pending';
    $project_name = $_POST['project_name'] ?? '';

    // Collect items from array fields
    $area_names = $_POST['area_name'] ?? [];
    $item_id = $_POST['item_id'] ?? [];
    $qtys         = $_POST['qty'] ?? [];
    $unit_prices  = $_POST['unit_price'] ?? [];
    $sub_totals   = $_POST['sub_total'] ?? [];
    $descriptions = $_POST['description'] ?? [];

    $items = [];
    for ($i = 0; $i < count($qtys); $i++) {
        // Skip empty rows
        if ($qtys[$i] === '' && $sub_totals[$i] === '') continue;

        $items[] = [
            'area_name'  => $area_names[$i] ?? '',
            'item_id'  => $item_id[$i] ?? '',
            'qty'         => (float)$qtys[$i],
            'unit_price'  => (float)$unit_prices[$i],
            'sub_total'   => (float)$sub_totals[$i],
            'description' => $descriptions[$i] ?? ''
        ];
    }

    // Validate
    if (!$client_id) {
        echo "❌ Missing client";
        exit;
    }
    if (empty($items)) {
        echo "❌ Missing items";
        exit;
    }

    // Save quotation
    $success = addQuotation(
        $appointment_id,
        $quote_no,
        $client_id,
        $project_name,
        $valid_until,
        $total,
        $items,
        $created_by,
        $status
    );

    echo $success ? "✅ Quotation added successfully" : "❌ Failed to add quotation";
}
