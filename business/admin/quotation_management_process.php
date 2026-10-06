<?php
require_once __DIR__ . '/../../data/admin/user_modal.php';
require_once __DIR__ . '/../../data/admin/quotation_model.php';
require_once __DIR__ . '/../../data/admin/client_model.php';

$search = trim($_GET['search'] ?? '');

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$limit = 10;
$offset = ($page - 1) * $limit;

// Fetch data
$quotes = getQuotations($limit, $offset, $search);
$totalRows = countQuotations($search);
$totalPages = ceil($totalRows / $limit);
ob_start();
?>

<table class="table table-striped table-hover border text-center">
    <thead>
        <tr>
            <th>Quotation no</th>
            <th>Client Name</th>
            <th>Created At</th>
            <th>Status</th>
            <th>Item Count</th>

            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($quotes as $row): ?>
            <?php
            $rowClass = '';
            $disabled = '';

            if ($row['status'] === 'Expired') {
                $rowClass = 'table-warning';
                $disabled = 'disabled';
            } elseif ($row['status'] === 'Rejected') {
                $rowClass = 'table-danger';
                $disabled = 'disabled';
            }
            ?>

            <tr class="<?= $rowClass ?>">
                <td><?= htmlspecialchars($row['quotation_no']); ?></td>
                <td><?= htmlspecialchars($row['fullname']); ?></td>
                <td><?= htmlspecialchars($row['created_at']); ?></td>
                <td><?= htmlspecialchars($row['status']); ?></td>
                <td><?= htmlspecialchars($row['item_count']); ?></td>


                <td class="d-flex justify-content-center gap-2">
                    <a
                        href="/decorluxe/business/admin/quotation/quotation_pdf.php?id=<?= $row['quotation_id'] ?>"
                        target="_blank"
                        class="btn btn-danger <?= $disabled ?>">
                        Generate PDF
                    </a>
                    <button class="btn btn-sm btn-primary  view-btn d-flex align-items-center gap-2" style="background-color: #2D332D;" onclick="viewQuote(<?= $row['quotation_id']; ?>)">
                        <i class="material-icons">visibility</i> View
                    </button>
                    <button class="btn btn-sm btn-success  edit-btn d-flex align-items-center gap-2" style="background-color: #6c757d;" onclick="editQuote(<?= $row['quotation_id']; ?>)" <?= $disabled ?>>
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
                    onclick="loadQuotations(<?= $page - 1 ?>, '<?= htmlspecialchars($search, ENT_QUOTES) ?>'); return false;">
                    Previous
                </a>
            </li>
        <?php endif; ?>

        <!-- Page Numbers -->
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                <a class="page-link"
                    href="#"
                    onclick="loadQuotations(<?= $i ?>, '<?= htmlspecialchars($search, ENT_QUOTES) ?>'); return false;">
                    <?= $i ?>
                </a>
            </li>
        <?php endfor; ?>

        <!-- Next -->
        <?php if ($page < $totalPages): ?>
            <li class="page-item">
                <a class="page-link"
                    href="#"
                    onclick="loadQuotations(<?= $page + 1 ?>, '<?= htmlspecialchars($search, ENT_QUOTES) ?>'); return false;">
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
    function loadQuotations(page = 1, search = '') {

        fetch(
                `/decorluxe/business/admin/quotation_management_process.php?page=${page}&search=${encodeURIComponent(search)}`
            )
            .then(response => response.text())
            .then(html => {

                document.getElementById("dynamic-content").innerHTML = html;

            });
    }
</script>