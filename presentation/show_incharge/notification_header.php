    <?php
    require_once '../../data/admin/notification_model.php';
    require_once '../../data/admin/inventory_model.php';

    $lowStockItems = getLowStockItems();
    $lowStockCount = count($lowStockItems);

    $count = getUnreadNotificationCount();
    $notifications = getUnreadNotifications();
    ?>
    <header id="topbar">

        <button class="btn btn-outline-secondary d-lg-none" id="toggleSidebar">
            <i class="bi bi-list"></i>
        </button>
        <div class="d-flex align-items-center gap-3">
            <h5 class="m-0 fw-semibold">Showroom Manager Dashboard</h5>

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
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
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
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
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

                                <li class="border-bottom mb-2 pb-2">

                                    <div class="fw-semibold">
                                        <?= htmlspecialchars($row['message']) ?>
                                    </div>

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
                <i class="material-icons">person</i>

                <a href="/decorluxe/index.php" class="decoration-none">
                    <i class="material-icons">home</i>
                </a>
            </div>
        </div>

    </header>