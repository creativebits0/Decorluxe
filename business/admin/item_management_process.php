<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../data/admin/inventory_model.php';

// if (!isset($_SESSION['username']) || !isset($_SESSION['role'])) {
//     header("Location: /decorluxe/presentation/login.php");
//     exit();
// }

$userType = $_SESSION['role'];
$search = trim($_GET['search'] ?? '');

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$limit = 10;
$offset = ($page - 1) * $limit;

// Fetch data
$items = getItems(
    $limit,
    $offset,
    $search
);

$totalRows = countItems($search);
$totalPages = ceil($totalRows / $limit);

// Return as HTML fragment for fetch()
ob_start();


?>

<table class="table table-striped table-hover border">
    <thead>
        <tr>
            <th>Item Code</th>
            <th>Item Name</th>
            <th>Category</th>
            <th>Unit</th>
            <th>sell Price</th>
            <th>Min Stock</th>
            <th>Image</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($items as $row): ?>
            <tr>
                <td><?= htmlspecialchars($row['itemCode']); ?></td>
                <td><?= htmlspecialchars($row['itemName']); ?></td>
                <td><?= htmlspecialchars($row['brand']); ?></td>
                <td><?= htmlspecialchars($row['unit']); ?></td>
                <td><?= htmlspecialchars($row['sellPrice']); ?></td>
                <td><?= htmlspecialchars($row['min_stock']); ?></td>
                <td>
                    <?php if (!empty($row['image_path'])): ?>
                        <img src="/decorluxe/<?= htmlspecialchars($row['image_path']); ?>" width="80" height="50" class="rounded">
                    <?php endif; ?>
                </td>

                <td class="d-flex justify-content-center gap-2">
                    <button class="btn btn-sm btn-primary  view-btn d-flex align-items-center gap-2" style="background-color: #2D332D;" onclick="viewItem(<?= $row['item_id']; ?>)">
                        <i class="material-icons">visibility</i> View
                    </button>
                    <?php if ($userType == 'admin' || $userType == 'show_incharge') { ?>
                        <button class="btn btn-sm btn-success  edit-btn d-flex align-items-center gap-2" style="background-color: #6c757d;" onclick="editItem(<?= $row['item_id']; ?>)">
                            <i class="material-icons">edit_square</i> Edit
                        </button>
                    <?php }

                    ?>

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
                onclick="loadItems(<?= $page - 1 ?>, '<?= htmlspecialchars($search, ENT_QUOTES) ?>'); return false;">
                Previous
            </a>
            </li>
        <?php endif; ?>

        <!-- Page Numbers -->
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                 <a class="page-link"
                    href="#"
                    onclick="loadItems(<?= $i ?>, '<?= htmlspecialchars($search, ENT_QUOTES) ?>'); return false;">
                    <?= $i ?>
                </a>
            </li>
        <?php endfor; ?>

        <!-- Next -->
        <?php if ($page < $totalPages): ?>
            <li class="page-item">
                    <a class="page-link"
                    href="#"
                    onclick="loadItems(<?= $page + 1 ?>, '<?= htmlspecialchars($search, ENT_QUOTES) ?>'); return false;">
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
    function loadItems(page = 1, search = '') {

        fetch(
                `/decorluxe/business/admin/item_management_process.php?page=${page}&search=${encodeURIComponent(search)}`
            )
            .then(response => response.text())
            .then(html => {

                document.getElementById("dynamic-content").innerHTML = html;

            });
    }
</script>