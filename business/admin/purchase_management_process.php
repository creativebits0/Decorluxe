<?php
require_once __DIR__ . '/../../data/admin/inventory_model.php';

$search = trim($_GET['search'] ?? '');


$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$limit = 10;
$offset = ($page - 1) * $limit;

// Fetch data
$purchase = getAllPurchase($limit, $offset ,$search);
$totalRows = countPurchases($search);
$totalPages = ceil($totalRows / $limit);

// $purchase = getAllPurchase();
ob_start();
?>

<table class="table table-striped table-hover border">
    <thead>
        <tr>
            <th>Purchase Date</th>
            <th>Bill No</th>
            <th>Supplier Name</th>
            <th>Status</th>
            <th>Purchase Amount</th>

            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($purchase as $row): ?>
            <tr>
                <!-- purchase_id,supplier_id, bill_no, purchase_date, discount, total_amount,bill_fill, status -->
                <td><?= htmlspecialchars($row['purchase_date']); ?></td>
                <td><?= htmlspecialchars($row['bill_no']); ?></td>
                <td><?= htmlspecialchars($row['supplier_name']); ?></td>
                <td><?= htmlspecialchars($row['status']); ?></td>
                <td><?= htmlspecialchars($row['total_amount']); ?></td>


                <td class="d-flex justify-content-center gap-2">
                    <button class="btn btn-sm btn-primary  view-btn d-flex align-items-center gap-2"  style="background-color: #2D332D;" onclick="viewPurchase(<?= $row['purchase_id']; ?>)">
                        <i class="material-icons">visibility</i> View
                    </button>
                    <button class="btn btn-sm btn-success  edit-btn d-flex align-items-center gap-2"  style="background-color: #6c757d;" onclick="editPurchase(<?= $row['purchase_id']; ?>)">
                        <i class="material-icons">edit_square</i> Edit
                    </button>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<nav class="mt-3">
    <ul class="pagination justify-content-center">

        <!-- Previous -->
        <?php if ($page > 1): ?>
            <li class="page-item">
              <a class="page-link"
                href="#"
                onclick="loadPurchase(<?= $page - 1 ?>, '<?= htmlspecialchars($search, ENT_QUOTES) ?>'); return false;">
                Previous
            </a>
            </li>
        <?php endif; ?>

        <!-- Page Numbers -->
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                 <a class="page-link"
                    href="#"
                    onclick="loadPurchase(<?= $i ?>, '<?= htmlspecialchars($search, ENT_QUOTES) ?>'); return false;">
                    <?= $i ?>
                </a>
            </li>
        <?php endfor; ?>

        <!-- Next -->
        <?php if ($page < $totalPages): ?>
            <li class="page-item">
                 <a class="page-link"
                    href="#"
                    onclick="loadPurchase(<?= $page + 1 ?>, '<?= htmlspecialchars($search, ENT_QUOTES) ?>'); return false;">
                    Next
                </a>
            </li>
        <?php endif; ?>

    </ul>
</nav>

<?php
echo ob_get_clean();
?>

<script>
    function loadPurchase(page = 1, search = '') {

        fetch(
                `/decorluxe/business/admin/purchase_management_process.php?page=${page}&search=${encodeURIComponent(search)}`
            )
            .then(response => response.text())
            .then(html => {

                document.getElementById("dynamic-content").innerHTML = html;

            });
    }
</script>

