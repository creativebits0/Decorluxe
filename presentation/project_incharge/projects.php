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


    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
    <style>
        .lg-modals {
            width: 95%;
            max-width: 1400px;
            margin: auto;
            display: flex;
            justify-content: center;

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
                                <h2 class="text-light fs-4 mb-0">Project Management</h2>
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
                            <?php include_once '../../business/admin/project_management_process.php'; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <div id="viewProjectModal" class="modal">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content lg-modals" style="background-color:#aa8c2c;">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="form-head">VIEW PROJECT</span>
                    <span class="close" onclick="closeModal('viewProjectModal')">&times;</span>
                </div>
                <hr>
                <div id="viewProjectContent"></div>
            </div>
        </div>
    </div>

    <div id="editProjectModal" class="modal">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content lg-modals" style="background-color:#aa8c2c;">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="form-head">EDIT PROJECT</span>
                    <span class="close" onclick="closeModal('editProjectModal')">&times;</span>
                </div>
                <hr>
                <div id="editProjectContent"></div>
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
        //Open Edit Project Modal
        function editProject(id) {
            const modal = document.getElementById("editProjectModal");
            const content = document.getElementById("editProjectContent");
            modal.style.display = "block";
            content.innerHTML = "<p class='text-center'>Loading...</p>";

            fetch(`/decorluxe/presentation/admin/projects/edit_project_form.php?id=${id}`)
                .then(res => res.text())
                .then(html => content.innerHTML = html)
                .catch(() => content.innerHTML = "<p class='text-danger'>Failed to load form.</p>");
        }

        //Open View Project Modal
        function viewProject(id) {
            const modal = document.getElementById("viewProjectModal");
            const content = document.getElementById("viewProjectContent");
            modal.style.display = "block";
            content.innerHTML = "<p class='text-center'>Loading...</p>";

            fetch(`/decorluxe/presentation/admin/projects/view_project_form.php?id=${id}`)
                .then(res => res.text())
                .then(html => content.innerHTML = html)
                .catch(() => content.innerHTML = "<p class='text-danger'>Failed to load data.</p>");
        }

        //Universal Close Function
        function closeModal(id) {
            document.getElementById(id).style.display = "none";
        }
    </script>
    <script>
        function addProjectItem(areaId) {

            let tbody = document.getElementById(areaId);

            if (!tbody) {
                console.error("Area tbody not found:", areaId);
                return;
            }

            let areaBox = tbody.closest('.areaBox');

            let areaName = areaBox.querySelector('.areaInput').value;

            // Get item options from hidden template
            let itemOptions = document.getElementById('itemOptionsTemplate').innerHTML;

            let html = `

    <tr>

        <td>

            <select
                name="new_item_id[]"
                class="form-control itemSelect"
                onchange="fillItemName(this)">

                <option value="">Select Item</option>

                ${itemOptions}

            </select>

            <input
                type="hidden"
                name="new_item_name[]"
                class="itemNameInput">

        </td>

        <td>

            <input
                type="number"
                name="new_qty[]"
                class="form-control qty"
                value="1"
                min="1">

        </td>

        <td>

            <input
                type="number"
                name="new_price[]"
                class="form-control price"
                value="0"
                min="0"
                step="0.01">

        </td>

        <td>

            <input
                type="text"
                class="form-control amount"
                value="0.00"
                readonly>

        </td>

        <td>

            <button
                type="button"
                class="btn btn-danger btn-sm"
                onclick="removeRow(this)">

                X

            </button>

        </td>

        <input
            type="hidden"
            name="new_area_name[]"
            value="${areaName}"
            class="dynamicAreaName">

    </tr>
    `;

            tbody.insertAdjacentHTML('beforeend', html);

            calculateAreaTotal(areaBox);
        }
    </script>

    <script>
        function removeRow(btn) {
            let row = btn.closest("tr");

            let itemId = row.getAttribute("data-id"); // IMPORTANT

            if (itemId) {
                let input = document.createElement("input");
                input.type = "hidden";
                input.name = "deleted_items[]";
                input.value = itemId;
                document.querySelector("form").appendChild(input);
            }

            let areaBox = btn.closest(".areaBox");

            row.remove();

            calculateAreaTotal(areaBox);
        }
    </script>

    <script>
        function addAreaContainer() {

            let uniqueId = Date.now();
            let html = `

    <div class="projectBox areaBox">

        <div class="d-flex justify-content-between align-items-center mb-3">

            <div class="w-50">
             <div style="display: flex; align-items: center; gap: 30px;">

                <label class="fw-bold mb-1">
                    AreaName
                </label>

                <input
                    type="text"
                    name="area_name_group[]"
                    class="form-control areaInput"
                    >

            </div>
            </div>

            <div class="mt-2">

                <button
                    type="button"
                    class="btn btn-success btn-sm"
onclick="addProjectItem('area_${uniqueId}')">
                    + Add Item

                </button>

                <button
                    type="button"
                    class="btn btn-danger btn-sm"
                    onclick="removeArea(this)">

                    Remove Area

                </button>

            </div>

        </div>

        <div class="table-responsive">

            <table class="table table-bordered align-middle">

                <thead>

                    <tr>
                        <th>Item Name</th>
                        <th width="100">Qty</th>
                        <th width="150">Price</th>
                        <th width="150">Amount</th>
                        <th width="80">Action</th>
                    </tr>

                </thead>

<tbody id="area_${uniqueId}">
                </tbody>

            </table>

        </div>

        <div class="mt-3 row">

            <div class="col-md-3">

                <label class="form-label small">
                    Material Cost
                </label>

                <input
                    type="number"
                    name="material_cost[]"
                    class="form-control materialCost"
                    step="0.01"
                    >
                    </div>
 
                
                        <div class="col-md-3">
                            <label class="form-label small">Labour/Installation</label>
                            <input type="number" name="labour_charge[]" class="form-control labour-charge" oninput="updateAreaTotal(this.closest('.areaBox'))">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label small">Transport</label>
                            <input type="number" name="transport[]" class="form-control transport" oninput="updateAreaTotal(this.closest('.areaBox'))">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label small">Other Expenditures</label>
                            <input type="number" name="other_charges[]" class="form-control other-charges" oninput="updateAreaTotal(this.closest('.areaBox'))">
                        </div>

                          <div class="col-md-3">

                <label class="form-label small">
                    Area Total Cost
                </label>

                <input
                    type="number"
                    name="area_total[]"
                    class="form-control areaTotal"
                    step="0.01"
                    >
                    </div>

                    <div class="col-md-9">
    

                <label class="form-label small">
                    Description
                </label>

                 <textarea
                            name="description[]"
                            class="form-control"
                            rows="2"></textarea>

            </div>

        </div>

    </div>
    `;

            document
                .getElementById('allAreas')
                .insertAdjacentHTML('beforeend', html);
        }



        /* ADD ITEM */


        /* PREVENT DUPLICATE ITEM */
        function preventDuplicateItems(currentRow) {

            let areaBox = currentRow.closest(".areaBox");

            let currentSelect =
                currentRow.querySelector(".itemSelect");

            let selected = currentSelect.value;

            if (!selected) return;

            let duplicate = false;

            areaBox.querySelectorAll(".itemSelect").forEach(sel => {

                if (
                    sel !== currentSelect &&
                    sel.value === selected
                ) {
                    duplicate = true;
                }

            });

            if (duplicate) {

                alert("This item already exists in this area.");

                currentSelect.value = "";

                let hidden =
                    currentRow.querySelector(".itemNameInput");

                if (hidden) {
                    hidden.value = "";
                }
            }
        }




        /* REMOVE AREA */
        function removeArea(btn) {

            let areaBox = btn.closest('.areaBox');

            // Get area name
            let areaName =
                areaBox.querySelector(".areaInput").value;

            // Save deleted area
            if (areaName.trim() !== '') {

                let areaInput = document.createElement("input");

                areaInput.type = "hidden";

                areaInput.name = "deleted_areas[]";

                areaInput.value = areaName;

                document
                    .getElementById("projectForm")
                    .appendChild(areaInput);
            }

            // Save deleted items
            areaBox.querySelectorAll(
                "input[name='item_id[]']"
            ).forEach(input => {

                let del = document.createElement("input");

                del.type = "hidden";

                del.name = "deleted_items[]";

                del.value = input.value;

                document
                    .getElementById("projectForm")
                    .appendChild(del);
            });

            areaBox.remove();

            calculateGrandTotal();
        }

        function updateAreaTotal(areaBox) {
            calculateAreaTotal(areaBox);
        }


        // document.addEventListener("input", function(e) {

        //     if (
        //         e.target.classList.contains("qty") ||
        //         e.target.classList.contains("price")
        //     ) {

        //         let row = e.target.closest("tr");

        //         if (!row) return;

        //         let qty = parseFloat(
        //             row.querySelector(".qty")?.value || 0
        //         );

        //         let price = parseFloat(
        //             row.querySelector(".price")?.value || 0
        //         );

        //         let amount = qty * price;

        //         let amountField =
        //             row.querySelector(".amount");

        //         if (amountField) {

        //             amountField.value =
        //                 amount.toFixed(2);
        //         }

        //         let areaBox =
        //             row.closest(".areaBox");

        //         if (areaBox) {

        //             calculateAreaTotal(areaBox);
        //         }
        //     }
        // });

        document.addEventListener("input", function(e) {

            if (
                e.target.classList.contains("qty") ||
                e.target.classList.contains("price") ||
                e.target.classList.contains("labour-charge") ||
                e.target.classList.contains("transport") ||
                e.target.classList.contains("other-charges")
            ) {

                let areaBox = e.target.closest(".areaBox");
                calculateAreaTotal(areaBox);
            }
        });


        function calculateAreaTotal(areaBox) {

            let itemsTotal = 0;

            // 1. ITEM TOTALS
            areaBox.querySelectorAll("tbody tr").forEach(row => {

                let qty = parseFloat(row.querySelector(".qty")?.value || 0);
                let price = parseFloat(row.querySelector(".price")?.value || 0);

                let amount = qty * price;

                row.querySelector(".amount").value = amount.toFixed(2);

                itemsTotal += amount;
            });

            // 2. CHARGES
            let labour = parseFloat(areaBox.querySelector(".labour-charge")?.value || 0);
            let transport = parseFloat(areaBox.querySelector(".transport")?.value || 0);
            let other = parseFloat(areaBox.querySelector(".other-charges")?.value || 0);

            // 3. MATERIAL COST FIELD UPDATE (IMPORTANT)
            let materialInput = areaBox.querySelector(".material-cost");
            if (materialInput) {
                materialInput.value = itemsTotal.toFixed(2);
            }

            // 4. FINAL TOTAL
            let finalTotal = itemsTotal + labour + transport + other;

            areaBox.querySelector(".areaTotal").value = finalTotal.toFixed(2);

            calculateGrandTotal();
        }

        /* GRAND TOTAL */

        function calculateGrandTotal() {

            let grandTotal = 0;

            document.querySelectorAll(".areaTotal").forEach(input => {

                let val = parseFloat(input.value);

                if (!isNaN(val)) {
                    grandTotal += val;
                }

            });

            let totalField = document.getElementById("total");

            if (totalField) {
                totalField.value = grandTotal.toFixed(2);
            }
        }

        // Avoiding manual subtotal change and direct grand total calculation
        // function calculateGrandTotal() {

        //     let grandTotal = 0;

        //     document.querySelectorAll(".amount").forEach(input => {

        //         let val = parseFloat(input.value || 0);

        //         if (!isNaN(val)) {

        //             grandTotal += val;
        //         }
        //     });

        //     let totalField = document.getElementById("total");

        //     if (totalField) {

        //         totalField.value = grandTotal.toFixed(2);
        //     }
        // }


        /* UPDATE AREA NAME */

        document.addEventListener("input", function(e) {

            if (e.target.classList.contains("areaInput")) {

                let areaBox = e.target.closest(".areaBox");

                let areaName = e.target.value;

                areaBox.querySelectorAll(".dynamicAreaName")
                    .forEach(input => {

                        input.value = areaName;

                    });

                areaBox.querySelectorAll(".hiddenAreaName")
                    .forEach(input => {

                        input.value = areaName;

                    });
            }

        });



        /* INITIAL LOAD */
        window.addEventListener("load", () => {

            document.querySelectorAll(".areaBox").forEach(areaBox => {

                calculateAreaTotal(areaBox);

            });

        });
    </script>

    <script>
        function fillItemName(select) {

            let row = select.closest("tr");

            let option = select.options[select.selectedIndex];

            let itemName = option.getAttribute("data-name") || "";

            let hidden = row.querySelector(".itemNameInput");

            if (hidden) {
                hidden.value = itemName;
            }

            preventDuplicateItems(row);
        }
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {

            window.addProjectItem = addProjectItem;
            window.addAreaContainer = addAreaContainer;
            window.removeArea = removeArea;
            window.removeRow = removeRow;
            window.fillItemName = fillItemName;

        });
    </script>