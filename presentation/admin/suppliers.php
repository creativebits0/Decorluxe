<?php
session_start();
if (!isset($_SESSION['username']) || !isset($_SESSION['role'])) {
    header("Location: /decorluxe/presentation/login.php");
    exit();
}

$userType = 'admin';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Decorluxe Interiors | Supplier</title>
    <link rel="icon" type="image/x-icon" href="/decorluxe/assets/images/logo.jpeg">
    <link href="/decorluxe/assets/css/style.css" rel="stylesheet">
    <link href="/decorluxe/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/decorluxe/assets/css/material-icons.css" />

</head>

<body>
    <div class="d-flex justify-content-center mt-3">
        <?php
        if (isset($_SESSION['success_message'])) {
            echo '<div  id="success-message" class="alert alert-success text-center mt-5">' . htmlspecialchars($_SESSION['success_message']) . '</div>';
            unset($_SESSION['success_message']);
        } elseif (isset($_SESSION['alert_message'])) {
            echo '<div  id="success-message" class="alert alert-danger text-center mt-5">' . htmlspecialchars($_SESSION['alert_message']) . '</div>';
            unset($_SESSION['alert_message']);
        }
        ?>
    </div>
    <!-- Sidebar -->
    <nav id="sidebar">
        <img src="/decorluxe/assets/images/logo-bg.png" class="sidebar-img ms-5 border-bottom" alt="logo" width="150px">
        <ul class="nav flex-column mt-3">
            <li class="nav-list"><a href="/decorluxe/presentation/admin/default_dashboard.php" class="nav-link d-flex justify-content-start align-items-center gap-3"><i class="material-icons">dashboard</i>Dashboard</a></li>
            <li class="nav-list"><a href="/decorluxe/presentation/admin/user_content.php" class="nav-link d-flex justify-content-start align-items-center gap-3"><i class="material-icons">person</i>Users</a></li>
            <li class="nav-list"><a href="/decorluxe/presentation/admin/clients.php" class="nav-link d-flex justify-content-start align-items-center gap-3"><i class="material-icons">diversity_3</i>Clients</a></li>
            <li class="nav-list"><a href="/decorluxe/presentation/admin/services.php" class="nav-link d-flex justify-content-start align-items-center gap-3"><i class="material-icons">tire_repair</i>Services</a></li>
            <li class="nav-list"><a href="/decorluxe/presentation/admin/suppliers.php" class="nav-link d-flex justify-content-start align-items-center gap-3 active"><i class="material-icons">diversity_1</i>Suppliers</a></li>
            <li class="sidebar-item dropdown-sidebar nav-list">
                <a href="#" class="nav-link sidebar-link dropdown-toggle sidebar-dropdown-btn d-flex justify-content-start align-items-center gap-3 w-100" id="itemDropdown" data-toggle="dropdown" aria-expanded="false">
                    <i class="material-icons">inventory</i>Inventory
                </a>
                <ul class="dropdown-menu sidebar-dropdown-menu " aria-labelledby="itemDropdown">
                    <li><a class="dropdown-item " href="/decorluxe/presentation/admin/item_content.php">Item</a></li>
                    <li><a class="dropdown-item " href="/decorluxe/presentation/admin/purchase_content.php">Purchase</a></li>
                    <li><a class="dropdown-item " href="/decorluxe/presentation/admin/sales_content.php">Sales</a></li>
                    <li><a class="dropdown-item " href="/decorluxe/presentation/admin/stock_content.php">Stock</a></li>
                </ul>
            </li>
            <li class="sidebar-item dropdown-sidebar nav-list">
                <a href="#" class="nav-link sidebar-link dropdown-toggle sidebar-dropdown-btn d-flex justify-content-start align-items-center gap-3 w-100" id="itemDropdown" data-toggle="dropdown" aria-expanded="false">
                    <i class="material-icons">paid</i>Finance
                </a>
                <ul class="dropdown-menu sidebar-dropdown-menu " aria-labelledby="itemDropdown">
                    <li><a class="dropdown-item " href="/decorluxe/presentation/admin/expenses.php">Expenses</a></li>
                    <li><a class="dropdown-item " href="/decorluxe/presentation/admin/wages.php">Wages</a></li>
                </ul>
            </li>

            <li class="nav-list"><a href="/decorluxe/presentation/admin/appointment_content.php" class="nav-link d-flex justify-content-start align-items-center gap-3 "><i class="material-icons">calendar_month</i>Appointments</a></li>
            <li class="nav-list"><a href="/decorluxe/presentation/admin/quotation.php" class="nav-link d-flex justify-content-start align-items-center gap-3"><i class="material-icons">request_quote</i>Quotation</a></li>
            <li class="nav-list"><a href="/decorluxe/presentation/admin/projects.php" class="nav-link d-flex justify-content-start align-items-center gap-3 "><i class="material-icons">design_services</i>Projects</a></li>

            <!-- <li><a href="#" class="nav-link" data-page="/decorluxe/presentation/admin/reports_content.php">Reports</a></li> -->
            <li class="sidebar-item dropdown-sidebar nav-list">
                <a href="#" class="nav-link sidebar-link dropdown-toggle sidebar-dropdown-btn d-flex justify-content-start align-items-center gap-3 w-100" id="reportDropdown" data-toggle="dropdown" aria-expanded="false">
                    <i class="material-icons">summarize</i>Reports
                </a>
                <ul class="dropdown-menu sidebar-dropdown-menu" aria-labelledby="reportDropdown">
                    <li><a class="dropdown-item" href="/decorluxe/presentation/admin/reports/booking_report.php">Booking Report</a></li>
                    <li><a class="dropdown-item" href="/decorluxe/presentation/admin/reports/sales_report.php">Sales Report</a></li>
                    <li><a class="dropdown-item " href="/decorluxe/presentation/admin/reports/purchase_report.php">Purchase Report</a></li>
                    <li><a class="dropdown-item " href="/decorluxe/presentation/admin/reports/profit_report.php">Profit Statement</a></li>
                </ul>
            </li>
            <li class="nav-list"><a href="/decorluxe/presentation/admin/settings.php" class="nav-link d-flex justify-content-start align-items-center gap-3 "><i class="material-icons">settings</i>Settings</a></li>
            <li class="nav-list mt-5"><a href="/decorluxe/business/logout_process.php" class="nav-link d-flex justify-content-start align-items-center gap-3 text-danger"><i class="material-icons">logout</i>Logout</a></li>
        </ul>

    </nav>
    <!-- Topbar -->
    <?php include_once "notification_header.php" ?>


    <!-- Dynamic Content -->
    <main id="content">
        <div class="text-center text-secondary">
            <div class="container-fluid">
                <div class="row g-3">
                    <div class="table-wrapper container">
                        <div class="table-title">
                            <div class="bg-dark d-flex justify-content-between p-3 rounded">
                                <h2 class="text-light fs-4 mb-0">Supplier Management</h2>
                                <button class="btn btn-sm text-light d-flex justify-content-between align-items-center" style="background-color: #D4AF37;" onclick="openAddSupplier()">
                                    <i class="material-icons">&#xE147;</i> Add New Supplier
                                </button>
                            </div>
                        </div>

                        <div class="row mb-3 mt-3">
                            <div class="col-md-8"></div>
                            <div class="col-md-4 d-flex justify-content-end">
                                <div class="input-group">
                                    <input
                                        type="search"
                                        class="form-control"
                                        placeholder="Search Items"
                                        onkeyup="universalLiveSearch(this)"
                                        data-process-url="/decorluxe/business/admin/supplier_management_process.php"
                                        data-target-div="dynamic-content"> <button class="btn btn-secondary"><i class="material-icons">search</i></button>
                                </div>
                            </div>
                        </div>

                        <div id="dynamic-content">
                            <?php include_once '../../business/admin/supplier_management_process.php'; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Modals (global, always available) -->
    <div id="addSupplierModal" class="modal ">
        <div class="modal-dialog modal-dialog-centered modal-md ">
            <div class="modal-content" style="background-color:#aa8c2c;">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="form-head">ADD SUPPLIER</span>
                    <span class="close" onclick="closeModal('addSupplierModal')">&times;</span>
                </div>
                <hr>
                <div id="addSupplierContent"></div>
            </div>
        </div>
    </div>

    <div id="viewSupplierModal" class="modal">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content" style="background-color:#aa8c2c;">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="form-head">VIEW SUPPLIER</span>
                    <span class="close" onclick="closeModal('viewSupplierModal')">&times;</span>
                </div>
                <hr>
                <div id="viewSupplierContent"></div>
            </div>
        </div>
    </div>

    <div id="editSupplierModal" class="modal">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content" style="background-color:#aa8c2c;">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="form-head">EDIT SUPPLIER</span>
                    <span class="close" onclick="closeModal('editSupplierModal')">&times;</span>
                </div>
                <hr>
                <div id="editSupplierContent"></div>
            </div>
        </div>
    </div>



    <!-- Footer -->
    <?php include_once __DIR__ . '/../../includes/footer.php'; ?>



    <script>
        function initSupplierForm() {

            // Load items
            fetch('/decorluxe/business/admin/inventory/get_items.php')
                .then(res => res.json())
                .then(data => {
                    const select = document.getElementById('item');
                    select.innerHTML = '<option value="">Select Items</option>';
                    data.forEach(item => {
                        const opt = document.createElement('option');
                        opt.value = item.item_id; //
                        opt.textContent = item.itemCode + "|  " + item.itemName; // 
                        select.appendChild(opt);
                    });
                });
        }
        //  Open Add Supplier Modal
        function openAddSupplier() {
            const modal = document.getElementById("addSupplierModal");
            const content = document.getElementById("addSupplierContent");
            modal.style.display = "block";
            content.innerHTML = "<p class='text-center'>Loading...</p>";

            fetch("/decorluxe/presentation/admin/suppliers/add_supplier_form.php")
                .then(res => res.text())
                .then(html => {
                    content.innerHTML = html;

                    initSupplierForm();

                });
        }

        //  Open Edit Supplier Modal
        function editSupplier(id) {
            const modal = document.getElementById("editSupplierModal");
            const content = document.getElementById("editSupplierContent");
            modal.style.display = "block";
            content.innerHTML = "<p class='text-center'>Loading...</p>";

            fetch(`/decorluxe/presentation/admin/suppliers/edit_supplier_form.php?id=${id}`)
                .then(res => res.text())
                .then(html => {
                    content.innerHTML = html;
                    initSupplierForm();
                })
                .catch(() => content.innerHTML = "<p class='text-danger'>Failed to load form.</p>");
        }

        //  Open View Supplier Modal
        function viewSupplier(id) {
            const modal = document.getElementById("viewSupplierModal");
            const content = document.getElementById("viewSupplierContent");
            modal.style.display = "block";
            content.innerHTML = "<p class='text-center'>Loading...</p>";

            fetch(`/decorluxe/presentation/admin/suppliers/view_supplier_form.php?id=${id}`)
                .then(res => res.text())
                .then(html => content.innerHTML = html)
                .catch(() => content.innerHTML = "<p class='text-danger'>Failed to load data.</p>");
        }

        //  Universal Close Function
        function closeModal(id) {
            document.getElementById(id).style.display = "none";
        }
    </script>
</body>

</html>