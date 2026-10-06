<?php
session_start();
require_once '../../../data/admin/inventory_model.php';

$redirect = "/decorluxe/presentation/admin/sales_content.php";

if ($_SESSION['role'] === 'show_incharge') {
    $redirect = "/decorluxe/presentation/show_incharge/sales_content.php";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // $sales_id   = $_POST['sales_id'];
    $invoice_no    = $_POST['invoice_no'];
    $sales_date = $_POST['sales_date'];
    $total         = $_POST['total'];

    $discount_type      = $_POST['final_discount_type'] ?? '';
    $discount      = $_POST['final_discount'] ?? 0;
    $status        = $_POST['status'] ?? "Pending";
    $created_by    = $_SESSION['username'] ?? 'admin';

    /* ---------- Upload Bill ---------- */

    $bill_file = null;

    if (!empty($_FILES['item_image']['name'])) {

        $targetDir = "../../../uploads/invoice/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        $fileName = time() . "_" . $_FILES['item_image']['name'];
        $targetFile = $targetDir . $fileName;

        move_uploaded_file($_FILES['item_image']['tmp_name'], $targetFile);

        $bill_file = $fileName;
    }

    /* ---------- Items ---------- */

    // $item_ids   = $_POST['item_id'];
    // $qtys       = $_POST['qty'];
    // $prices     = $_POST['unit_price'];
    // $discount_types = $_POST['discount_type'];
    // $discounts  = $_POST['discount'];
    // $sub_totals = $_POST['sub_total'];


    $item_ids = $_POST['item_id'] ?? [];
    $qtys = $_POST['qty'] ?? [];
    $prices = $_POST['unit_price'] ?? [];
    $discount_types = $_POST['discount_type'] ?? [];
    $discounts = $_POST['discount'] ?? [];
    $sub_totals = $_POST['sub_total'] ?? [];


    // Prevent submitting without any items
    if (empty($item_ids)) {
        $_SESSION['alert_message'] = "❌ Please add at least one item before saving the sales.";

        header("Location: $redirect");
        exit;
    }

    $items = [];

    for ($i = 0; $i < count($item_ids); $i++) {

        if (!$item_ids[$i]) continue;

        $items[] = [
            'item_id'  => $item_ids[$i],
            'qty'      => $qtys[$i],
            'price'    => $prices[$i],
            'discount_type' => $discount_types[$i] ?? null,
            'discount' => $discounts[$i] ?? 0,
            'total'    => $sub_totals[$i]
        ];
    }

    /* ---------- Save ---------- */

    $success = addSales(
        $invoice_no,
        $sales_date,
        $discount_type,
        $discount,
        $total,
        $created_by,
        $bill_file,
        $items
    );

    // echo $success ? "✅ Sales Saved" : "❌ Error";
    if ($success) {
        // $_SESSION['success_message'] = "✅ Sales Details Added Successfully";
        header("Location: $redirect");
        exit;
    } else {
        // $_SESSION['alert_message'] = "❌ Failed to Add Sales Details";
        header("Location: $redirect");
        exit;
    }
}
