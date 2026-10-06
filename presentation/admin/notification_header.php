    <?php
    require_once '../../data/admin/notification_model.php';
    require_once '../../data/admin/inventory_model.php';

    $lowStockItems = getLowStockItems();
    $lowStockCount = count($lowStockItems);

    $count = getUnreadNotifyCountForAdmin();
    // $notifications = getUnreadNotify();
    $notifications = getNotifications();

    ?>
    <header id="topbar">

        <button class="btn btn-outline-secondary d-lg-none" id="toggleSidebar">
            <i class="bi bi-list"></i>
        </button>
        <div class="d-flex align-items-center gap-3">
            <h5 class="m-0 fw-semibold">Admin Dashboard</h5>

            <div class="calendar-card">
                <div class="calendar-month">
                    <?= strtoupper(date('M')) ?>
                </div>
                <div class="calendar-day">
                    <?= date('d') ?>
                </div>
            </div>
        </div>
        <div class="d-flex align-items-center gap-3">
            <div class="topbar-icons d-flex align-items-center">
                <div class="dropdown d-inline-flex align-items-center">

                    <a href="#"
                        class="position-relative text-dark d-flex align-items-center text-decoration-none"
                        data-bs-toggle="dropdown">

                        <i class="material-icons fs-4">production_quantity_limits</i>

                        <?php if ($lowStockCount > 0): ?>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger low-stock-badge">
                                <?= $lowStockCount ?>
                            </span>
                        <?php endif; ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end low-stock-dropdown p-3 ">

                        <li style="background-color: #ddd;">
                            <h6 class="dropdown-header">
                                Low Stock Items
                            </h6>
                        </li>

                        <?php if ($lowStockCount > 0): ?>

                            <?php foreach ($lowStockItems as $item): ?>

                                <li class="border-bottom mb-2 pb-2">
                                    <strong><?= htmlspecialchars($item['itemName']) ?></strong>
                                    <br>

                                    <small>
                                        Code: <?= htmlspecialchars($item['itemCode']) ?>
                                    </small>

                                    <br>

                                    <small class="text-danger">
                                        Stock: <?= $item['stock_qty'] ?>
                                        / Min: <?= $item['min_stock'] ?>
                                    </small>
                                </li>

                            <?php endforeach; ?>

                        <?php else: ?>

                            <li class="px-3 py-2 text-success">
                                No low stock items
                            </li>

                        <?php endif; ?>
                    </ul>
                </div>
                <div class="dropdown d-inline-flex align-items-center">

                    <a href="#"
                        class="position-relative text-dark d-flex align-items-center text-decoration-none"
                        data-bs-toggle="dropdown">

                        <i class="material-icons fs-4">notifications</i>

                        <?php if ($count > 0): ?>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger notification-badge">
                                <?= $count ?>
                            </span>
                        <?php endif; ?>

                    </a>

                    <ul class="dropdown-menu dropdown-menu-end notification-dropdown p-3">

                        <li style="background-color:#ddd;">
                            <h6 class="dropdown-header">
                                Notifications
                            </h6>
                        </li>

                        <?php if (!empty($notifications)): ?>

                            <?php foreach ($notifications as $row): ?>
                                <li class="border-bottom mb-2 pb-2 notification-row
                                 <?= $row['admin_read'] ? 'bg-light' : 'bg-white' ?>"
                                    data-read="<?= $row['admin_read'] ?>">
                                    <div class="notification-message <?= $row['admin_read']
                                                                            ? 'text-muted'
                                                                            : 'fw-bold text-dark' ?>">
                                        <?= htmlspecialchars($row['message']) ?>
                                    </div>
                                    <?php if (!$row['admin_read']) : ?>
                                        <span class="badge bg-danger notification-status">
                                            New
                                        </span>
                                    <?php else : ?>
                                        <span class="badge bg-secondary notification-status">
                                            Read
                                        </span>
                                    <?php endif; ?>
                                    <a href="#"
                                        class="small text-primary view-notification"
                                        data-id="<?= $row['notification_id'] ?>">
                                        View Details
                                    </a>
                                </li>

                            <?php endforeach; ?>

                        <?php else: ?>

                            <li class="px-3 py-2 text-success">
                                No Notifications
                            </li>

                        <?php endif; ?>

                    </ul>


                </div>
                <a href="#"
                    class="decoration-none"
                    onclick="openProfileEdit(<?= $_SESSION['id'] ?>)">
                    <i class="material-icons">person</i>
                </a>

                <a href="/decorluxe/index.php" class="decoration-none">
                    <i class="material-icons">home</i>
                </a>
            </div>
        </div>

    </header>
    <div id="editUserModal" class="modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content p-4">

                <div class="d-flex justify-content-between align-items-center">
                    <h5>Edit Profile</h5>

                    <span class="close"
                        onclick="closeModal('editUserModal')">
                        &times;
                    </span>
                </div>

                <hr>

                <div id="editUserContent"></div>

            </div>
        </div>
    </div>


    <!-- notification modal -->

    <div id="notificationModal" class="modal">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content p-4">

                <div class="d-flex justify-content-between align-items-center">
                    <h5>Notification Details</h5>

                    <span class="close"
                        onclick="closeModal('notificationModal')">
                        &times;
                    </span>
                </div>

                <hr>

                <div id="notificationContent">
                    Loading...
                </div>

            </div>
        </div>
    </div>
    <script>
        function openProfileEdit(id) {
            const modal =
                document.getElementById("editUserModal");
            const content =
                document.getElementById("editUserContent");
            modal.style.display = "block";
            content.innerHTML =
                "<p class='text-center'>Loading...</p>";
            fetch(`/decorluxe/presentation/admin/users/edit_user_form.php?id=${id}`)
                .then(res => res.text())
                .then(html => {
                    content.innerHTML = html;
                })
                .catch(() => {
                    content.innerHTML =
                        "<p class='text-danger'>Failed to load user.</p>";
                });
        }
    </script>

    <script>
        document.addEventListener('click', function(e) {

            const btn = e.target.closest('.view-notification');

            if (!btn) return;

            e.preventDefault();

            const notificationId = btn.dataset.id;

            const modal =
                document.getElementById('notificationModal');

            const content =
                document.getElementById('notificationContent');

            modal.style.display = 'block';

            content.innerHTML =
                "<p class='text-center'>Loading...</p>";

            fetch(
                    `/decorluxe/presentation/admin/notifications/view_project_notification.php?id=${notificationId}`
                )
                .then(res => res.text())
                .then(html => {

                    content.innerHTML = html;

                    const row = btn.closest('.notification-row');

                    const wasRead = row.dataset.read === "1";

                    if (!wasRead) {

                        // mark as read in UI
                        row.dataset.read = "1";

                        row.classList.remove('bg-white');
                        row.classList.add('bg-light');

                        const message =
                            row.querySelector('.notification-message');

                        if (message) {

                            message.classList.remove(
                                'fw-bold',
                                'text-dark'
                            );

                            message.classList.add(
                                'text-muted'
                            );
                        }

                        const statusBadge =
                            row.querySelector('.notification-status');

                        if (statusBadge) {

                            statusBadge.classList.remove('bg-danger');

                            statusBadge.classList.add('bg-secondary');

                            statusBadge.innerText = 'Read';
                        }

                        // decrease unread count only once
                        const badge =
                            document.querySelector(
                                '.notification-badge'
                            );

                        if (badge) {

                            let count =
                                parseInt(badge.innerText);

                            if (count > 1) {

                                badge.innerText = count - 1;

                            } else {

                                badge.remove();
                            }
                        }
                    }
                })
        });
    </script>