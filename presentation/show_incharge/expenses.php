<?php
session_start();
require_once '../../data/admin/report_model.php';

if (!isset($_SESSION['username']) || !isset($_SESSION['role'])) {
    header("Location: /decorluxe/presentation/login.php");
    exit();
}

if ($_SESSION['role'] !== 'show_incharge') {
    header("Location: /decorluxe/presentation/login.php");
    exit();
}
$user = $_SESSION['username'];

$userType = 'show_incharge';
$categories = getExpenseCategories();   // fetch all tabs
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="/decorluxe/assets/css/style.css" rel="stylesheet">
    <link href="/decorluxe/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/decorluxe/assets/css/material-icons.css" />


    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
    <style>

    </style>
</head>

<body>
    <div class="d-flex justify-content-center mt-3">
        <?php
        if (isset($_SESSION['success_message'])) {
            echo '<div id="success-message" class="alert alert-success text-center mt-5">' . htmlspecialchars($_SESSION['success_message']) . '</div>';
            unset($_SESSION['success_message']);
        } elseif (isset($_SESSION['alert_message'])) {
            echo '<div id="success-message" class="alert alert-danger text-center mt-5">' . htmlspecialchars($_SESSION['alert_message']) . '</div>';
            unset($_SESSION['alert_message']);
        }
        ?>
    </div> <!-- Sidebar -->
    <nav id="sidebar">
        <img src="/decorluxe/assets/images/logo-bg.png" class="sidebar-img ms-5 border-bottom" alt="logo" width="150px">
        <ul class="nav flex-column mt-3">
            <li class="nav-list"><a href="/decorluxe/presentation/show_incharge/default_dashboard.php" class="nav-link d-flex justify-content-start align-items-center gap-3 active"><i class="material-icons">dashboard</i>Dashboard</a></li>
            <!-- <li class="nav-list"><a href="/decorluxe/presentation/show_incharge/user_content.php" class="nav-link d-flex justify-content-start align-items-center gap-3"><i class="material-icons">person</i>Users</a></li> -->
            <!-- <li class="nav-list"><a href="/decorluxe/presentation/show_incharge/clients.php" class="nav-link d-flex justify-content-start align-items-center gap-3"><i class="material-icons">diversity_3</i>Clients</a></li> -->
            <li class="nav-list"><a href="/decorluxe/presentation/show_incharge/services.php" class="nav-link d-flex justify-content-start align-items-center gap-3"><i class="material-icons">tire_repair</i>Services</a></li>
            <li class="nav-list"><a href="/decorluxe/presentation/show_incharge/suppliers.php" class="nav-link d-flex justify-content-start align-items-center gap-3 "><i class="material-icons">diversity_1</i>Suppliers</a></li>
            <li class="sidebar-item dropdown-sidebar nav-list">
                <a href="#" class="nav-link sidebar-link dropdown-toggle sidebar-dropdown-btn d-flex justify-content-start align-items-center gap-3 w-100" id="itemDropdown" data-toggle="dropdown" aria-expanded="false">
                    <i class="material-icons">inventory</i>Inventory
                </a>
                <ul class="dropdown-menu sidebar-dropdown-menu " aria-labelledby="itemDropdown">
                    <li><a class="dropdown-item " href="/decorluxe/presentation/show_incharge/item_content.php">Item</a></li>
                    <li><a class="dropdown-item " href="/decorluxe/presentation/show_incharge/purchase_content.php">Purchase</a></li>
                    <li><a class="dropdown-item " href="/decorluxe/presentation/show_incharge/sales_content.php">Sales</a></li>
                    <li><a class="dropdown-item " href="/decorluxe/presentation/show_incharge/stock_content.php">Stock</a></li>
                </ul>
            </li>
            <li class="sidebar-item dropdown-sidebar nav-list">
                <a href="#" class="nav-link sidebar-link dropdown-toggle sidebar-dropdown-btn d-flex justify-content-start align-items-center gap-3 w-100" id="itemDropdown" data-toggle="dropdown" aria-expanded="false">
                    <i class="material-icons">paid</i>Finance
                </a>
                <ul class="dropdown-menu sidebar-dropdown-menu " aria-labelledby="itemDropdown">
                    <li><a class="dropdown-item " href="/decorluxe/presentation/show_incharge/expenses.php">Expenses</a></li>
                    <li><a class="dropdown-item " href="/decorluxe/presentation/show_incharge/wages.php">Wages</a></li>
                </ul>
            </li>

            <!-- <li class="nav-list"><a href="/decorluxe/presentation/show_incharge/appointment_content.php" class="nav-link d-flex justify-content-start align-items-center gap-3 "><i class="material-icons">calendar_month</i>Appointments</a></li> -->
            <!-- <li class="nav-list"><a href="/decorluxe/presentation/show_incharge/quotation.php" class="nav-link d-flex justify-content-start align-items-center gap-3"><i class="material-icons">request_quote</i>Quotation</a></li> -->
            <!-- <li class="nav-list"><a href="/decorluxe/presentation/show_incharge/projects.php" class="nav-link d-flex justify-content-start align-items-center gap-3"><i class="material-icons">design_services</i>Projects</a></li> -->

            <!-- <li><a href="#" class="nav-link" data-page="/decorluxe/presentation/show_incharge/reports_content.php">Reports</a></li> -->
            <li class="sidebar-item dropdown-sidebar nav-list">
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
    <?php include_once "notification_header.php"; ?>



    <main id="content">
        <div class="text-center text-secondary mt-4">
            <div class="container-fluid">
                <div class="container-fluid mt-3">
                    <div class="row g-3">

                        <!-- LEFT SIDE : Expense Categories -->
                        <div class="col-md-4">
                            <div class="card shadow-sm">
                                <div class="card-header bg-dark text-white d-flex justify-content-between">
                                    <span>Expense Categories</span>

                                    <button class="btn btn-sm btn-success"
                                        onclick="openAddExpense()">
                                        + Add
                                    </button>
                                </div>

                                <div class="card-body" style="max-height:500px;overflow:auto;">

                                    <?php foreach ($categories as $row) { ?>

                                        <div class="badge bg-warning text-dark p-2 m-1 category-tab"
                                            style="cursor:pointer;font-size:14px;"
                                            onclick="editExpenseCategory(<?= $row['id'] ?>)">
                                            <?= $row['expense_name'] ?>
                                        </div>

                                    <?php } ?>

                                </div>
                            </div>
                        </div>


                        <!-- RIGHT SIDE : Add Expense Amount -->
                        <div class="col-md-8">
                            <div class="card shadow-sm">
                                <div class="card-header bg-dark text-white">
                                    Add Expense Amount
                                </div>

                                <div class="card-body">

                                    <form action="../../business/admin/finance/save_expense_amount.php" method="POST">

                                        <div class="row g-3">

                                            <div class="col-md-6">
                                                <label>Expense Type</label>
                                                <select name="category_id" class="form-select" required>
                                                    <option value="">Select Expense</option>

                                                    <?php foreach ($categories as $row) { ?>
                                                        <option value="<?= $row['id'] ?>">
                                                            <?= $row['expense_name'] ?>
                                                        </option>
                                                    <?php } ?>

                                                </select>
                                            </div>

                                            <div class="col-md-6">
                                                <label>Amount</label>
                                                <input type="number" step="0.01"
                                                    name="amount"
                                                    class="form-control"
                                                    required>
                                            </div>

                                            <div class="col-md-6">
                                                <label>Date</label>
                                                <input type="date"
                                                    name="expense_date"
                                                    class="form-control"
                                                    value="<?= date('Y-m-d') ?>"
                                                    required>
                                            </div>

                                            <div class="col-md-6">
                                                <label>Remark</label>
                                                <input type="text"
                                                    name="remark"
                                                    class="form-control">
                                            </div>

                                            <div class="col-md-12">
                                                <button class="btn btn-success w-100">
                                                    Save Expense
                                                </button>
                                            </div>

                                        </div>

                                    </form>

                                </div>
                            </div>
                        </div>

                    </div>
                </div>



                <!-- Edit Modal -->
                <div class="modal" id="editExpenseModal">
                    <div class="modal-dialog modal-sm modal-dialog-centered">
                        <div class="modal-content p-3">
                            <div id="editExpenseContent"></div>
                        </div>
                    </div>
                </div>

                <!-- Dynamic Content -->
                <!-- <main id="content">
        <div class="text-center text-secondary mt-4">
            <div class="container-fluid">
                <div class="row g-3">
                    <div class="table-wrapper container">
                        <div class="table-title">
                            <div class="mt-2 bg-dark d-flex justify-content-between p-3 rounded">
                                <h2 class="text-light fs-4 mb-0">Expenses</h2>
                                <button class="btn btn-sm text-light d-flex justify-content-between align-items-center"
                                    style="background-color:#4CAF50;"
                                    onclick="openAddExpense()">
                                    <i class="material-icons">&#xE147;</i> Add Expense Category
                                </button>
                            </div>
                        </div>

                        <div class="row mb-3 mt-3">
                            <div class="col-md-10"></div>
                            <div class="col-md-2 d-flex justify-content-end">
                                <div class="input-group">
                                    <input type="search" id="live-search" class="form-control" placeholder="Search" onkeyup="liveSearch()">
                                    <button class="btn btn-secondary"><i class="material-icons">search</i></button>
                                </div>
                            </div>
                        </div>

                        <div id="dynamic-content">
                            <?php include_once '../../business/admin/expense_management_process.php'; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main> -->
                <div id="dynamic-content">
                    <?php include_once '../../business/admin/expense_management_process.php'; ?>
                </div>
                <!-- Modals (global, always available) -->
                <div id="addExpenseModal" class="modal">
                    <div class="modal-dialog modal-dialog-centered modal-sm">
                        <div class="modal-content p-3" style="background-color:#aa8c2c;">

                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h5 class="text-white m-0">Expense Type</h5>
                                <span class="close text-white fs-4"
                                    style="cursor:pointer;"
                                    onclick="closeModal('addExpenseModal')">&times;</span>
                            </div>

                            <form id="expenseForm">
                                <div id="expenseFields">
                                    <div class="mb-2">
                                        <input type="text" name="expense_name[]" class="form-control"
                                            placeholder="Enter Expense Type" required>
                                    </div>
                                </div>

                                <div class="d-flex gap-2 mb-3">
                                    <button type="button" class="btn btn-dark btn-sm" onclick="addMoreExpense()">
                                        + Add More
                                    </button>
                                </div>
                                <button type="submit" class="btn btn-success w-100">
                                    Save
                                </button>
                            </form>

                            <div id="msg" class="mt-2 text-center text-white"></div>

                        </div>
                    </div>
                </div>

                <div id="editExpenseAmountModal" class="modal">
                    <div class="modal-dialog modal-dialog-centered modal-md">
                        <div class="modal-content" style="background-color:#aa8c2c;">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="form-head">EDIT PROJECT</span>
                                <span class="close" onclick="closeModal('editExpenseAmountModal')">&times;</span>
                            </div>
                            <hr>
                            <div id="editExpenseAmountContent"></div>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <footer id="footer">© 2026 Creative Bits</footer>
                <script src="/decorluxe/assets/bootstrap/js/bootstrap.bundle.min.js"></script>

                <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script> -->
                <!-- Global scripts (load once only) -->
                <script src="/decorluxe/assets/js/functionScript.js"></script>
                <script src="/decorluxe/assets/js/script.js"></script>

                <script>
                    function openAddExpense() {
                        document.getElementById("addExpenseModal").style.display = "block";
                    }

                    function closeModal(id) {
                        document.getElementById(id).style.display = "none";
                    }

                    function addMoreExpense() {
                        let div = document.createElement("div");
                        div.className = "mb-2";
                        div.innerHTML = `
        <input type="text" name="expense_name[]" class="form-control"
        placeholder="Enter Expense Type" required>
    `;
                        document.getElementById("expenseFields").appendChild(div);
                    }

                    document.getElementById("expenseForm").addEventListener("submit", function(e) {
                        e.preventDefault();

                        let formData = new FormData(this);

                        fetch("/decorluxe/business/admin/finance/save_expense_category.php", {
                                method: "POST",
                                body: formData
                            })
                            .then(res => res.text())
                            .then(data => {
                                document.getElementById("msg").innerHTML = data;
                                document.getElementById("expenseForm").reset();
                            });
                    });
                </script>




                <script>
                    function editExpenseCategory(id) {
                        document.getElementById("editExpenseModal").style.display = 'block';

                        fetch("/decorluxe/presentation/admin/finance/edit_expense_category.php?id=" + id)
                            .then(res => res.text())
                            .then(data => {
                                document.getElementById("editExpenseContent").innerHTML = data;
                            });
                    }

                    function closeModal() {
                        document.getElementById("editExpenseModal").style.display = 'none';
                    }
                </script>

                <script>
                    function editExpenseAmount(id) {
                        document.getElementById("editExpenseAmountModal").style.display = "block";

                        fetch("/decorluxe/presentation/admin/finance/edit_expense_amount.php?id=" + id)
                            .then(res => res.text())
                            .then(data => {
                                document.getElementById("editExpenseAmountContent").innerHTML = data;
                            });
                    }

                    function closeModal() {
                        document.getElementById("editExpenseAmountModal").style.display = 'none';
                    }
                </script>