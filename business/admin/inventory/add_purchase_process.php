
<?php
session_start();
require_once '../../../data/admin/inventory_model.php';

$redirect = "/decorluxe/presentation/admin/purchase_content.php";

if ($_SESSION['role'] === 'show_incharge') {
    $redirect = "/decorluxe/presentation/show_incharge/purchase_content.php";
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $supplier_id   = $_POST['supplier_id'] ?? 0;
    $invoice_no    = $_POST['invoice_no'] ?? '';
    $purchase_date = $_POST['purchase_date'] ?? '';
    $total         = $_POST['total'] ?? 0;

    $discount_type      = $_POST['final_discount_type'] ?? '';
    $discount      = $_POST['final_discount'] ?? 0;
    $status        = $_POST['status'] ?? "Pending";
    $created_by    = $_SESSION['username'] ?? 'admin';

    /* ---------- Upload Bill ---------- */

    $bill_file = null;

    if (!empty($_FILES['item_image']['name'])) {

        $targetDir = "../../../uploads/bills/";

        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        $fileName   = time() . "_" . basename($_FILES['item_image']['name']);
        $targetFile = $targetDir . $fileName;

        move_uploaded_file($_FILES['item_image']['tmp_name'], $targetFile);

        $bill_file = $fileName;
    }

    /* ---------- Items ---------- */

    $item_ids   = $_POST['item_id'] ?? [];
    $qtys       = $_POST['qty'] ?? [];
    $prices     = $_POST['unit_price'] ?? [];
    $discount_types = $_POST['discount_type'] ?? [];
    $discounts  = $_POST['discount'] ?? [];
    $sub_totals = $_POST['sub_total'] ?? [];

    $items = [];

    for ($i = 0; $i < count($item_ids); $i++) {

        if (empty($item_ids[$i])) continue;

        $items[] = [
            'item_id'  => $item_ids[$i],
            'qty'      => $qtys[$i] ?? 0,
            'price'    => $prices[$i] ?? 0,
            'discount_type' => $discount_types[$i] ?? null,
            'discount' => $discounts[$i] ?? 0,
            'total'    => $sub_totals[$i] ?? 0
        ];
    }

    /* ---------- Save ---------- */

    $success = addPurchase(
        $supplier_id,
        $invoice_no,
        $purchase_date,
        $discount_type,
        $discount,
        $total,
        $status,
        $created_by,
        $bill_file,
        $items
    );

    // echo $success ? "✅ Purchase Saved" : "❌ Error Saving Purchase";
    if ($success) {
        // $_SESSION['success_message'] = "✅ Purchase Details Added Successfully";
        header("Location: $redirect");
        exit;
    } else {
        // $_SESSION['alert_message'] = "❌ Failed to Add Purchase Details";
        header("Location: $redirect");
        exit;
    }
}
?>