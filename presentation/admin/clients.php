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
    <title>Decorluxe Interiors | Clients</title>
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
            <li class="nav-list"><a href="/decorluxe/presentation/admin/clients.php" class="nav-link d-flex justify-content-start align-items-center gap-3 active"><i class="material-icons">diversity_3</i>Clients</a></li>
            <li class="nav-list"><a href="/decorluxe/presentation/admin/services.php" class="nav-link d-flex justify-content-start align-items-center gap-3"><i class="material-icons">tire_repair</i>Services</a></li>
            <li class="nav-list"><a href="/decorluxe/presentation/admin/suppliers.php" class="nav-link d-flex justify-content-start align-items-center gap-3 "><i class="material-icons">diversity_1</i>Suppliers</a></li>
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
                                <h2 class="text-light fs-4 mb-0">Client Management</h2>
                                <button class="btn btn-sm text-light d-flex justify-content-between align-items-center" style="background-color: #D4AF37;" onclick="openAddClient()">
                                    <i class="material-icons">&#xE147;</i> Add New Client
                                </button>
                            </div>
                        </div>

                        <div class="row mb-3 mt-3">
                            <div class="col-md-8"></div>
                            <div class="col-md-4 d-flex justify-content-end">
                                <div class="input-group">
                                    <!-- <input type="search" id="live-search" class="form-control" placeholder="Search" onkeyup="liveSearch()"> -->
                                    <input
                                        type="search"
                                        class="form-control"
                                        placeholder="Search Clients"
                                        onkeyup="universalLiveSearch(this)"
                                        data-process-url="/decorluxe/business/admin/client_management_process.php"
                                        data-target-div="dynamic-content">
                                    <button class="btn btn-secondary"><i class="material-icons">search</i></button>
                                </div>
                            </div>
                        </div>

                        <div id="dynamic-content">
                            <?php include_once '../../business/admin/client_management_process.php'; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Modals (global, always available) -->
    <div id="addClientModal" class="modal ">
        <div class="modal-dialog modal-dialog-centered modal-md ">
            <div class="modal-content" style="background-color:#aa8c2c;">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="form-head">ADD CLIENT</span>
                    <span class="close" onclick="closeModal('addClientModal')">&times;</span>
                </div>
                <hr>
                <div id="addClientContent"></div>
            </div>
        </div>
    </div>

    <div id="viewClientModal" class="modal">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content" style="background-color:#aa8c2c;">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="form-head">VIEW Client</span>
                    <span class="close" onclick="closeModal('viewClientModal')">&times;</span>
                </div>
                <hr>
                <div id="viewClientContent"></div>
            </div>
        </div>
    </div>

    <div id="editClientModal" class="modal">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content" style="background-color:#aa8c2c;">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="form-head">EDIT Client</span>
                    <span class="close" onclick="closeModal('editClientModal')">&times;</span>
                </div>
                <hr>
                <div id="editClientContent"></div>
            </div>
        </div>
    </div>



    <!-- Footer -->
    <!-- <footer id="footer">© 2026 Creative Bits</footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script src="/decorluxe/assets/js/functionScript.js"></script>
    <script src="/decorluxe/assets/js/formValidation.js"></script>
    <script src="/decorluxe/assets/js/global_search.js"></script>
    <script src="/decorluxe/assets/js/script.js"></script> -->
    <?php include_once __DIR__ . '/../../includes/footer.php'; ?>

    <script>
        // ✅ Open Add User Modal
        function openAddUser() {
            const modal = document.getElementById("addUserModal");
            const content = document.getElementById("addUserContent");
            modal.style.display = "block";
            content.innerHTML = "<p class='text-center'>Loading...</p>";

            fetch("/decorluxe/presentation/admin/users/add_user_form.php")
                .then(res => res.text())
                .then(html => content.innerHTML = html)
                .catch(() => content.innerHTML = "<p class='text-danger'>Failed to load form.</p>");
        }

        // ✅ Open Edit User Modal
        function editUser(id) {
            const modal = document.getElementById("editUserModal");
            const content = document.getElementById("editUserContent");
            modal.style.display = "block";
            content.innerHTML = "<p class='text-center'>Loading...</p>";

            fetch(`/decorluxe/presentation/admin/users/edit_user_form.php?id=${id}`)
                .then(res => res.text())
                .then(html => content.innerHTML = html)
                .catch(() => content.innerHTML = "<p class='text-danger'>Failed to load form.</p>");
        }

        // ✅ Open View User Modal
        function viewUser(id) {
            const modal = document.getElementById("viewUserModal");
            const content = document.getElementById("viewUserContent");
            modal.style.display = "block";
            content.innerHTML = "<p class='text-center'>Loading...</p>";

            fetch(`/decorluxe/presentation/admin/users/view_user_form.php?id=${id}`)
                .then(res => res.text())
                .then(html => content.innerHTML = html)
                .catch(() => content.innerHTML = "<p class='text-danger'>Failed to load data.</p>");
        }

        // ✅ Universal Close Function
        function closeModal(id) {
            document.getElementById(id).style.display = "none";
        }
    </script>

    <script>
        function addAddress() {

            const container = document.getElementById("addressContainer");

            const div = document.createElement("div");

            div.classList.add("d-flex", "gap-2", "mb-2");

            div.innerHTML = `

<input type="hidden" name="address_id[]" value="">

<input 
type="text"
name="addresses[]"
class="form-control"
placeholder="Enter Address"
required>

<button 
type="button"
class="btn btn-danger"
onclick="removeAddress(this)">
<i class="material-icons">&#x2212;</i>
</button>

`;

            container.appendChild(div);

        }


        function removeAddress(btn) {

            btn.parentElement.remove();

        }
    </script>
</body>

</html>