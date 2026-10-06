<?php
session_start();
require_once '../../data/admin/dashboard_model.php';

if (!isset($_SESSION['username']) || !isset($_SESSION['role'])) {
    header("Location: /decorluxe/presentation/login.php");
    exit();
}

if ($_SESSION['role'] !== 'project_incharge') {
    header("Location: /decorluxe/presentation/login.php");
    exit();
}
$user = $_SESSION['username'];
$user_id =  $_SESSION['id'];

$userType = 'project_incharge';


$user = $_SESSION['username'];


$totalBookings = getTotalBookings();

$completedBookings = getCompletedBookings();

$pendingBookings = getPendingBookings();

$todayResult = getTodayAppointments();

$upcomingResult = getUpcomingAppointments();
?>


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
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="/decorluxe/assets/css/style.css" rel="stylesheet">
    <link href="/decorluxe/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/decorluxe/assets/css/material-icons.css" />
    <script src="/decorluxe/assets/fullCalendar/index.global.min.js"></script>


    <!-- <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.css" rel="stylesheet"> -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script> -->
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
            <li class="nav-list"><a href="/decorluxe/presentation/project_incharge/default_dashboard.php" class="nav-link d-flex justify-content-start align-items-center gap-3 active"><i class="material-icons">dashboard</i>Dashboard</a></li>
            <!-- <li class="nav-list"><a href="/decorluxe/presentation/admin/user_content.php" class="nav-link d-flex justify-content-start align-items-center gap-3"><i class="material-icons">person</i>Users</a></li> -->
            <li class="nav-list"><a href="/decorluxe/presentation/project_incharge/clients.php" class="nav-link d-flex justify-content-start align-items-center gap-3"><i class="material-icons">diversity_3</i>Clients</a></li>
            <li class="nav-list"><a href="/decorluxe/presentation/project_incharge/services.php" class="nav-link d-flex justify-content-start align-items-center gap-3"><i class="material-icons">tire_repair</i>Services</a></li>
            <!-- <li class="nav-list"><a href="/decorluxe/presentation/project_incharge/suppliers.php" class="nav-link d-flex justify-content-start align-items-center gap-3 "><i class="material-icons">diversity_1</i>Suppliers</a></li> -->
            <li class="sidebar-item dropdown-sidebar nav-list">
                <a href="#" class="nav-link sidebar-link dropdown-toggle sidebar-dropdown-btn d-flex justify-content-start align-items-center gap-3 w-100" id="itemDropdown" data-toggle="dropdown" aria-expanded="false">
                    <i class="material-icons">inventory</i>Inventory
                </a>
                <ul class="dropdown-menu sidebar-dropdown-menu " aria-labelledby="itemDropdown">
                    <li><a class="dropdown-item " href="/decorluxe/presentation/project_incharge/item_content.php">Item</a></li>
                    <!-- <li><a class="dropdown-item " href="/decorluxe/presentation/project_incharge/purchase_content.php">Purchase</a></li>
                    <li><a class="dropdown-item " href="/decorluxe/presentation/project_incharge/sales_content.php">Sales</a></li> -->
                    <li><a class="dropdown-item " href="/decorluxe/presentation/project_incharge/stock_content.php">Stock</a></li>
                </ul>
            </li>
            <!-- <li class="sidebar-item dropdown-sidebar nav-list">
                <a href="#" class="nav-link sidebar-link dropdown-toggle sidebar-dropdown-btn d-flex justify-content-start align-items-center gap-3 w-100" id="itemDropdown" data-toggle="dropdown" aria-expanded="false">
                    <i class="material-icons">paid</i>Finance
                </a>
                <ul class="dropdown-menu sidebar-dropdown-menu " aria-labelledby="itemDropdown">
                    <li><a class="dropdown-item " href="/decorluxe/presentation/project_incharge/expenses.php">Expenses</a></li>
                    <li><a class="dropdown-item " href="/decorluxe/presentation/project_incharge/wages.php">Wages</a></li>
                </ul>
            </li> -->

            <li class="nav-list"><a href="/decorluxe/presentation/project_incharge/appointment_content.php" class="nav-link d-flex justify-content-start align-items-center gap-3 "><i class="material-icons">calendar_month</i>Appointments</a></li>
            <li class="nav-list"><a href="/decorluxe/presentation/project_incharge/quotation.php" class="nav-link d-flex justify-content-start align-items-center gap-3"><i class="material-icons">request_quote</i>Quotation</a></li>
            <li class="nav-list"><a href="/decorluxe/presentation/project_incharge/projects.php" class="nav-link d-flex justify-content-start align-items-center gap-3"><i class="material-icons">design_services</i>Projects</a></li>

            <!-- <li><a href="#" class="nav-link" data-page="/decorluxe/presentation/project_incharge/reports_content.php">Reports</a></li> -->
            <li class="sidebar-item dropdown-sidebar nav-list">
                <a href="#" class="nav-link sidebar-link dropdown-toggle sidebar-dropdown-btn d-flex justify-content-start align-items-center gap-3 w-100" id="reportDropdown" data-toggle="dropdown" aria-expanded="false">
                    <i class="material-icons">summarize</i>Reports
                </a>
                <ul class="dropdown-menu sidebar-dropdown-menu" aria-labelledby="reportDropdown">
                    <li><a class="dropdown-item" href="/decorluxe/presentation/project_incharge/reports/booking_report.php">Booking Report</a></li>
                    <!-- <li><a class="dropdown-item" href="/decorluxe/presentation/project_incharge/reports/sales_report.php">Sales Report</a></li> -->
                    <!-- <li><a class="dropdown-item " href="/decorluxe/presentation/project_incharge/reports/purchase_report.php">Purchase Report</a></li> -->
                </ul>
            </li>
            <!-- <li class="nav-list"><a href="/decorluxe/presentation/project_incharge/settings.php" class="nav-link d-flex justify-content-start align-items-center gap-3 "><i class="material-icons">settings</i>Settings</a></li> -->
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

                <div class="col-md-4">

                    <div class="card dashboard-card card-shadow border-0">

                        <div class="card-body text-center">

                            <h6>Total Bookings</h6>

                            <h2 class="fw-bold text-primary">
                                <?= $totalBookings ?>
                            </h2>

                        </div>

                    </div>

                </div>

                <div class="col-md-4">

                    <div class="card dashboard-card card-shadow border-0">

                        <div class="card-body text-center">

                            <h6>Completed</h6>

                            <h2 class="fw-bold text-success">
                                <?= $completedBookings ?>
                            </h2>

                        </div>

                    </div>

                </div>

                <div class="col-md-4">

                    <div class="card dashboard-card card-shadow border-0">

                        <div class="card-body text-center">

                            <h6>Pending</h6>

                            <h2 class="fw-bold text-warning">
                                <?= $pendingBookings ?>
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