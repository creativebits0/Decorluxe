<?php
require_once '../../../data/admin/supplier_model.php';
require_once '../../../data/admin/inventory_model.php';

$items = getAllitems();
$suppliers = getAllSupplier();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Decorluxe Interiors | Purchase Report</title>
    <link rel="icon" type="image/x-icon" href="/decorluxe/assets/images/logo.jpeg">
    <link href="/decorluxe/assets/css/style.css" rel="stylesheet">
    <link href="/decorluxe/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/decorluxe/assets/css/material-icons.css" />

</head>
<style>
    .purchaseModal {
        width: 100%;
    }

    .itemField {
        background: white;
        border-left: 5px solid #aa8c2c;
    }
</style>

<body>
    <!-- Sidebar -->
    <nav id="sidebar">
        <img src="/decorluxe/assets/images/logo-bg.png" class="sidebar-img ms-5 border-bottom" alt="logo" width="150px">
        <ul class="nav flex-column mt-3">
            <li class="nav-list"><a href="/decorluxe/presentation/show_incharge/default_dashboard.php" class="nav-link d-flex justify-content-start align-items-center gap-3 "><i class="material-icons">dashboard</i>Dashboard</a></li>
            <!-- <li class="nav-list"><a href="/decorluxe/presentation/show_incharge/user_content.php" class="nav-link d-flex justify-content-start align-items-center gap-3"><i class="material-icons">person</i>Users</a></li> -->
            <!-- <li class="nav-list"><a href="/decorluxe/presentation/show_incharge/clients.php" class="nav-link d-flex justify-content-start align-items-center gap-3"><i class="material-icons">diversity_3</i>Clients</a></li> -->
            <!-- <li class="nav-list"><a href="/decorluxe/presentation/show_incharge/services.php" class="nav-link d-flex justify-content-start align-items-center gap-3"><i class="material-icons">tire_repair</i>Services</a></li> -->
            <li class="nav-list"><a href="/decorluxe/presentation/show_incharge/suppliers.php" class="nav-link d-flex justify-content-start align-items-center gap-3 "><i class="material-icons">diversity_1</i>Suppliers</a></li>
            <li class="sidebar-item dropdown-sidebar nav-list">
                <a href="#" class="nav-link sidebar-link dropdown-toggle sidebar-dropdown-btn d-flex justify-content-start align-items-center gap-3 w-100" id="itemDropdown" data-toggle="dropdown" aria-expanded="false">
                    <i class="material-icons">inventory</i>Inventory
                </a>
                <ul class="dropdown-menu sidebar-dropdown-menu " aria-labelledby="itemDropdown">
                    <li><a class="dropdown-item " href="/decorluxe/presentation/show_incharge/item_content.php">Item</a></li>
                    <li><a class="dropdown-item active " href="/decorluxe/presentation/show_incharge/purchase_content.php">Purchase</a></li>
                    <li><a class="dropdown-item " href="/decorluxe/presentation/show_incharge/sales_content.php">Sales</a></li>
                    <li><a class="dropdown-item " href="/decorluxe/presentation/show_incharge/stock_content.php">Stock</a></li>
                </ul>
            </li>
            <!-- <li class="sidebar-item dropdown-sidebar nav-list">
                <a href="#" class="nav-link sidebar-link dropdown-toggle sidebar-dropdown-btn d-flex justify-content-start align-items-center gap-3 w-100" id="itemDropdown" data-toggle="dropdown" aria-expanded="false">
                    <i class="material-icons">paid</i>Finance
                </a>
                <ul class="dropdown-menu sidebar-dropdown-menu " aria-labelledby="itemDropdown">
                    <li><a class="dropdown-item " href="/decorluxe/presentation/show_incharge/expenses.php">Expenses</a></li>
                    <li><a class="dropdown-item " href="/decorluxe/presentation/show_incharge/wages.php">Wages</a></li>
                </ul>
            </li> -->

            <!-- <li class="nav-list"><a href="/decorluxe/presentation/show_incharge/appointment_content.php" class="nav-link d-flex justify-content-start align-items-center gap-3 "><i class="material-icons">calendar_month</i>Appointments</a></li> -->
            <!-- <li class="nav-list"><a href="/decorluxe/presentation/show_incharge/quotation.php" class="nav-link d-flex justify-content-start align-items-center gap-3"><i class="material-icons">request_quote</i>Quotation</a></li> -->
            <!-- <li class="nav-list"><a href="/decorluxe/presentation/show_incharge/projects.php" class="nav-link d-flex justify-content-start align-items-center gap-3"><i class="material-icons">design_services</i>Projects</a></li> -->

            <!-- <li><a href="#" class="nav-link" data-page="/decorluxe/presentation/show_incharge/reports_content.php">Reports</a></li> -->
            <li class="sidebar-item dropdown-sidebar nav-list border-bottom mb-5 pb-3">
                <a href="#" class="nav-link sidebar-link dropdown-toggle sidebar-dropdown-btn d-flex justify-content-start align-items-center gap-3 w-100" id="reportDropdown" data-toggle="dropdown" aria-expanded="false">
                    <i class="material-icons">summarize</i>Reports
                </a>
                <ul class="dropdown-menu sidebar-dropdown-menu" aria-labelledby="reportDropdown">
                    <!-- <li><a class="dropdown-item" href="/decorluxe/presentation/show_incharge/reports/booking_report.php">Booking Report</a></li> -->
                    <li><a class="dropdown-item" href="/decorluxe/presentation/show_incharge/reports/sales_report.php">Sales Report</a></li>
                    <li><a class="dropdown-item " href="/decorluxe/presentation/show_incharge/reports/purchase_report.php">Purchase Report</a></li>
                </ul>
            </li>
            <!-- <li class="nav-list"><a href="/decorluxe/presentation/show_incharge/settings.php" class="nav-link d-flex justify-content-start align-items-center gap-3 "><i class="material-icons">settings</i>Settings</a></li> -->
            <li class="nav-list mt-5"><a href="/decorluxe/business/logout_process.php" class="nav-link d-flex justify-content-start align-items-center gap-3 text-danger"><i class="material-icons">logout</i>Logout</a></li>
        </ul>

    </nav>

    <!-- Topbar -->
    <?php include_once "../notification_header.php"; ?>


    <!-- Dynamic Content -->
    <main id="content">
        <div class="text-center text-secondary mt-4">
            <div class="container-fluid">
                <div class="table-wrapper container">
                    <div class="table-title">
                        <div class="mt-2 bg-dark d-flex justify-content-between p-3 rounded">
                            <h2 class="text-light fs-4 mb-0">Purchase Report</h2>
                        </div>
                    </div>

                    <div class="card-body m-2">

                        <form method="GET" action="purchase_report.php">
                            <div class="row g-3">

                                <!-- Report Type -->
                                <div class="col-md-3">
                                    <select name="report_type" id="reportType" class="form-select">
                                        <option value="">All</option>

                                        <option value="daily"
                                            <?= ($_GET['report_type'] ?? '') == 'daily' ? 'selected' : ''; ?>>
                                            Daily
                                        </option>

                                        <option value="monthly"
                                            <?= ($_GET['report_type'] ?? '') == 'monthly' ? 'selected' : ''; ?>>
                                            Monthly
                                        </option>

                                        <option value="yearly"
                                            <?= ($_GET['report_type'] ?? '') == 'yearly' ? 'selected' : ''; ?>>
                                            Yearly
                                        </option>
                                    </select>
                                </div>

                                <!-- Daily -->
                                <div class="col-md-3 d-none" id="dailyField">
                                    <input type="date"
                                        name="selected_date"
                                        class="form-control"
                                        value="<?= $_GET['selected_date'] ?? ''; ?>">
                                </div>

                                <!-- Monthly -->
                                <div class="col-md-3 d-none" id="monthField">
                                    <input type="month"
                                        name="selected_month"
                                        class="form-control"
                                        value="<?= $_GET['selected_month'] ?? ''; ?>">
                                </div>

                                <!-- Yearly -->
                                <div class="col-md-3 d-none" id="yearField">
                                    <select name="selected_year" class="form-select">

                                        <option value="">Select Year</option>

                                        <?php
                                        $current = date('Y');

                                        for ($i = $current; $i >= $current - 5; $i--) {
                                        ?>
                                            <option value="<?= $i; ?>"
                                                <?= ($_GET['selected_year'] ?? '') == $i ? 'selected' : ''; ?>>
                                                <?= $i; ?>
                                            </option>
                                        <?php } ?>

                                    </select>
                                </div>

                                <!-- Item -->
                                <div class="col-md-3">

                                    <select name="item_id" class="form-select">
                                        <option value="">All Items</option>

                                        <?php foreach ($items as $item) { ?>

                                            <option value="<?= $item['item_id']; ?>"
                                                <?= ($_GET['item_id'] ?? '') == $item['item_id'] ? 'selected' : ''; ?>>

                                                <?= $item['itemName']; ?>

                                            </option>

                                        <?php } ?>
                                    </select>

                                </div>

                                <!-- Supplier -->
                                <div class="col-md-3">

                                    <select name="supplier_id" class="form-select">

                                        <option value="">All Suppliers</option>

                                        <?php foreach ($suppliers as $sup) { ?>

                                            <option value="<?= $sup['supplier_id']; ?>"
                                                <?= ($_GET['supplier_id'] ?? '') == $sup['supplier_id'] ? 'selected' : ''; ?>>

                                                <?= $sup['supplier_name']; ?>

                                            </option>

                                        <?php } ?>

                                    </select>

                                </div>

                                <!-- Button -->
                                <div class="row mb-2">
                                    <div class="d-flex justify-content-center align-items-center gap-2 mt-2">
                                        <button type="submit"
                                            class="btn btn-success d-flex justify-content-center align-items-center gap-2">

                                            <i class="material-icons">search</i>
                                            Generate Report

                                        </button>
                                        <!-- PDF -->
                                        <button type="submit"
                                            formaction="../../../business/admin/generatePdf/purchase_report_pdf.php"
                                            formtarget="_blank"
                                            class="btn btn-danger d-flex justify-content-center align-items-center gap-2">

                                            <i class="material-icons">picture_as_pdf</i>
                                            Generate PDF

                                        </button>
                                    </div>
                                </div>

                            </div>

                        </form>
                    </div>
                    <hr>


                    <div id="dynamic-content">
                        <?php include_once '../../../business/admin/reports/purchase_report_process.php'; ?>
                    </div>
                </div>
            </div>
        </div>
    </main>


    <!-- Footer -->
    <footer id="footer">© 2026 Creative Bits</footer>
    <script src="/decorluxe/assets/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script> -->
    <!-- Global scripts (load once only) -->
    <script src="/decorluxe/assets/js/functionScript.js"></script>
    <script src="/decorluxe/assets/js/quotation.js"></script>
    <script src="/decorluxe/assets/js/script.js"></script>

    <script>
        const reportType = document.getElementById('reportType');

        function togglePurchaseFilters() {

            // Hide all first
            document.getElementById('dailyField').classList.add('d-none');
            document.getElementById('monthField').classList.add('d-none');
            document.getElementById('yearField').classList.add('d-none');

            // Show according to report type
            if (reportType.value === 'daily') {
                document.getElementById('dailyField').classList.remove('d-none');
            }

            if (reportType.value === 'monthly') {
                document.getElementById('monthField').classList.remove('d-none');
            }

            if (reportType.value === 'yearly') {
                document.getElementById('yearField').classList.remove('d-none');
            }
        }

        reportType.addEventListener('change', togglePurchaseFilters);

        // Run on page load
        togglePurchaseFilters();
    </script>

</body>

</html>