<?php

require_once __DIR__ . '/../../data/admin/user_modal.php';

// Parameters
$userType = $_GET['user_type'] ?? 'all';
$search = trim($_GET['search'] ?? '');

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

$limit = 10;
$offset = ($page - 1) * $limit;

$users = getUsers($userType, $limit, $offset, $search);

$totalRows = countUsers($userType, $search);

$totalPages = ceil($totalRows / $limit);

// Return as HTML fragment for fetch()
ob_start();
?>

<table class="table table-striped table-hover border">
    <thead>
        <tr>
            <th>Username</th>
            <th>Role</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Status</th>

            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $row): ?>
            <tr>
                <td><?= htmlspecialchars($row['username']); ?></td>
                <td><?= htmlspecialchars($row['role']); ?></td>
                <td><?= htmlspecialchars($row['email']); ?></td>
                <td><?= htmlspecialchars($row['phone1']); ?></td>
                <td>
                    <div class="form-check form-switch">
                        <input id="<?= $row['id']; ?>"
                            class="form-check-input status mx-auto"
                            type="checkbox"
                            <?= $row['status'] == '1' ? 'checked' : ''; ?>>
                    </div>
                </td>

                <td class="d-flex justify-content-center gap-2">
                    <button class="btn btn-sm text-white view-btn d-flex align-items-center gap-2" style="background-color: #2D332D;" onclick="viewUser(<?= $row['id']; ?>)">
                        <i class="material-icons">visibility</i> View
                    </button>
                    <button class="btn btn-sm text-white  edit-btn d-flex align-items-center gap-2" style="background-color: #6c757d;" onclick="editUser(<?= $row['id']; ?>)">
                        <i class="material-icons">edit_square</i> Edit
                    </button>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<nav class="mt-3">
    <ul class="pagination justify-content-center">

        <?php if ($page > 1): ?>
            <li class="page-item">
                <a class="page-link"
                    href="#"
                    onclick="loadUsers(
    '<?= $userType ?>',
    <?= $page - 1 ?>,
    '<?= htmlspecialchars($search) ?>'
)">
                    Previous
                </a>
            </li>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                <a class="page-link"
                    href="#"
                    onclick="loadUsers(
    '<?= $userType ?>',
    <?= $i ?>,
    '<?= htmlspecialchars($search) ?>'
)">
                    <?= $i ?>
                </a>
            </li>
        <?php endfor; ?>

        <?php if ($page < $totalPages): ?>
            <li class="page-item">
                <a class="page-link"
                    href="#"
                    onclick="loadUsers(
    '<?= $userType ?>',
    <?= $page + 1 ?>,
    '<?= htmlspecialchars($search) ?>'
)"">
                    Next
                </a>
            </li>
        <?php endif; ?>

    </ul>
</nav>

<?php
echo ob_get_clean();
?>

<!-- <script>
    document.addEventListener("DOMContentLoaded", () => {
        document.querySelectorAll(".form-check-input.status").forEach(toggle => {
            toggle.addEventListener("change", function() {
                const userId = this.id;
                const newStatus = this.checked ? 1 : 0;

                fetch("/decorluxe/business/admin/users/update_user_status.php", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/x-www-form-urlencoded"
                        },
                        body: `id=${userId}&status=${newStatus}`
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            console.log(`User #${userId} status updated to ${newStatus}`);

                            if (newStatus == 1) {
                                alert("Succefully activate this user!")
                            } else {
                                alert("Succefully inactivate this user!")

                            }
                        } else {
                            alert("Failed to update user status!");
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
</script> -->
<!-- <script>
    function loadUsers(userType, page = 1) {

        fetch(`/decorluxe/business/admin/user_management_process.php?user_type=${userType}&page=${page}`)
            .then(response => response.text())
            .then(html => {
                document.getElementById("dynamic-content").innerHTML = html;
            })
            .catch(error => {
                console.error("Error loading users:", error);
            });

        return false;
    }
</script> -->
<script>
    function loadUsers(userType, page = 1, search = '') {

    fetch(
        `/decorluxe/business/admin/user_management_process.php?user_type=${userType}&page=${page}&search=${encodeURIComponent(search)}`
    )
    .then(response => response.text())
    .then(html => {

        document.getElementById("dynamic-content").innerHTML = html;

    })
    .catch(error => {

        console.error(error);

    });

    return false;
}
</script>
<script>
    document.addEventListener("change", function(e) {

        if (!e.target.classList.contains("status")) {
            return;
        }

        const toggle = e.target;
        const userId = toggle.id;
        const newStatus = toggle.checked ? 1 : 0;

        fetch("/decorluxe/business/admin/users/update_user_status.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: `id=${userId}&status=${newStatus}`
            })
            .then(res => res.json())
            .then(data => {

                if (data.success) {

                    if (newStatus == 1) {
                        alert("Successfully activated this user!");
                    } else {
                        alert("Successfully deactivated this user!");
                    }

                } else {
                    alert("Failed to update user status!");
                    toggle.checked = !toggle.checked;
                }

            })
            .catch(err => {
                console.error(err);
                alert("Error connecting to server.");
                toggle.checked = !toggle.checked;
            });

    });
</script>