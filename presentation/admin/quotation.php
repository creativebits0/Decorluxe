<?php
session_start();
require_once '../../data/admin/quotation_model.php';

if (!isset($_SESSION['username']) || !isset($_SESSION['role'])) {
    header("Location: /decorluxe/presentation/login.php");
    exit();
}

expireOldQuotations();

$user = $_SESSION['username']
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Decorluxe Interiors | Quotation</title>
    <link rel="icon" type="image/x-icon" href="/decorluxe/assets/images/logo.jpeg">
    <link href="/decorluxe/assets/css/style.css" rel="stylesheet">
    <link href="/decorluxe/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/decorluxe/assets/css/material-icons.css" />

</head>
<style>
    .lg-modals {
        width: 100%;
        min-width: 1000px;
        display: flex;
        justify-content: center;
        margin: 0 auto;

    }

    .image-modal {
        display: none;
        position: fixed;
        z-index: 9999;
        padding-top: 60px;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.9);
        text-align: center;
    }

    .modal-content-img {
        max-width: 90%;
        max-height: 85vh;
        border-radius: 8px;
    }

    .close-btn {
        position: absolute;
        top: 20px;
        right: 30px;
        font-size: 35px;
        color: white;
        cursor: pointer;
    }
</style>

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
            <li class="nav-list"><a href="/decorluxe/presentation/admin/quotation.php" class="nav-link d-flex justify-content-start align-items-center gap-3 active"><i class="material-icons">request_quote</i>Quotation</a></li>
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
                                <h2 class="text-light fs-4 mb-0">Quotation Management</h2>
                                <!-- <button class="btn btn-sm text-light d-flex justify-content-between align-items-center" style="background-color: #D4AF37;" onclick="openAddQuote()">
                                    <i class="material-icons">&#xE147;</i> Add New Quotation
                                </button> -->
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
                                        data-process-url="/decorluxe/business/admin/quotation_management_process.php"
                                        data-target-div="dynamic-content">
                                    <button class="btn btn-secondary"><i class="material-icons">search</i></button>
                                </div>
                            </div>
                        </div>

                        <div id="dynamic-content">
                            <?php include_once '../../business/admin/quotation_management_process.php'; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Modals (global, always available) -->
    <div id="addQuoteModal" class="modal ">
        <div class="modal-dialog modal-dialog-centered modal-md ">
            <div class="modal-content" style="background-color:#aa8c2c;">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="form-head">ADD QUOTATION</span>
                    <span class="close" onclick="closeModal('addQuoteModal')">&times;</span>
                </div>
                <hr>
                <div id="addQuoteContent"></div>
            </div>
        </div>
    </div>

    <div id="viewQuoteModal" class="modal">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content lg-modals" style="background-color:#aa8c2c;">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="form-head">VIEW QUOTATION</span>
                    <span class="close" onclick="closeModal('viewQuoteModal')">&times;</span>
                </div>
                <hr>
                <div id="viewQuoteContent"></div>
            </div>
        </div>
    </div>

    <div id="editQuoteModal" class="modal">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content lg-modals" style="background-color:#aa8c2c;">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="form-head">EDIT QUOTATION</span>
                    <span class="close" onclick="closeModal('editQuoteModal')">&times;</span>
                </div>
                <hr>
                <div id="editQuoteContent"></div>
            </div>
        </div>
    </div>

    <!-- Image Zoom Modal -->
    <div id="imageModal" class="image-modal" onclick="closeImageModal()">
        <span class="close-btn">&times;</span>
        <img id="modalImage" class="modal-content-img">
    </div>

    <!-- Footer -->
    <?php include_once __DIR__ . '/../../includes/footer.php'; ?>

    <script src="/decorluxe/assets/js/quotation.js"></script>

    <script>
        function openImageModal(src) {
            document.getElementById("imageModal").style.display = "block";
            document.getElementById("modalImage").src = src;
        }

        function closeImageModal() {
            document.getElementById("imageModal").style.display = "none";
        }
    </script>
    <script>
        function openAddQuote() {
            const modal = document.getElementById("addQuoteModal");
            const content = document.getElementById("addQuoteContent");
            modal.style.display = "block";
            content.innerHTML = "<p class='text-center'>Loading...</p>";

            fetch("/decorluxe/presentation/admin/quotation/add_quote_form.php")
                .then(res => res.text())
                .then(html => {
                    content.innerHTML = html;

                    //Run initialization script manually
                    initQuotationForm();
                });
        }

        // 
        function initQuotationForm() {
            // Auto date
            const today = new Date().toISOString().split('T')[0];
            const dateInput = document.getElementById('date');
            if (dateInput) dateInput.value = today;


            fetch('/decorluxe/business/admin/inventory/get_items.php')
                .then(res => res.json())
                .then(data => {

                    let options = '<option value="">Select Item</option>';

                    data.forEach(i => {
                        options += `<option value="${i.item_id}">
                ${i.itemCode} - ${i.itemName} - (${i.sellPrice})
            </option>`;
                    });

                    window.itemOptions = options;

                    // ENABLE BUTTON AFTER LOAD
                    document.querySelectorAll('#addItemBtn').forEach(btn => {
                        btn.disabled = false;
                    });

                    console.log("Items loaded successfully");
                })
                .catch(() => {
                    console.error("Failed to load items");
                });

            // console.log(window.itemOptions);
            // Load clients
            fetch('/decorluxe/business/admin/clients/get_clients.php')
                .then(res => res.json())
                .then(data => {
                    const select = document.getElementById('client');
                    select.innerHTML = '<option value="">Select Client</option>';
                    data.forEach(client => {
                        const opt = document.createElement('option');
                        opt.value = client.client_id; //
                        opt.textContent = client.fullname; // 
                        select.appendChild(opt);
                    });
                });
        }

        //Open Edit Quote Modal
        function editQuote(id) {
            const modal = document.getElementById("editQuoteModal");
            const content = document.getElementById("editQuoteContent");

            modal.style.display = "block";
            content.innerHTML = "<p class='text-center'>Loading...</p>";

            fetch(`/decorluxe/presentation/admin/quotation/edit_quote_form.php?id=${id}`)
                .then(res => res.text())
                .then(html => {
                    content.innerHTML = html;

                    loadItemsIntoSelects();

                    setTimeout(() => {
                        refreshAllCalculations();
                    }, 200);
                })
                .catch(() => {
                    content.innerHTML = "<p class='text-danger'>Failed to load form.</p>";
                });
        }

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

        function loadItemsIntoSelects() {
            fetch('/decorluxe/business/admin/inventory/get_items.php')
                .then(res => res.json())
                .then(data => {

                    let options = '<option value="">Select Item</option>';

                    data.forEach(i => {
                        options += `
<option
    value="${i.item_id}"
    data-price="${i.sellPrice}">
    ${i.itemCode} - ${i.itemName} - (${i.sellPrice})
</option>`;
                    });

                    document.querySelectorAll('#editQuoteContent .item-name').forEach(select => {

                        const selectedValue = select.getAttribute('data-selected');

                        select.innerHTML = options;

                        if (selectedValue) {
                            // convert both to string for safe comparison
                            select.value = String(selectedValue);

                            // fallback (important!)
                            if (select.value !== String(selectedValue)) {
                                [...select.options].forEach(opt => {
                                    if (opt.value == selectedValue) {
                                        opt.selected = true;
                                    }
                                });
                            }
                        }
                        console.log("Selected:", selectedValue);
                        console.log("Options:", options);
                    });
                });
        }
    </script>


    <script>
        window.itemOptions = null;
        window.itemsLoaded = false;

        function loadItems() {
            return fetch('/decorluxe/business/admin/inventory/get_items.php')
                .then(res => res.json())
                .then(data => {

                    let options = '<option value="">Select Item</option>';

                    data.forEach(i => {
                        //         options += `<option value="${i.item_id}">
                        //     ${i.itemCode} - ${i.itemName} - ( ${i.sellPrice})
                        // </option>`;
                        options += `
<option
    value="${i.item_id}"
    data-price="${i.sellPrice}">
    ${i.itemCode} - ${i.itemName} - (${i.sellPrice})
</option>`;
                    });

                    window.itemOptions = options;
                    window.itemsLoaded = true;

                    console.log("✅ Items preloaded");
                })
                .catch(err => {
                    console.error("❌ Item load failed", err);
                });
        }

        // LOAD IMMEDIATELY when page loads
        loadItems();
    </script>
    <script>
        // Sync the areaIndex with the number of existing areas loaded from PHP
        document.addEventListener("DOMContentLoaded", function() {
            const existingAreas = document.querySelectorAll('.areaBox').length;
            if (existingAreas > 0) {
                areaIndex = existingAreas - 1;
            }
            // Initialize UI items for the dropdowns
            initQuotationUI("edit");
        });
    </script>

    <script>
        document.addEventListener('submit', function(e) {

            if (e.target.id !== 'editQuoteForm') return;

            const originalStatus =
                document.getElementById('original_status')?.value;

            const newStatus =
                document.getElementById('quotation_status')?.value;

            if (
                originalStatus !== 'Rejected' &&
                newStatus === 'Rejected'
            ) {

                if (!confirm(
                        "Are you sure you want to reject this quotation?"
                    )) {
                    e.preventDefault();
                }
            }
        });
    </script>

    <script>
        function markImageForDelete(btn, imageId) {

            if (!confirm("Remove this image?")) {
                return;
            }

            // Hide image
            btn.closest(".image-card").style.display = "none";

            // Store deleted id
            const container = document.getElementById("deletedImagesContainer");

            let input = document.createElement("input");
            input.type = "hidden";
            input.name = "deleted_images[]";
            input.value = imageId;

            container.appendChild(input);
        }
    </script>
</body>

</html>