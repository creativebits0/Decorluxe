<?php
session_start();

if (!isset($_SESSION['username']) || !isset($_SESSION['role'])) {
    header("Location: /decorluxe/presentation/login.php");
    exit();
}

if ($_SESSION['role'] !== 'project_incharge') {
    header("Location: /decorluxe/presentation/login.php");
    exit();
}
$user = $_SESSION['username'];

$userType = 'project_incharge';
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
    <script src="/decorluxe/assets/fullCalendar/index.global.min.js"></script>


    <!-- <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.css" rel="stylesheet"> -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script> -->
    <style>
        .lg-modals {
            width: 100%;
            min-width: 1000px;
            display: flex;
            justify-content: center;
            margin-left: -250px;

        }
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


    <!-- Dynamic Content -->
    <main id="content">
        <div class="text-center text-secondary mt-4">
            <div class="container-fluid">
                <div class="row g-3">
                    <div class="table-wrapper container">
                        <div class="table-title">
                            <div class="mt-2 bg-dark d-flex justify-content-between p-3 rounded">
                                <h2 class="text-light fs-4 mb-0">Appointment Management</h2>
                                <button class="btn btn-sm text-light d-flex justify-content-between align-items-center" style="background-color:#4CAF50;" onclick="openAddAppointment()">
                                    <i class="material-icons">&#xE147;</i> Add New Appointment
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


                        <!-- Calander -->

                        <div class="card mt-3">
                            <div class="card-header">
                                <h5 class="mb-0">Appointment Calendar</h5>
                            </div>

                            <div class="card-body" style="width: 60%; margin-left: 20%;">
                                <div id="calendar"></div>
                            </div>

                        </div>
                        <div id="dynamic-content">
                            <?php include_once '../../business/admin/appointment_management_process.php'; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <div id="addAppointmentModal" class="modal ">
        <div class="modal-dialog modal-dialog-centered modal-md ">
            <div class="modal-content" style="background-color:#aa8c2c;">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="form-head">ADD APPOINTMENT</span>
                    <span class="close" onclick="closeModal('addAppointmentModal')">&times;</span>
                </div>
                <hr>
                <div id="addAppointmentContent"></div>
            </div>
        </div>
    </div>

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

    <div id="editAppointmentModal" class="modal">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content" style="background-color:#aa8c2c;">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="form-head">EDIT APPOINTMENT</span>
                    <span class="close" onclick="closeModal('editAppointmentModal')">&times;</span>
                </div>
                <hr>
                <div id="editAppointmentContent"></div>
            </div>
        </div>
    </div>
    <!-- measurement modal -->
    <!-- measurement modal -->

    <div id="measurementModal" class="modal">
        <div class="modal-dialog modal-xl">
            <div class="modal-content p-3">

                <div class="d-flex justify-content-between align-items-center">
                    <span class="form-head">SITE IMAGES</span>
                    <span class="close" onclick="closeModal('measurementModal')">&times;</span>
                </div>

                <input type="hidden" id="measure_appointment_id">

                <table class="table table-bordered" id="measureTable">

                    <thead>
                        <tr>
                            <th>Area Name</th>
                            <th>Image</th>
                            <th>Preview</th>
                            <th>Notes</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody id="measureBody">

                        <tr>

                            <td>
                                <input type="text"
                                    class="form-control area_name">
                            </td>

                            <td>
                                <input type="file"
                                    class="form-control image_file"
                                    accept="image/*"
                                    onchange="previewImage(this)">
                            </td>

                            <td>
                                <img class="preview"
                                    width="100"
                                    style="display:none;
                                border-radius:10px;
                                border:1px solid #ccc;">
                            </td>

                            <td>
                                <input type="text"
                                    class="form-control notes">
                            </td>

                            <td>
                                <button type="button"
                                    class="btn btn-danger btn-sm"
                                    onclick="removeRow(this)">
                                    X
                                </button>
                            </td>

                        </tr>

                    </tbody>

                </table>

                <button class="btn btn-primary mb-2"
                    onclick="addRow()">
                    + Add More
                </button>

                <button type="button"
                    class="btn btn-success w-100"
                    onclick="saveMeasurement()">
                    Save Images
                </button>

            </div>
        </div>
    </div>

    <!-- Quotation modal -->
    <div id="addQuoteModal" class="modal ">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content lg-modals" style="background-color:#aa8c2c;">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="form-head">ADD QUOTATION</span>
                    <span class="close" onclick="closeModal('addQuoteModal')">&times;</span>
                </div>
                <hr>
                <div id="addQuoteContent"></div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer id="footer">© 2026 Creative Bits</footer>
    <script src="/decorluxe/assets/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script> -->
    <!-- Global scripts (load once only) -->
    <script src="/decorluxe/assets/js/functionScript.js"></script>
    <script src="/decorluxe/assets/js/quotation.js"></script>
    <script src="/decorluxe/assets/js/script.js"></script>

    <script>
        let clientsData = [];

        function clientList(selectedClient = null, selectedAddress = null) {

            fetch('/decorluxe/business/admin/clients/get_clients.php')

                .then(res => res.json())

                .then(data => {

                    clientsData = data;

                    const select = document.getElementById("client");

                    select.innerHTML = "<option value=''>Select Client</option>";

                    data.forEach(client => {

                        let selected = client.client_id == selectedClient ? "selected" : "";

                        select.innerHTML += `
<option value="${client.client_id}" ${selected}>
${client.fullname}
</option>
`;

                    });

                    // Remove old event first
                    select.onchange = null;

                    // Add change event
                    select.onchange = function() {
                        loadClientAddresses(this.value);
                    };

                    // Load address if already selected
                    if (selectedClient) {
                        loadClientAddresses(selectedClient, selectedAddress);
                    }

                });
        }


        function loadClientAddresses(clientId, selectedAddress = null) {

            const location = document.getElementById("location");

            location.innerHTML = "<option>Select Location</option>";

            const client = clientsData.find(
                c => c.client_id == clientId
            );

            if (client && client.addresses) {

                client.addresses.forEach(addr => {

                    let selected = addr.address_id == selectedAddress ? "selected" : "";

                    location.innerHTML += `
<option value="${addr.address_id}" ${selected}>
${addr.address}
</option>
`;

                });

            }

        }
        // Open Add Appointment Modal
        function openAddAppointment() {
            const modal = document.getElementById("addAppointmentModal");
            const content = document.getElementById("addAppointmentContent");
            modal.style.display = "block";
            content.innerHTML = "<p class='text-center'>Loading...</p>";

            fetch(`/decorluxe/presentation/admin/appointments/add_appointment_form.php`)
                .then(res => res.text())
                .then(html => {
                    content.innerHTML = html;

                    //Run initialization script manually
                    clientList();
                });
        }

        // Open Edit Appointment Modal
        function editAppointment(id) {
            const modal = document.getElementById("editAppointmentModal");
            const content = document.getElementById("editAppointmentContent");
            modal.style.display = "block";
            content.innerHTML = "<p class='text-center'>Loading...</p>";

            fetch(`/decorluxe/presentation/admin/appointments/edit_appointment_form.php?id=${id}`)
                .then(res => res.text())
                .then(html => {
                    content.innerHTML = html;

                    const clientId = document.querySelector('[name="client"]').value;
                    const addressId = document.querySelector('[name="location"]').value;

                    clientList(clientId, addressId);

                });
        }

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
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            var calendarEl = document.getElementById('calendar');

            var calendar = new FullCalendar.Calendar(calendarEl, {

                initialView: 'dayGridMonth',

                events: '/decorluxe/business/admin/appointments/get_calendar_events.php',

                eventClick: function(info) {

                    viewAppointment(info.event.id);

                }

            });

            calendar.render();

        });
    </script>


    <script>
        function saveMeasurement() {

            let rows = document.querySelectorAll("#measureBody tr");

            let fd = new FormData();

            fd.append(
                "appointment_id",
                document.getElementById("measure_appointment_id").value
            );

            rows.forEach((row, index) => {

                fd.append(
                    "area_name[]",
                    row.querySelector(".area_name").value
                );

                fd.append(
                    "notes[]",
                    row.querySelector(".notes").value
                );

                let image =
                    row.querySelector(".image_file").files[0];

                fd.append("images[]", image);

            });

            fetch("/decorluxe/business/admin/appointments/measurement_process.php", {

                    method: "POST",
                    body: fd

                })

                .then(res => res.text())

                .then(data => {

                    alert(data);

                    location.reload();

                });
        }
    </script>
    <script>
        document.addEventListener("submit", function(e) {
            if (e.target.id === "editAppointmentForm") {
                e.preventDefault();

                let type = e.target.querySelector('[name="type"]')?.value;
                let status = e.target.querySelector('[name="status"]')?.value;

                if (type === "Site Visit" && status === "Completed") {
                    document.getElementById("measurementModal").style.display = "block";
                    // return;
                }

                submitAppointment();
            }
        });

        function submitAppointment() {
            const formData =
                new FormData(document.getElementById("editAppointmentForm"));

            if (status === 'Cancelled') {

                const confirmed = confirm(
                    "Are you sure you want to cancel this appointment?\n\nThis action will disable editing and measurements."
                );

                if (!confirmed) {
                    return;
                }
            }
            fetch("/decorluxe/business/admin/appointments/edit_appointment_process.php", {
                    method: "POST",
                    body: formData
                })
                .then(res => res.json())
                .then(data => {

                    // alert(data.message);

                    if (data.openMeasurement) {

                        document.getElementById("measurementModal").style.display = "block";

                        document.getElementById("measure_appointment_id").value = data.id;

                    } else {
                        // location.reload();
                        window.location.href = data.redirect;

                    }

                });
        }
    </script>


    <script>
        function addRow() {

            let row = `

    <tr>

        <td>
            <input type="text"
                class="form-control area_name">
        </td>

        <td>
            <input type="file"
                class="form-control image_file"
                accept="image/*"
                onchange="previewImage(this)">
        </td>

        <td>
            <img class="preview"
                width="100"
                style="display:none;
                border-radius:10px;
                border:1px solid #ccc;">
        </td>

        <td>
            <input type="text"
                class="form-control notes">
        </td>

        <td>
            <button type="button"
                class="btn btn-danger btn-sm"
                onclick="removeRow(this)">
                X
            </button>
        </td>

    </tr>

    `;

            document.getElementById("measureBody")
                .insertAdjacentHTML("beforeend", row);
        }

        function removeRow(btn) {
            btn.closest("tr").remove();
        }

        function previewImage(input) {

            const file = input.files[0];

            const preview =
                input.closest("tr")
                .querySelector(".preview");

            if (file) {

                preview.src = URL.createObjectURL(file);

                preview.style.display = "block";
            }
        }
    </script>

    <script>
        function openMeasurementModal(id) {
            document.getElementById("measurementModal").style.display = "block";

            loadMeasurements(id);
        }

        // edit measurement 

        function loadMeasurements(id) {

            document.getElementById(
                "measure_appointment_id"
            ).value = id;

            fetch("/decorluxe/business/admin/appointments/get_measurements.php?id=" + id)

                .then(res => res.json())

                .then(data => {

                    let body =
                        document.getElementById("measureBody");

                    body.innerHTML = "";

                    if (data.length == 0) {
                        addRow();
                        return;
                    }

                    data.forEach(row => {

                        body.innerHTML += `

                <tr>

                    <td>
                        <input type="text"
                            class="form-control area_name"
                            value="${row.area_name}">
                    </td>

                    <td>
                        <input type="file"
                            class="form-control image_file"
                            accept="image/*"
                            onchange="previewImage(this)">
                    </td>

                    <td>

                        <img
                            src="/decorluxe/uploads/measurements/${row.image_path}"
                            width="100"
                            class="preview"
                            style="border-radius:10px;
                            border:1px solid #ccc;">

                    </td>

                    <td>
                        <input type="text"
                            class="form-control notes"
                            value="${row.notes}">
                    </td>

                    <td>
                        <button type="button"
                            onclick="removeRow(this)"
                            class="btn btn-danger btn-sm">
                            X
                        </button>
                    </td>

                </tr>

                `;
                    });

                });
        }
    </script>
    <!-- generate quotation from appointment ID -->
    <!-- <script>
        function generateQuotation(id) {
            const modal = document.getElementById("addQuoteModal");
            const content = document.getElementById("addQuoteContent");

            modal.style.display = "block";
            content.innerHTML = "<p class='text-center'>Loading...</p>";

            fetch(`/decorluxe/presentation/admin/quotation/add_quote_form.php?appointment_id=${id}`)
                .then(res => res.text())
                .then(html => {
                    content.innerHTML = html;
                    initQuotationForm(id); // important
                });
        }
    </script> -->
    <script>
        function createProjectStart(parent_id) {
            let date = document.getElementById("project_date").value;

            if (date == "") {
                alert("Select project start date");
                return;
            }

            let fd = new FormData();

            fd.append("mode", "project_start");
            fd.append("parent_id", parent_id);
            fd.append("date", date);

            fetch("/decorluxe/business/admin/appointments/edit_appointment_process.php", {
                    method: "POST",
                    body: fd
                })
                .then(res => res.text())
                .then(data => {
                    alert(data);
                    location.reload();
                });
        }
    </script>
    <script>
        function updateProjectStart(parent_id) {

            let date = document.getElementById("project_date").value;

            if (!date) {
                alert("Select date");
                return;
            }

            let fd = new FormData();
            fd.append("mode", "update_project_start");
            fd.append("parent_id", parent_id);
            fd.append("date", date);

            fetch("/decorluxe/business/admin/appointments/edit_appointment_process.php", {
                    method: "POST",
                    body: fd
                })
                .then(res => res.json())
                .then(data => {
                    alert(data.message);
                    location.reload();
                });
        }
    </script>
    <!-- <script>
        //Open View Quote Modal
        function viewQuote(id) {
            const modal = document.getElementById("viewQuoteModal");
            const content = document.getElementById("viewQuoteContent");
            modal.style.display = "block";
            content.innerHTML = "<p class='text-center'>Loading...</p>";

            fetch(`/decorluxe/presentation/admin/quotation/view_quote_form.php?id=${id}`)
                .then(res => res.text())
                .then(html => content.innerHTML = html)
                .catch(() => content.innerHTML = "<p class='text-danger'>Failed to load data.</p>");
        }

        //Universal Close Function
        function closeModal(id) {
            document.getElementById(id).style.display = "none";
        }
    </script> -->

</body>

</html>