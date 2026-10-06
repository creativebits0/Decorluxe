<?php

require_once __DIR__ . '/../../data/admin/client_model.php';

$search = trim($_GET['search'] ?? '');
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$limit = 10;
$offset = ($page - 1) * $limit;

// Fetch data
$AllClients = getClientsWithAddresses(
    $limit,
    $offset,
    $search
);

$totalRows = countClients($search);
$totalPages = ceil($totalRows / $limit);
// $AllClients = getAllClientsWithAddresses();
// Return as HTML fragment for fetch()
ob_start();


?>

<table class="table table-striped table-hover border">
    <thead>
        <tr>
            <th>Full Name</th>
            <th>Address</th>
            <th>Email</th>
            <th>Phone</th>

            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($AllClients as $row): ?>
            <tr>
                <td><?= htmlspecialchars($row['fullname']); ?></td>
                <td>
                    <?php
                    if (!empty($row['addresses'])) {

                        echo htmlspecialchars($row['addresses'][0]['address']);

                        if (count($row['addresses']) > 1) {
                            echo " <span class='text-muted'>(+" . (count($row['addresses']) - 1) . " more)</span>";
                        }
                    }
                    ?>
                </td>
                <td><?= htmlspecialchars($row['email']); ?></td>
                <td><?= htmlspecialchars($row['phone1']); ?></td>
                <td class="d-flex justify-content-center gap-2">
                    <button class="btn btn-sm btn-primary  view-btn d-flex align-items-center gap-2" style="background-color: #2D332D;" onclick="viewClient(<?= $row['client_id']; ?>)">
                        <i class="material-icons">visibility</i> View
                    </button>
                    <button class="btn btn-sm btn-success  edit-btn d-flex align-items-center gap-2" style="background-color: #6c757d;" onclick="editClient(<?= $row['client_id']; ?>)">
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
            <a class="page-link"
                href="#"
                onclick="loadClients(<?= $page - 1 ?>, '<?= htmlspecialchars($search, ENT_QUOTES) ?>'); return false;">
                Previous
            </a>
            </li>
        <?php endif; ?>

        <!-- Page Numbers -->
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                <a class="page-link"
                    href="#"
                    onclick="loadClients(<?= $i ?>, '<?= htmlspecialchars($search, ENT_QUOTES) ?>'); return false;">
                    <?= $i ?>
                </a>
            </li>
        <?php endfor; ?>

        <!-- Next -->
        <?php if ($page < $totalPages): ?>
            <li class="page-item">
                <a class="page-link"
                    href="#"
                    onclick="loadClients(<?= $page + 1 ?>, '<?= htmlspecialchars($search, ENT_QUOTES) ?>'); return false;">
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
    function loadClients(page = 1, search = '') {

        fetch(
                `/decorluxe/business/admin/client_management_process.php?page=${page}&search=${encodeURIComponent(search)}`
            )
            .then(response => response.text())
            .then(html => {

                document.getElementById("dynamic-content").innerHTML = html;

            });
    }
</script>