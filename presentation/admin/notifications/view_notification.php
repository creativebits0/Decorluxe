<?php

require_once '../../../data/admin/notification_model.php';

$id = $_GET['id'] ?? 0;

$notification = getNotificationById($id);

$quoteno = getQuotationNo(
    $notification['quotation_id']
);
if (!$notification) {
    exit("Notification not found");
}

$items = getQuotationItemTotals(
    $notification['quotation_id']
);

markNotificationAsRead($id);
?>

<div class="alert alert-info">
    <strong>Quotation No:</strong>
    <?= htmlspecialchars($quoteno) ?>
</div>
<table class="table table-bordered">

    <thead>

        <tr>
            <th>Item</th>
            <th>Total Qty</th>
        </tr>

    </thead>

    <tbody>

        <?php foreach ($items as $item): ?>

            <tr>

                <td>
                    <?= htmlspecialchars($item['itemName']) ?>
                </td>

                <td>
                    <?= $item['total_qty'] ?>
                </td>

            </tr>

        <?php endforeach; ?>

    </tbody>

</table>