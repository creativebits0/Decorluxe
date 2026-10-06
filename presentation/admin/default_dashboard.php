<?php

session_start();
require_once '../../data/admin/dashboard_model.php';


if (!isset($_SESSION['username']) || !isset($_SESSION['role'])) {
    header("Location: /decorluxe/presentation/login.php");
    exit();
}

if ($_SESSION['role'] !== 'admin') {
    header("Location: /decorluxe/presentation/login.php");
    exit();
}
$role = $_SESSION['role'];
$user = $_SESSION['username'];


$totalBookings = getTotalBookings();

$completedBookings = getCompletedBookings();

$pendingBookings = getPendingBookings();

$totalProjects = getTotalProjects();

$completedProjects = getCompletedProjects();

$pendingProjects = getPendingProjects();

$todayResult = getTodayAppointments();

$upcomingResult = getUpcomingAppointments();


$salesChart = getSalesChartData();

$salesLabels = $salesChart['labels'];
$salesData   = $salesChart['data'];


$expenseChart = getExpenseChartData();

$expenseLabels = $expenseChart['labels'];
$expenseData   = $expenseChart['data'];


$purchaseChart = getPurchaseChartData();

$purchaseLabels = $purchaseChart['labels'];
$purchaseData   = $purchaseChart['data'];
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8" />

    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Decorluxe Interiors | Dashboard</title>
    <link rel="icon" type="image/x-icon" href="/decorluxe/assets/images/logo.jpeg">
    <link href="/decorluxe/assets/css/style.css" rel="stylesheet">
    <link href="/decorluxe/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/decorluxe/assets/css/material-icons.css" />
    <script src="/decorluxe/assets/fullCalendar/index.global.min.js"></script>

    <!-- <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.css" rel="stylesheet"> -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script> -->

    <style>
        #content {
            background-color: #fefefe;
        }

        .card-shadow {
            box-shadow: rgba(60, 64, 67, 0.3) 0px 1px 2px 0px, rgba(210, 103, 2, 0.15) 0px 2px 6px 2px;

        }

        .dashboard-card {
            border-radius: 15px;
            transition: 0.3s;
        }

        .dashboard-card:hover {
            transform: translateY(-3px);
        }

        .card-header {
            font-weight: 600;
        }

        .fc .fc-toolbar-title {
            font-size: 22px;
            font-weight: bold;
        }

        .fc-daygrid-day-number {
            text-decoration: none;
            color: #333;
        }

        .appointment-box {
            border-bottom: 1px solid #eee;
            padding: 10px 0;
        }

        .appointment-box:last-child {
            border-bottom: none;
        }
    </style>

</head>

<body>

    <!-- SUCCESS MESSAGE -->
    <div class="d-flex justify-content-center mt-3">

        <?php
        if (isset($_SESSION['success_message'])) {

            echo '<div id="success-message"
                class="alert alert-success text-center mt-5">'
                . htmlspecialchars($_SESSION['success_message']) .
                '</div>';

            unset($_SESSION['success_message']);
        } elseif (isset($_SESSION['alert_message'])) {

            echo '<div id="success-message"
                class="alert alert-danger text-center mt-5">'
                . htmlspecialchars($_SESSION['alert_message']) .
                '</div>';

            unset($_SESSION['alert_message']);
        }
        ?>

    </div>

    <!-- SIDEBAR -->
    <nav id="sidebar">
        <img src="/decorluxe/assets/images/logo-bg.png" class="sidebar-img ms-5 border-bottom" alt="logo" width="150px">
        <ul class="nav flex-column mt-3">
            <li class="nav-list"><a href="/decorluxe/presentation/admin/default_dashboard.php" class="nav-link d-flex justify-content-start align-items-center gap-3 active"><i class="material-icons">dashboard</i>Dashboard</a></li>
            <li class="nav-list"><a href="/decorluxe/presentation/admin/user_content.php" class="nav-link d-flex justify-content-start align-items-center gap-3"><i class="material-icons">person</i>Users</a></li>
            <li class="nav-list"><a href="/decorluxe/presentation/admin/clients.php" class="nav-link d-flex justify-content-start align-items-center gap-3"><i class="material-icons">diversity_3</i>Clients</a></li>
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



    <!-- MAIN CONTENT -->
    <main id="content">

        <div class="container-fluid mt-4">

            <!-- TOP CARDS -->
            <div class="row g-4">

                <div class="col-md-2">

                    <div class="card dashboard-card card-shadow border-0">

                        <div class="card-body text-center">

                            <h6>Total Bookings</h6>

                            <h2 class="fw-bold text-primary">
                                <?= $totalBookings ?>
                            </h2>

                        </div>

                    </div>

                </div>

                <div class="col-md-2">

                    <div class="card dashboard-card card-shadow border-0">

                        <div class="card-body text-center">

                            <h6>Booking Completed</h6>

                            <h2 class="fw-bold text-success">
                                <?= $completedBookings ?>
                            </h2>

                        </div>

                    </div>

                </div>

                <div class="col-md-2">

                    <div class="card dashboard-card card-shadow border-0">

                        <div class="card-body text-center">

                            <h6>Booking Pending</h6>

                            <h2 class="fw-bold text-warning">
                                <?= $pendingBookings ?>
                            </h2>

                        </div>

                    </div>

                </div>

                <!-- </div> -->
                <!-- <div class="row g-4 mt-2"> -->

                <div class="col-md-2">

                    <div class="card dashboard-card card-shadow border-0">

                        <div class="card-body text-center">

                            <h6>Total Projects</h6>

                            <h2 class="fw-bold text-primary">
                                <?= $totalProjects ?>
                            </h2>

                        </div>

                    </div>

                </div>

                <div class="col-md-2">

                    <div class="card dashboard-card card-shadow border-0">

                        <div class="card-body text-center">

                            <h6>Completed</h6>

                            <h2 class="fw-bold text-success">
                                <?= $completedProjects ?>
                            </h2>

                        </div>

                    </div>

                </div>

                <div class="col-md-2">

                    <div class="card dashboard-card card-shadow border-0">

                        <div class="card-body text-center">

                            <h6>Pending</h6>

                            <h2 class="fw-bold text-warning">
                                <?= $pendingProjects ?>
                            </h2>

                        </div>

                    </div>

                </div>

            </div>


            <!-- CALENDAR + APPOINTMENTS -->
            <div class="row mt-4 g-4">

                <!-- CALENDAR -->
                <div class="col-lg-8">

                    <div class="card card-shadow border-0 h-100">

                        <div class="card-header bg-dark text-white">
                            Appointment Calendar
                        </div>

                        <div class="card-body bg-light">

                            <div id="calendar"></div>

                        </div>

                    </div>

                </div>


                <!-- APPOINTMENTS -->
                <div class="col-lg-4">

                    <!-- TODAY -->
                    <div class="card card-shadow border-0 mb-4">

                        <div class="card-header bg-primary text-white">
                            Today's Appointments
                        </div>

                        <div class="card-body"
                            style="max-height:300px; overflow:auto;">

                            <?php if (mysqli_num_rows($todayResult) > 0): ?>

                                <?php while ($row = mysqli_fetch_assoc($todayResult)): ?>

                                    <div class="appointment-box text-start">

                                        <div class="fw-bold">
                                            <?= $row['fullname'] ?>
                                        </div>

                                        <small class="text-muted">
                                            <?= $row['appointment_type'] ?>
                                        </small>

                                        <div class="small text-success">

                                            <?= date(
                                                'h:i A',
                                                strtotime($row['appointment_date'])
                                            ) ?>

                                        </div>

                                    </div>

                                <?php endwhile; ?>

                            <?php else: ?>

                                <div class="text-center text-muted">
                                    No appointments today
                                </div>

                            <?php endif; ?>

                        </div>

                    </div>


                    <!-- UPCOMING -->
                    <div class="card card-shadow border-0">

                        <div class="card-header bg-success text-white">
                            Upcoming Appointments
                        </div>

                        <div class="card-body"
                            style="max-height:300px; overflow:auto;">

                            <?php if (mysqli_num_rows($upcomingResult) > 0): ?>

                                <?php while ($row = mysqli_fetch_assoc($upcomingResult)): ?>

                                    <div class="appointment-box text-start">

                                        <div class="fw-bold">
                                            <?= $row['fullname'] ?>
                                        </div>

                                        <small class="text-muted">
                                            <?= $row['appointment_type'] ?>
                                        </small>

                                        <div class="small text-primary">

                                            <?= date(
                                                'd M Y h:i A',
                                                strtotime($row['appointment_date'])
                                            ) ?>

                                        </div>

                                    </div>

                                <?php endwhile; ?>

                            <?php else: ?>

                                <div class="text-center text-muted">
                                    No upcoming appointments
                                </div>

                            <?php endif; ?>

                        </div>

                    </div>

                </div>

            </div>


            <!-- CHARTS -->
            <div class="row mt-4 g-4">

                <!-- SALES -->
                <div class="col-lg-4">

                    <div class="card card-shadow border-0">

                        <div class="card-header bg-dark text-white">
                            Most Sales
                        </div>
                        <div class="card-body">

                            <div class="mb-3">
                                <select id="salesFilter" class="form-select">
                                    <option value="overall">Overall</option>
                                    <option value="monthly">Monthly</option>
                                    <option value="today">Today</option>
                                </select>
                            </div>

                            <canvas id="salesChart"></canvas>

                        </div>

                    </div>

                </div>


                <!-- EXPENSE -->
                <div class="col-lg-4">

                    <div class="card card-shadow border-0">

                        <div class="card-header bg-danger text-white">
                            Top Expenses
                        </div>
                        <div class="card-body">

                            <div class="mb-3">
                                <select id="expenseFilter" class="form-select">
                                    <option value="overall">Overall</option>
                                    <option value="monthly">Monthly</option>
                                    <option value="today">Today</option>
                                </select>
                            </div>

                            <canvas id="expenseChart"></canvas>

                        </div>

                    </div>

                </div>


                <!-- PURCHASE -->
                <div class="col-lg-4">

                    <div class="card card-shadow border-0">

                        <div class="card-header bg-success text-white">
                            Most Purchases
                        </div>
                        <div class="card-body">

                            <div class="mb-3">
                                <select id="purchaseFilter" class="form-select">
                                    <option value="overall">Overall</option>
                                    <option value="monthly">Monthly</option>
                                    <option value="today">Today</option>
                                </select>
                            </div>

                            <canvas id="purchaseChart"></canvas>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </main>
    <!-- View Appointments -->
    <div id="viewAppointmentModal" class="modal">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content" style="background-color:#aa8c2c;">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="form-head">VIEW APPOINTMENT</span>
                    <span class="close" onclick="closeModal('viewAppointmentModal')">&times;</span>
                </div>
                <hr>
                <div id="viewAppointmentContent"></div>
            </div>
        </div>
    </div>
    <!-- <div id="editUserModal" class="modal">
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
    </div> -->
    <!-- FOOTER -->
    <footer id="footer">
        © 2026 Creative Bits
    </footer>


    <!-- SCRIPTS -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script> -->
    <script src="/decorluxe/assets/bootstrap/js/bootstrap.bundle.min.js"></script>

    <script src="/decorluxe/assets/js/chart.min.js"></script>

    <script src="/decorluxe/assets/js/script.js"></script>

    <script src="/decorluxe/assets/js/functionScript.js"></script>

    <!-- edit relevant user -->
    <!-- <script>
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
    </script> -->
    <script>
        // Open View Appointment Modal
        function viewAppointment(id) {
            const modal = document.getElementById("viewAppointmentModal");
            const content = document.getElementById("viewAppointmentContent");
            modal.style.display = "block";
            content.innerHTML = "<p class='text-center'>Loading...</p>";

            fetch(`/decorluxe/presentation/admin/appointments/view_appointment_form.php?id=${id}`)
                .then(res => res.text())
                .then(html => content.innerHTML = html)
                .catch(() => content.innerHTML = "<p class='text-danger'>Failed to load data.</p>");
        }

        // Universal Close Function
        function closeModal(id) {
            document.getElementById(id).style.display = "none";
        }
    </script>
    <!-- CALENDAR -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            var calendarEl =
                document.getElementById('calendar');

            var calendar = new FullCalendar.Calendar(
                calendarEl, {

                    initialView: 'dayGridMonth',

                    height: 650,

                    events: '/decorluxe/business/admin/appointments/get_calendar_events.php',

                    eventClick: function(info) {

                        viewAppointment(info.event.id);

                    }

                });

            calendar.render();

        });
    </script>

    <!-- CHARTS -->
    <script>
        const chartColors = [
            '#0d6efd',
            '#198754',
            '#dc3545',
            '#ffc107',
            '#6f42c1',
            '#fd7e14',
            '#20c997',
            '#6610f2'
        ];


        /*
        -----------------------------------------
        CREATE CHARTS
        -----------------------------------------
        */

        const salesChart = new Chart(document.getElementById('salesChart'), {
            type: 'pie',
            data: {
                labels: <?= json_encode($salesLabels) ?>,
                datasets: [{
                    data: <?= json_encode($salesData) ?>,
                    backgroundColor: chartColors
                }]
            }
        });

        const expenseChart = new Chart(document.getElementById('expenseChart'), {
            type: 'bar',
            data: {
                labels: <?= json_encode($expenseLabels) ?>,
                datasets: [{
                    data: <?= json_encode($expenseData) ?>,
                    backgroundColor: chartColors
                }]
            }
        });

        const purchaseChart = new Chart(document.getElementById('purchaseChart'), {
            type: 'doughnut',
            data: {
                labels: <?= json_encode($purchaseLabels) ?>,
                datasets: [{
                    data: <?= json_encode($purchaseData) ?>,
                    backgroundColor: chartColors
                }]
            }
        });


        /*
        -----------------------------------------
        UPDATE CHART FUNCTION
        -----------------------------------------
        */

        function updateChart(chart, type, filter) {
            fetch(`/decorluxe/business/admin/dashboard/get_chart_data.php?type=${type}&filter=${filter}`)

                .then(res => res.json())

                .then(data => {

                    chart.data.labels = data.labels;

                    chart.data.datasets[0].data = data.data;

                    chart.update();
                });
        }


        /*
        -----------------------------------------
        FILTER EVENTS
        -----------------------------------------
        */

        document.getElementById('salesFilter')
            .addEventListener('change', function() {

                updateChart(salesChart, 'sales', this.value);

            });


        document.getElementById('expenseFilter')
            .addEventListener('change', function() {

                updateChart(expenseChart, 'expense', this.value);

            });


        document.getElementById('purchaseFilter')
            .addEventListener('change', function() {

                updateChart(purchaseChart, 'purchase', this.value);

            });
    </script>
</body>

</html>