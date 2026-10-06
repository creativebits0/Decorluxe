<?php

require_once __DIR__ . '/../../data/admin/supplier_model.php';

$search = trim($_GET['search'] ?? '');

// Parameters
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$limit = 10;
$offset = ($page - 1) * $limit;

// Fetch data
$supplier = getSupplier($limit, $offset, $search);
$totalRows = countSupplier($search);
$totalPages = ceil($totalRows / $limit);

// Return as HTML fragment for fetch()
ob_start();
?>

<table class="table table-striped table-hover border">
    <thead>
        <tr>
            <th>Supplier Name</th>
            <th>Address</th>
            <th>Phone</th>
            <th>Email</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($supplier as $row): ?>
            <tr>
                <td><?= htmlspecialchars($row['supplier_name']); ?></td>
                <td><?= htmlspecialchars($row['address']); ?></td>
                <td><?= htmlspecialchars($row['phone1']); ?></td>
                <td><?= htmlspecialchars($row['email']); ?></td>
                <td>
                    <div class="form-check form-switch">
                        <input id="<?= $row['supplier_id']; ?>"
                            class="form-check-input status mx-auto"
                            type="checkbox"
                            <?= $row['status'] == 'Active' ? 'checked' : ''; ?>>
                    </div>
                </td>

                <td class="d-flex justify-content-center gap-2">
                    <button class="btn btn-sm btn-primary  view-btn d-flex align-items-center gap-2" style="background-color: #2D332D;" onclick="viewSupplier(<?= $row['supplier_id']; ?>)">
                        <i class="material-icons">visibility</i> View
                    </button>
                    <button class="btn btn-sm btn-success  edit-btn d-flex align-items-center gap-2" style="background-color: #6c757d;" onclick="editSupplier(<?= $row['supplier_id']; ?>)">
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
                    onclick="loadSuppliers(<?= $page - 1 ?>, '<?= htmlspecialchars($search, ENT_QUOTES) ?>'); return false;">
                    Previous
                </a>
            </li>
        <?php endif; ?>

        <!-- Page Numbers -->
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                <a class="page-link"
                    href="#"
                    onclick="loadSuppliers(<?= $i ?>, '<?= htmlspecialchars($search, ENT_QUOTES) ?>'); return false;">
                    <?= $i ?>
                </a>
            </li>
        <?php endfor; ?>

        <!-- Next -->
        <?php if ($page < $totalPages): ?>
            <li class="page-item">
                <a class="page-link"
                    href="#"
                    onclick="loadSuppliers(<?= $page + 1 ?>, '<?= htmlspecialchars($search, ENT_QUOTES) ?>'); return false;">
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
    function loadSuppliers(page = 1, search = '') {

        fetch(
                `/decorluxe/business/admin/supplier_management_process.php?page=${page}&search=${encodeURIComponent(search)}`
            )
            .then(response => response.text())
            .then(html => {

                document.getElementById("dynamic-content").innerHTML = html;

            });
    }
</script>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        document.querySelectorAll(".form-check-input.status").forEach(toggle => {
            toggle.addEventListener("change", function() {
                const serviceId = this.id;
                const newServiceStatus = this.checked ? 'Active' : 'Inactive';

                fetch("/decorluxe/business/admin/suppliers/update_supplier_status.php", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/x-www-form-urlencoded"
                        },
                        body: `id=${serviceId}&status=${newServiceStatus}`
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            console.log(`User #${serviceId} status updated to ${newServiceStatus}`);

                            if (newServiceStatus == 'Active') {
                                alert("Succefully activate this Service!")
                            } else {
                                alert("Succefully inactivate this Service!")

                            }
                        } else {
                            alert("Failed to update service status!");
                            this.checked = !this.checked;
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        alert("Error connecting to server.");
                        this.checked = !this.checked;
                    });
            });
        });
    });
</script>