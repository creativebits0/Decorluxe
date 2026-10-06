<?php
require_once __DIR__ . '/../../data/admin/inventory_model.php';

$search = trim($_GET['search'] ?? '');

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$limit = 10;
$offset = ($page - 1) * $limit;

// Fetch data
$sales = getAllSales($limit, $offset, $search);
$totalRows = countSales($search);
$totalPages = ceil($totalRows / $limit);

// $sales = getAllSales();
ob_start();
?>

<table class="table table-striped table-hover border">
    <thead>
        <tr>
            <th>Sales Date</th>
            <th>Invoice No</th>
            <!-- <th>Purchase Amount</th> -->
            <th>Subtotal</th>
            <th>Discount</th>
            <th>Returned</th>
            <th>Net Total</th>
            <!-- <th>Generate</th> -->

            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($sales as $row): ?>
            <tr>
                <!-- sales_id,supplier_id, bill_no, sales_date, discount, total_amount,bill_fill, status -->
                <td><?= htmlspecialchars($row['sales_date']); ?></td>
                <td><?= htmlspecialchars($row['invoice_no']); ?></td>
                <!-- <td><?= htmlspecialchars($row['total_amount']); ?></td> -->
                <!-- <td><button>Gnerate Report</button></td> -->

                <td><?= number_format($row['subtotal'], 2); ?></td>

                <td>
                    <?php
                    if ($row['discount'] > 0) {
                        if ($row['discount_type'] == 'percent') {
                            echo $row['discount'] . '%';
                        } else {
                            echo 'Rs. ' . number_format($row['discount'], 2);
                        }
                    } else {
                        echo '-';
                    }
                    ?>
                </td>

                <td class="text-danger">
                    <?= number_format($row['returned_amount'], 2); ?>
                </td>

                <td class="text-success">
                    <?= number_format($row['total_amount'] - $row['returned_amount'], 2); ?>
                </td>

                <td class="d-flex justify-content-center gap-2">

                    <button class="btn btn-sm btn-primary  view-btn d-flex align-items-center gap-2" style="background-color: #2D332D;" onclick="viewSales(<?= $row['sales_id']; ?>)">
                        <i class="material-icons">visibility</i> View
                    </button>
                    <button class="btn btn-sm btn-success  edit-btn d-flex align-items-center gap-2" style="background-color: #6c757d;" onclick="editSales(<?= $row['sales_id']; ?>)">
                        <i class="material-icons">edit_square</i> Edit
                    </button>
                    <button
                        class="btn btn-warning btn-sm d-flex align-items-center gap-2 text-light"
                        onclick="returnSales(<?= $row['sales_id'] ?>)">
                        <i class="material-icons">undo</i>
                        Return
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
                    onclick="loadSales(<?= $page - 1 ?>, '<?= htmlspecialchars($search, ENT_QUOTES) ?>'); return false;">
                    Previous
                </a>
            </li>
        <?php endif; ?>

        <!-- Page Numbers -->
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                <a class="page-link"
                    href="#"
                    onclick="loadSales(<?= $i ?>, '<?= htmlspecialchars($search, ENT_QUOTES) ?>'); return false;">
                    <?= $i ?>
                </a>
            </li>
        <?php endfor; ?>

        <!-- Next -->
        <?php if ($page < $totalPages): ?>
            <li class="page-item">
                <a class="page-link"
                    href="#"
                    onclick="loadSales(<?= $page + 1 ?>, '<?= htmlspecialchars($search, ENT_QUOTES) ?>'); return false;">
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
    function loadSales(page = 1, search = '') {

        fetch(
                `/decorluxe/business/admin/sales_management_process.php?page=${page}&search=${encodeURIComponent(search)}`
            )
            .then(response => response.text())
            .then(html => {

                document.getElementById("dynamic-content").innerHTML = html;

            });
    }
</script>