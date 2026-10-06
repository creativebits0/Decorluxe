<?php
session_start();

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
require_once '../../data/admin/report_model.php';

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
<style>
    .employee-item.active {
        background: #ddd;
    }
</style>



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
<?php include_once "notification_header.php";?>

    <!-- Dynamic Content -->
    <main id="content">
        <div class="text-center text-secondary mt-4">
            <div class="container-fluid">
                <div class="row g-3">
                    <div class="table-wrapper container">
                        <div class="table-title">
                            <div class="mt-2 bg-dark d-flex justify-content-between p-3 rounded">
                                <h2 class="text-light fs-4 mb-0">Salary Management</h2>
                            </div>
                        </div>

                        <?php
                        $employees = getAllEmployees(); // create function
                        ?>

                        <div class="row mt-3">

                            <!-- LEFT SIDE -->
                            <div class="col-md-4">
                                <div class="card shadow-sm">
                                    <div class="card-header bg-dark text-white">
                                        Employees
                                    </div>

                                    <div class="card-body" style="max-height:500px;overflow:auto;">

                                        <?php foreach ($employees as $emp) { ?>

                                            <div class="p-2 border-bottom employee-item"
                                                style="cursor:pointer;"
                                                onclick="loadSalary(<?= $emp['id'] ?>, this)">

                                                <?= $emp['firstname'] ?>
                                            </div>

                                        <?php } ?>

                                    </div>
                                </div>
                            </div>

                            <!-- RIGHT SIDE -->
                            <div class="col-md-8">

                                <!-- Salary Setup -->
                                <div class="card shadow-sm mb-3">
                                    <div class="card-header bg-dark text-white">
                                        Salary Setup
                                    </div>
                                    <div class="card-body" id="salaryFormContainer">
                                        Select employee to manage salary
                                    </div>
                                </div>

                                <!-- Wage Entry -->
                                <div class="card shadow-sm">
                                    <div class="card-header bg-dark text-white">
                                        Add Wages
                                    </div>
                                    <div class="card-body" id="wageFormContainer">
                                        Select employee to add wages
                                    </div>

                                </div>

                            </div>

                        </div>
                    </div>
                    <div id="dynamic-content " class="mt-3">
                        <?php include_once '../../business/admin/wages_management_process.php'; ?>
                    </div>
                </div>
            </div>

        </div>


    </main>

    <div id="editWagesModal" class="modal">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content" style="background-color:#aa8c2c;">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="form-head">EDIT PROJECT</span>
                    <span class="close" onclick="closeModal('editWagesModal')">&times;</span>
                </div>
                <hr>
                <div id="editWagesContent"></div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer id="footer">© 2026 Creative Bits</footer>

    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script> -->
    <script src="/decorluxe/assets/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Global scripts (load once only) -->
    <script src="/decorluxe/assets/js/functionScript.js"></script>
    <script src="/decorluxe/assets/js/formValidation.js"></script>
    <script src="/decorluxe/assets/js/script.js"></script>
    <script>
        function loadSalary(user_id, el) {

            // highlight selected
            document.querySelectorAll(".employee-item").forEach(item => {
                item.classList.remove("active");
            });
            el.classList.add("active");

            // load salary form
            fetch("/decorluxe/presentation/admin/finance/get_salary.php?user_id=" + user_id)
                .then(res => res.text())
                .then(data => {
                    document.getElementById("salaryFormContainer").innerHTML = data;
                });

            // load wage form
            fetch("/decorluxe/presentation/admin/finance/get_wage_form.php?user_id=" + user_id)
                .then(res => res.text())
                .then(data => {
                    document.getElementById("wageFormContainer").innerHTML = data;
                });
        }
    </script>
    <script>
        document.getElementById("salaryForm").addEventListener("submit", function(e) {
            e.preventDefault();

            let formData = new FormData(this);

            fetch("/decorluxe/business/admin/finance/save_salary.php", {
                    method: "POST",
                    body: formData
                })
                .then(res => res.text())
                .then(msg => {
                    alert(msg);
                });
        });
    </script>


    <script>
        document.getElementById("wageForm").addEventListener("submit", function(e) {
            e.preventDefault();

            let formData = new FormData(this);

            fetch("/decorluxe/business/admin/finance/save_wage.php", {
                    method: "POST",
                    body: formData
                })
                .then(res => res.text())
                .then(msg => {
                    alert(msg);
                });
        });
    </script>
    <script>
        function editWage(id) {
            document.getElementById("editWagesModal").style.display = "block";

            fetch("/decorluxe/presentation/admin/finance/edit_wages.php?id=" + id)
                .then(res => res.text())
                .then(data => {
                    document.getElementById("editWagesContent").innerHTML = data;
                })
                .catch(() => {
                    document.getElementById("editWagesContent").innerHTML =
                        "<p class='text-danger text-center'>Failed to load</p>";
                });
        }
    </script>
    <script>
        //     function generateMonthly(user_id){
        //     fetch("/decorluxe/business/admin/finance/generate_monthly.php?user_id="+user_id)
        //     .then(res=>res.text())
        //     .then(msg=>{
        //         alert(msg);
        //     });
        // }

        function generateMonthly(user_id) {
            let btn = event.target;
            btn.disabled = true;

            fetch("/decorluxe/business/admin/finance/generate_monthly.php?user_id=" + user_id)
                .then(res => res.text())
                .then(msg => {
                    alert(msg);
                    btn.disabled = false;
                });
        }
    </script>