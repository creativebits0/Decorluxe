<?php

require_once __DIR__ . '/../../data/admin/inventory_model.php';
require_once __DIR__ . '/../../data/admin/material_request_model.php';

$search = trim($_GET['search'] ?? '');

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;

$limit  = 10;
$offset = ($page - 1) * $limit;

/* -----------------------------
   Search / Filter
------------------------------*/
$status = $_GET['status'] ?? '';

/* -----------------------------
   Get Stock Data
------------------------------*/
$items = getStockItems($limit, $offset, $search, $status);
$totalRows  = countStockItems($search, $status);
$totalPages = ceil($totalRows / $limit);


ob_start();
?>

<table class="table table-striped table-hover border align-middle">
    <thead>
        <tr>
            <!-- <th>Item Code</th> -->
            <th>Item Name</th>
            <!-- <th>Brand</th> -->
            <th>Min Stock</th>
            <th>Physical Stock</th>
            <th>Allocated</th>
            <th>Available </th>
            <th>Required By</th>
            <th>Last Update</th>
            <th>Reason</th>
            <th>Image</th>
        </tr>
    </thead>

    <tbody>

        <?php if (count($items) > 0): ?>
            <?php foreach ($items as $row): ?>
                <?php
                $count = getRequiredProjectCount($row['item_id']);
                ?>
                <?php
                $stock = $row['stock_qty'] ?? 0;
                $min   = $row['min_stock'] ?? 0;
                $available = $row['stock_qty'] - $row['allocated_qty'];
                $rowClass = ($available < $min) ? 'table-danger' : '';
                ?>
                <tr class="<?= $rowClass; ?>">
                    <!-- <td><?= htmlspecialchars($row['itemCode']) ?></td> -->
                    <td><?= htmlspecialchars($row['itemName']) ?></td>
                    <!-- <td><?= htmlspecialchars($row['brand']) ?></td> -->
                    <td><?= htmlspecialchars($row['min_stock']) ?></td>
                    <td><?= htmlspecialchars($stock) ?></td>
                    <td>
                        <?= number_format($row['allocated_qty'], 0) . ' ' . $row['unit']; ?>
                    </td>

                    <td>
                        <?= number_format($available, 0) . ' ' . $row['unit']; ?>
                    </td>
                    <td>

                        <?php if ($count > 0) { ?>
                            <a href="#"
                                class="badge bg-warning text-decoration-none"
                                onclick="showAllocationModal(<?= $row['item_id'] ?>);return false;">
                                <?= $count ?> Project<?= $count > 1 ? 's' : '' ?>
                            </a>
                        <?php } else { ?>

                            -

                        <?php } ?>
                    </td>

                    <td><?= htmlspecialchars($row['last_update'] ?? '-') ?></td>

                    <!-- REASON / TYPE -->
                    <td><?= htmlspecialchars($row['last_type'] ?? '-') ?></td>
                    <!-- LAST UPDATE -->
                    <td>
                        <?php if (!empty($row['image_path'])): ?>
                            <img src="/decorluxe/<?= htmlspecialchars($row['image_path']) ?>"
                                width="70" height="55"
                                class="rounded border">
                        <?php else: ?>
                            <span class="text-muted">No Image</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>

        <?php else: ?>

            <tr>
                <td colspan="7" class="text-center text-danger">
                    No Records Found
                </td>
            </tr>

        <?php endif; ?>

    </tbody>
</table>

<!-- Pagination -->
<nav class="mt-3">
    <ul class="pagination justify-content-center">

        <!-- Previous -->
        <?php if ($page > 1): ?>
            <li class="page-item">
                <a class="page-link"
                    href="#"
                    onclick="loadStock(
                                <?= $page - 1 ?>,
                                '<?= htmlspecialchars($search, ENT_QUOTES) ?>',
                                '<?= htmlspecialchars($status, ENT_QUOTES) ?>'
                            ); return false;">
                    Previous
                </a>
            </li>
        <?php endif; ?>

        <!-- Page Numbers -->
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                <a class="page-link"
                    href="#"
                    onclick="loadStock(
                                <?= $i ?>,
                                '<?= htmlspecialchars($search, ENT_QUOTES) ?>',
                                '<?= htmlspecialchars($status, ENT_QUOTES) ?>'
                            ); return false;">
                    <?= $i ?>
                </a>
            </li>
        <?php endfor; ?>

        <!-- Next -->
        <?php if ($page < $totalPages): ?>
            <li class="page-item">
                <a class="page-link"
                    href="#"
                    onclick="loadStock(
                                 <?= $page + 1 ?>,
                                '<?= htmlspecialchars($search, ENT_QUOTES) ?>',
                                '<?= htmlspecialchars($status, ENT_QUOTES) ?>'
                            ); return false;">
                    Next
                </a>
            </li>
        <?php endif; ?>

    </ul>
</nav>


<script>
    function loadStock(page = 1, search = '', status = '') {

        fetch(
                `/decorluxe/business/admin/stock_management_process.php?page=${page}&search=${encodeURIComponent(search)}&status=${encodeURIComponent(status)}`
            )
            .then(response => response.text())
            .then(html => {
                document.getElementById("dynamic-content").innerHTML = html;

                document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
                    new bootstrap.Tooltip(el);
                });
            });
    }
</script>

<?php
echo ob_get_clean();
?>