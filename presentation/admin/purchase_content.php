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
    <title>Decorluxe Interiors | Purchase</title>
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
                    <li><a class="dropdown-item active " href="/decorluxe/presentation/admin/purchase_content.php">Purchase</a></li>
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
                                <h2 class="text-light fs-4 mb-0">Purchase Management</h2>
                                <button class="btn btn-sm text-light d-flex justify-content-between align-items-center" style="background-color: #D4AF37;" onclick="openAddPurchase()">
                                    <i class="material-icons">&#xE147;</i> Add New Purchase
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
                                        data-process-url="/decorluxe/business/admin/purchase_management_process.php"
                                        data-target-div="dynamic-content"> <button class="btn btn-secondary"><i class="material-icons">search</i></button>
                                </div>
                            </div>
                        </div>
                        <div id="dynamic-content">
                            <?php include_once '../../business/admin/purchase_management_process.php'; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Modals (global, always available) -->
    <div id="addPurchaseModal" class="modal">
        <div class="modal-dialog modal-dialog-centered modal-lg ">
            <div class="modal-content purchaseModal" style="background-color:#aa8c2c;">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="form-head">ADD PURCHASE</span>
                    <span class="close" onclick="closeModal('addPurchaseModal')">&times;</span>
                </div>
                <hr>
                <div id="addPurchaseContent"></div>
            </div>
        </div>
    </div>

    <div id="viewPurchaseModal" class="modal ">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content purchaseModal" style="background-color:#aa8c2c;">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="form-head">VIEW PURCHASE</span>
                    <span class="close" onclick="closeModal('viewPurchaseModal')">&times;</span>
                </div>
                <hr>
                <div id="viewPurchaseContent"></div>
            </div>
        </div>
    </div>

    <div id="editPurchaseModal" class="modal ">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content purchaseModal" style="background-color:#aa8c2c;">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="form-head">EDIT PURCHASE</span>
                    <span class="close" onclick="closeModal('editPurchaseModal')">&times;</span>
                </div>
                <hr>
                <div id="editPurchaseContent"></div>
            </div>
        </div>
    </div>



    <!-- Footer -->
    <?php include_once __DIR__ . '/../../includes/footer.php'; ?>


    <script>
        // ✅ Open Add Purchase Modal
        function openAddPurchase() {
            const modal = document.getElementById("addPurchaseModal");
            const content = document.getElementById("addPurchaseContent");
            modal.style.display = "block";
            content.innerHTML = "<p class='text-center'>Loading...</p>";

            fetch("/decorluxe/presentation/admin/inventory/add_purchase_form.php")
                .then(res => res.text())
                .then(html => {
                    content.innerHTML = html;

                    loadSupplier();
                });
        }

        // ✅ Open Edit Purchase Modal
        function editPurchase(id) {
            const modal = document.getElementById("editPurchaseModal");
            const content = document.getElementById("editPurchaseContent");
            modal.style.display = "block";
            content.innerHTML = "<p class='text-center'>Loading...</p>";

            fetch(`/decorluxe/presentation/admin/inventory/edit_purchase_form.php?id=${id}`)
                .then(res => res.text())
                .then(html => {
                    content.innerHTML = html;

                    // loadSupplier();

                });
        }

        // ✅ Open View Purchase Modal
        function viewPurchase(id) {
            const modal = document.getElementById("viewPurchaseModal");
            const content = document.getElementById("viewPurchaseContent");
            modal.style.display = "block";
            content.innerHTML = "<p class='text-center'>Loading...</p>";

            fetch(`/decorluxe/presentation/admin/inventory/view_purchase_form.php?id=${id}`)
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
        /* =====================================================
   PURCHASE MANAGEMENT - COMPLETE SCRIPT
   ADD + EDIT + VIEW + COMMON FUNCTIONS
=====================================================*/

        let pItemCount = 0;
        let editItemCount = 0;

        /* =====================================================
           OPEN ADD PURCHASE
        =====================================================*/
        function openAddPurchase() {

            const modal = document.getElementById("addPurchaseModal");
            const content = document.getElementById("addPurchaseContent");

            modal.style.display = "block";
            content.innerHTML = "<p class='text-center'>Loading...</p>";

            pItemCount = 0;

            fetch("/decorluxe/presentation/admin/inventory/add_purchase_form.php")
                .then(res => res.text())
                .then(html => {

                    content.innerHTML = html;

                    loadSupplier();

                    bindFinalDiscountEvents();

                    const date = document.querySelector('#addPurchaseContent [name="purchase_date"]');

                    if (date) {
                        date.value = new Date().toISOString().split('T')[0];
                    }
                    const form = content.querySelector("#purchaseForm");

                    if (form) {

                        form.addEventListener("submit", function(e) {

                            // Don't allow saving without items
                            const total = parseFloat(form.querySelector("#total").value) || 0;

                            if (total <= 0) {
                                e.preventDefault();
                                alert("Please add at least one item before saving the purchase.");
                                return;
                            }

                            // Confirm if Verified
                            const status = form.querySelector("#purchase_status");

                            if (status && status.value === "Verified") {

                                if (!confirm(
                                        "You have selected 'Verified'.\n\n" +
                                        "Once this purchase is saved as Verified, it cannot be edited in the future.\n\n" +
                                        "Do you want to continue?"
                                    )) {
                                    e.preventDefault();
                                }

                            }

                        });

                    }
                });
        }

        /* =====================================================
           OPEN EDIT PURCHASE
        =====================================================*/
        function editPurchase(id) {

            const modal = document.getElementById("editPurchaseModal");
            const content = document.getElementById("editPurchaseContent");

            modal.style.display = "block";
            content.innerHTML = "<p class='text-center'>Loading...</p>";

            editItemCount = 0;

            fetch(`/decorluxe/presentation/admin/inventory/edit_purchase_form.php?id=${id}`)
                .then(res => res.text())
                .then(html => {

                    content.innerHTML = html;

                    bindEditFinalDiscountEvents();

                    document.querySelectorAll('#editPurchaseContent .itemField').forEach((row, index) => {

                        // editItemCount = index + 1;
                        // reorder("#addPurchaseContent .itemField");
                        editItemCount = document.querySelectorAll('#editPurchaseContent .itemField').length;

                        row.querySelector("h6").innerText = "Item " + (index + 1);

                        const selectedItem = row.querySelector(".item").dataset.selected || "";

                        loadItems(row.querySelector(".item"), selectedItem);

                        row.querySelectorAll("input,select").forEach(el => {
                            el.addEventListener("input", updateEditPurchaseTotal);
                            el.addEventListener("change", updateEditPurchaseTotal);
                        });

                        row.querySelector(".item").addEventListener("change", validateEditDuplicateItems);
                    });

                    updateEditPurchaseTotal();

                    const form = content.querySelector("#editPurchaseForm");

                    if (form) {

                        form.addEventListener("submit", function(e) {

                            // Don't allow saving without items
                            const total = parseFloat(form.querySelector("#total").value) || 0;

                            if (total <= 0) {
                                e.preventDefault();
                                alert("Please add at least one item before updating the purchase.");
                                return;
                            }

                            // Confirm if changing to Verified
                            const status = form.querySelector("#purchase_status");

                            if (status && status.value === "Verified") {

                                if (!confirm(
                                        "You have selected 'Verified'.\n\n" +
                                        "Once this purchase is saved as Verified, it cannot be edited in the future.\n\n" +
                                        "Do you want to continue?"
                                    )) {
                                    e.preventDefault();
                                }

                            } else if (status && status.value === "Cancelled") {

                                if (!confirm(
                                        "You have selected 'Cancelled'.\n\n" +
                                        "Once this purchase is saved as Cancelled, it cannot be edited in the future.\n\n" +
                                        "Do you want to continue?"
                                    )) {
                                    e.preventDefault();
                                }

                            }


                        });

                    }
                });
        }

        /* =====================================================
           VIEW PURCHASE
        =====================================================*/
        function viewPurchase(id) {

            const modal = document.getElementById("viewPurchaseModal");
            const content = document.getElementById("viewPurchaseContent");

            modal.style.display = "block";

            content.innerHTML = "<p class='text-center'>Loading...</p>";

            fetch(`/decorluxe/presentation/admin/inventory/view_purchase_form.php?id=${id}`)
                .then(res => res.text())
                .then(html => content.innerHTML = html)
                .catch(() => content.innerHTML = "<p class='text-danger'>Load failed</p>");
        }

        /* =====================================================
           CLOSE MODAL
        =====================================================*/
        function closeModal(id) {

            document.getElementById(id).style.display = "none";

            if (id === "addPurchaseModal") pItemCount = 0;
            if (id === "editPurchaseModal") editItemCount = 0;
        }

        /* =====================================================
           LOAD SUPPLIER
        =====================================================*/
        function loadSupplier(selected = "") {

            fetch('/decorluxe/business/admin/suppliers/get_supplier.php')
                .then(res => res.json())
                .then(data => {

                    const selects = document.querySelectorAll("#supplier");

                    selects.forEach(select => {

                        select.innerHTML = '<option value="">Select Supplier</option>';

                        data.forEach(s => {

                            const opt = document.createElement("option");

                            opt.value = s.supplier_id;
                            opt.textContent = s.supplier_name;

                            if (selected == s.supplier_id) {
                                opt.selected = true;
                            }

                            select.appendChild(opt);
                        });
                    });
                });
        }

        /* =====================================================
           LOAD ITEMS
        =====================================================*/
        function loadItems(selectElement, selected = "") {

            fetch('/decorluxe/business/admin/inventory/get_items.php')
                .then(res => res.json())
                .then(data => {

                    selectElement.innerHTML = '<option value="">Select Item</option>';

                    data.forEach(item => {

                        const opt = document.createElement("option");

                        opt.value = item.item_id;
                        opt.textContent = item.itemName;

                        if (selected == item.item_id) {
                            opt.selected = true;
                        }

                        selectElement.appendChild(opt);
                    });
                });
        }

        /* =====================================================
           ADD PURCHASE ROW
        =====================================================*/
        function addPurchaseItem() {

            pItemCount++;

            const container = document.getElementById("purchaseItemContainer");

            const div = document.createElement("div");

            div.className = "itemField border p-3 mb-2 rounded bg-light";

            div.innerHTML = rowHTML(pItemCount, false);

            container.appendChild(div);

            loadItems(div.querySelector(".item"));

            bindRowEvents(div, updatePurchaseTotal, validateDuplicateItems);
        }

        /* =====================================================
           ADD EDIT ROW
        =====================================================*/
        function addEditPurchaseItem() {

            editItemCount++;

            const container = document.getElementById("editPurchaseItemContainer");

            const div = document.createElement("div");

            div.className = "itemField border p-3 mb-2 rounded bg-light";

            div.innerHTML = rowHTML(editItemCount, true);

            container.appendChild(div);

            loadItems(div.querySelector(".item"));

            bindRowEvents(div, updateEditPurchaseTotal, validateEditDuplicateItems);
        }

        /* =====================================================
           ROW HTML
        =====================================================*/
        function rowHTML(no, isEdit = false) {

            return `
<h6>Item ${no}</h6>

<select name="item_id[]" class="form-select mb-2 item"></select>

<div class="row">

<div class="col-md-3">
<input type="number" name="qty[]" class="form-control qty" placeholder="Qty">
</div>

<div class="col-md-3">
<input type="number" name="unit_price[]" class="form-control price" placeholder="Unit Price">
</div>

<div class="col-md-3">
<select name="discount_type[]" class="form-select discount_type">
<option value="">Discount Type</option>
<option value="percent">%</option>
<option value="amount">Amount</option>
</select>
</div>

<div class="col-md-3">
<input type="number" name="discount[]" class="form-control discount" placeholder="Discount">
</div>

</div>

<div class="mt-2">
<label>Sub Total</label>
<input type="number" name="sub_total[]" class="form-control subtotal" readonly>
</div>

<button type="button"
class="btn btn-danger btn-sm mt-2"
onclick="${isEdit ? 'removeEditPurchaseItem(this)' : 'removePurchaseItem(this)'}">
Remove
</button>
`;
        }

        /* =====================================================
           BIND ROW EVENTS
        =====================================================*/
        function bindRowEvents(div, totalFn, dupFn) {

            div.querySelectorAll("input,select").forEach(el => {
                el.addEventListener("input", totalFn);
                el.addEventListener("change", totalFn);
            });

            div.querySelector(".item").addEventListener("change", dupFn);
        }

        /* =====================================================
           DUPLICATE CHECK ADD
        =====================================================*/
        function validateDuplicateItems() {

            duplicateCheck("#addPurchaseContent .item");
        }

        /* =====================================================
           DUPLICATE CHECK EDIT
        =====================================================*/
        function validateEditDuplicateItems() {

            duplicateCheck("#editPurchaseContent .item");
        }

        /* =====================================================
           COMMON DUPLICATE CHECK
        =====================================================*/
        function duplicateCheck(selector) {

            let used = [];
            let duplicate = false;

            document.querySelectorAll(selector).forEach(sel => {

                if (sel.value) {

                    if (used.includes(sel.value)) {
                        duplicate = true;
                        sel.value = "";
                    } else {
                        used.push(sel.value);
                    }
                }
            });

            if (duplicate) {
                alert("Same item cannot be selected twice.");
            }
        }

        /* =====================================================
           UPDATE ADD TOTAL
        =====================================================*/
        function updatePurchaseTotal() {

            calculateTotal("#addPurchaseContent");
        }

        /* =====================================================
           UPDATE EDIT TOTAL
        =====================================================*/
        function updateEditPurchaseTotal() {

            calculateTotal("#editPurchaseContent");
        }

        /* =====================================================
           COMMON CALCULATION
        =====================================================*/
        function calculateTotal(area) {

            let subtotal = 0;

            document.querySelectorAll(`${area} .itemField`).forEach(row => {

                let qty = parseFloat(row.querySelector(".qty").value) || 0;
                let price = parseFloat(row.querySelector(".price").value) || 0;
                let discount = parseFloat(row.querySelector(".discount").value) || 0;
                let type = row.querySelector(".discount_type").value;

                let rowTotal = qty * price;

                if (type === "percent") {
                    rowTotal -= rowTotal * discount / 100;
                }

                if (type === "amount") {
                    rowTotal -= discount;
                }

                if (rowTotal < 0) rowTotal = 0;

                row.querySelector(".subtotal").value = rowTotal.toFixed(2);

                subtotal += rowTotal;
            });

            const sub = document.querySelector(`${area} #purchase_subtotal`);
            const total = document.querySelector(`${area} #total`);
            const fd = document.querySelector(`${area} #final_discount`);
            const ft = document.querySelector(`${area} #final_discount_type`);

            if (sub) sub.value = subtotal.toFixed(2);

            let grand = subtotal;

            let finalDiscount = parseFloat(fd?.value) || 0;
            let finalType = ft?.value || "";

            if (finalType === "percent") {
                grand -= subtotal * finalDiscount / 100;
            }

            if (finalType === "amount") {
                grand -= finalDiscount;
            }

            if (grand < 0) grand = 0;

            if (total) total.value = grand.toFixed(2);

            discountLock(area);
        }

        /* =====================================================
           DISCOUNT LOCK
        =====================================================*/
        function discountLock(area) {

            let itemUsed = false;

            document.querySelectorAll(`${area} .itemField`).forEach(row => {

                let val = parseFloat(row.querySelector(".discount").value) || 0;
                let type = row.querySelector(".discount_type").value;

                if (val > 0 && type !== "") {
                    itemUsed = true;
                }
            });

            const fd = document.querySelector(`${area} #final_discount`);
            const ft = document.querySelector(`${area} #final_discount_type`);

            let finalVal = parseFloat(fd?.value) || 0;
            let finalType = ft?.value || "";

            let finalUsed = finalVal > 0 && finalType !== "";

            if (fd) fd.disabled = itemUsed;
            if (ft) ft.disabled = itemUsed;

            document.querySelectorAll(`${area} .itemField`).forEach(row => {

                row.querySelector(".discount").disabled = finalUsed;
                row.querySelector(".discount_type").disabled = finalUsed;
            });
        }

        /* =====================================================
           BIND FINAL EVENTS
        =====================================================*/
        function bindFinalDiscountEvents() {

            bindFinal("#addPurchaseContent", updatePurchaseTotal);
        }

        function bindEditFinalDiscountEvents() {

            bindFinal("#editPurchaseContent", updateEditPurchaseTotal);
        }

        function bindFinal(area, fn) {

            const fd = document.querySelector(`${area} #final_discount`);
            const ft = document.querySelector(`${area} #final_discount_type`);

            if (fd) fd.addEventListener("input", fn);
            if (ft) ft.addEventListener("change", fn);
        }

        /* =====================================================
           REMOVE ADD ROW
        =====================================================*/
        function removePurchaseItem(btn) {

            btn.closest(".itemField").remove();

            reorder("#addPurchaseContent .itemField");

            pItemCount = document.querySelectorAll("#addPurchaseContent .itemField").length;

            updatePurchaseTotal();
        }

        /* =====================================================
           REMOVE EDIT ROW
        =====================================================*/
        function removeEditPurchaseItem(btn) {

            btn.closest(".itemField").remove();

            reorder("#editPurchaseContent .itemField");

            editItemCount = document.querySelectorAll("#editPurchaseContent .itemField").length;

            updateEditPurchaseTotal();
        }

        /* =====================================================
           REORDER ITEMS
        =====================================================*/
        function reorder(selector) {

            document.querySelectorAll(selector).forEach((row, index) => {

                row.querySelector("h6").innerText = "Item " + (index + 1);
            });
        }
    </script>

    <script>
        document.getElementById("purchaseForm").addEventListener("submit", function(e) {

            const status = document.getElementById("purchase_status").value;

            if (status === "Verified") {

                if (!confirm("You have selected VERIFIED.\n\nAfter saving, this purchase cannot be edited in the future.\n\nDo you want to continue?")) {
                    e.preventDefault();
                }

            }

        });
    </script>
</body>

</html>