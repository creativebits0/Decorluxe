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
                                <h2 class="text-light fs-4 mb-0">Sales Management</h2>
                                <button class="btn btn-sm text-light d-flex justify-content-between align-items-center" style="background-color:#4CAF50;" onclick="openAddSales()">
                                    <i class="material-icons">&#xE147;</i> Add New Sales
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
                            <?php include_once '../../business/admin/sales_management_process.php'; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Modals (global, always available) -->
    <div id="addSalesModal" class="modal sales-modal">
        <div class="modal-dialog modal-dialog-centered modal-md ">
            <div class="modal-content purchaseModal" style="background-color:#aa8c2c;">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="form-head">ADD SALES</span>
                    <span class="close" onclick="closeModal('addSalesModal')">&times;</span>
                </div>
                <hr>
                <div id="addSalesContent"></div>
            </div>
        </div>
    </div>

    <div id="viewSalesModal" class="modal ">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content purchaseModal" style="background-color:#aa8c2c;">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="form-head">VIEW SALES</span>
                    <span class="close" onclick="closeModal('viewSalesModal')">&times;</span>
                </div>
                <hr>
                <div id="viewSalesContent"></div>
            </div>
        </div>
    </div>

    <div id="editSalesModal" class="modal ">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content purchaseModal" style="background-color:#aa8c2c;">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="form-head">EDIT SALES</span>
                    <span class="close" onclick="closeModal('editSalesModal')">&times;</span>
                </div>
                <hr>
                <div id="editSalesContent"></div>
            </div>
        </div>
    </div>
    <div id="returnSalesModal" class="modal ">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content " style="background-color:#aa8c2c;">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="form-head">RETURN SALES</span>
                    <span class="close" onclick="closeModal('returnSalesModal')">&times;</span>
                </div>
                <hr>
                <div id="returnSalesContent"></div>
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
        // ✅ Open Add Sales Modal
        function openAddSales() {
            const modal = document.getElementById("addSalesModal");
            const content = document.getElementById("addSalesContent");
            modal.style.display = "block";
            content.innerHTML = "<p class='text-center'>Loading...</p>";

            fetch("/decorluxe/presentation/admin/inventory/add_sales_form.php")
                .then(res => res.text())
                .then(html => {
                    content.innerHTML = html;

                    loadSupplier();
                });
        }

        // ✅ Open Edit Sales Modal
        // function editSales(id) {
        //     const modal = document.getElementById("editSalesModal");
        //     const content = document.getElementById("editSalesContent");
        //     modal.style.display = "block";
        //     content.innerHTML = "<p class='text-center'>Loading...</p>";

        //     fetch(`/decorluxe/presentation/admin/inventory/edit_sales_form.php?id=${id}`)
        //         .then(res => res.text())
        //         .then(html => {
        //             content.innerHTML = html;

        //             loadSupplier();
        //         });
        // }
        function editSales(id) {

            const modal = document.getElementById("editSalesModal");
            const content = document.getElementById("editSalesContent");

            modal.style.display = "block";
            content.innerHTML = "<p class='text-center'>Loading...</p>";

            fetch(`/decorluxe/presentation/admin/inventory/edit_sales_form.php?id=${id}`)
                .then(res => res.text())
                .then(html => {

                    content.innerHTML = html;

                    // load selected items after html inserted
                    content.querySelectorAll(".item").forEach(select => {

                        let selected = select.dataset.selected;

                        loadItems(select, selected);

                    });

                    pItemCount =
                        content.querySelectorAll(".itemField").length;

                    updateSalesTotal();

                });
        }


        // ✅ Open View Sales Modal
        function viewSales(id) {
            const modal = document.getElementById("viewSalesModal");
            const content = document.getElementById("viewSalesContent");
            modal.style.display = "block";
            content.innerHTML = "<p class='text-center'>Loading...</p>";

            fetch(`/decorluxe/presentation/admin/inventory/view_sales_form.php?id=${id}`)
                .then(res => res.text())
                .then(html => content.innerHTML = html)
                .catch(() => content.innerHTML = "<p class='text-danger'>Failed to load data.</p>");
        }



        // ✅ Open return Sales Modal
        function returnSales(id) {
            const modal = document.getElementById("returnSalesModal");
            const content = document.getElementById("returnSalesContent");
            modal.style.display = "block";
            content.innerHTML = "<p class='text-center'>Loading...</p>";

            fetch(`/decorluxe/presentation/admin/inventory/return_sales_form.php?id=${id}`)
                .then(res => res.text())
                .then(html => content.innerHTML = html);


        }
        // ✅ Universal Close Function
        function closeModal(id) {
            document.getElementById(id).style.display = "none";
        }
    </script>


    <script>
        let pItemCount = 0;

        /* -------------------------------
           OPEN EDIT SALES
        --------------------------------*/
        function editSales(id) {

            const modal = document.getElementById("editSalesModal");
            const content = document.getElementById("editSalesContent");

            modal.style.display = "block";
            content.innerHTML = "<p class='text-center'>Loading...</p>";

            fetch(`/decorluxe/presentation/admin/inventory/edit_sales_form.php?id=${id}`)
                .then(res => res.text())
                .then(html => {

                    content.innerHTML = html;

                    pItemCount = 0;

                    content.querySelectorAll(".itemField").forEach(row => {

                        pItemCount++;

                        const select = row.querySelector(".item");
                        const selected = select.dataset.selected || "";

                        loadItems(select, selected);

                        bindRowEvents(row);
                    });

                    refreshAllItemDropdowns();
                    updateSalesTotal();
                });
        }

        /* -------------------------------
           ADD ITEM ROW
        --------------------------------*/
        function addSalesItem() {

            const existingRows =
                document.querySelectorAll("#salesItemContainer .itemField").length;

            if (existingRows > 0) {
                // if already row exists, only add if previous selected
                const emptySelect = [...document.querySelectorAll(".item")]
                    .find(s => s.value === "");

                if (emptySelect) {
                    alert("Select item before adding new row.");
                    emptySelect.focus();
                    return;
                }
            }

            pItemCount++;

            const container = document.getElementById("salesItemContainer");

            const div = document.createElement("div");

            div.className =
                "itemField border p-3 mb-2 rounded bg-light";

            div.innerHTML = `
        <h6>Item ${pItemCount}</h6>

        <select name="item_id[]" class="form-select mb-2 item"></select>

        <div class="row g-2">
            <div class="col-md-3">
                <input type="number" step="0.01"
                    name="qty[]" class="form-control qty"
                    placeholder="Qty">
            </div>

            <div class="col-md-3">
                <input type="number" step="0.01"
                    name="unit_price[]" class="form-control price"
                    placeholder="Unit Price">
            </div>

            <div class="col-md-3">
                <select class="form-select discount_type">
                    <option value="">Discount Type</option>
                    <option value="percent">%</option>
                    <option value="amount">Amount</option>
                </select>
            </div>

            <div class="col-md-3">
                <input type="number" step="0.01"
                    name="discount[]" class="form-control discount"
                    placeholder="Discount">
            </div>
        </div>

        <div class="mt-2">
            <label>Sub Total</label>
            <input type="number" step="0.01"
                name="sub_total[]"
                class="form-control subtotal"
                readonly>
        </div>

        <button type="button"
            onclick="removeSalesItem(this)"
            class="btn btn-danger btn-sm mt-2">
            Remove
        </button>
    `;

            container.appendChild(div);

            const select = div.querySelector(".item");
            loadItems(select);

            bindRowEvents(div);
        }

        /* -------------------------------
           BIND EVENTS
        --------------------------------*/
        function bindRowEvents(row) {

            row.querySelectorAll(
                ".qty,.price,.discount,.discount_type"
            ).forEach(el => {
                el.addEventListener("input", updateSalesTotal);
                el.addEventListener("change", updateSalesTotal);
            });

            row.querySelector(".item")
                .addEventListener("change", function() {

                    if (isDuplicateItem(this)) {
                        alert("Same item cannot be selected twice.");
                        this.value = "";
                    }

                    refreshAllItemDropdowns();
                    updateSalesTotal();
                });
        }

        /* -------------------------------
           REMOVE ITEM
        --------------------------------*/
        function removeSalesItem(btn) {

            btn.closest(".itemField").remove();

            reorderItems();
            refreshAllItemDropdowns();
            updateSalesTotal();
        }

        function reorderItems() {

            const rows =
                document.querySelectorAll(".itemField");

            rows.forEach((row, index) => {
                row.querySelector("h6").innerText =
                    "Item " + (index + 1);
            });

            pItemCount = rows.length;
        }

        /* -------------------------------
           DUPLICATE CHECK
        --------------------------------*/
        function isDuplicateItem(currentSelect) {

            let val = currentSelect.value;

            if (val === "") return false;

            let count = 0;

            document.querySelectorAll(".item").forEach(sel => {
                if (sel.value === val) count++;
            });

            return count > 1;
        }

        /* -------------------------------
           LOAD ITEMS
        --------------------------------*/
        function loadItems(selectElement, selected = "") {

            fetch('/decorluxe/business/admin/inventory/get_items.php')
                .then(res => res.json())
                .then(data => {

                    selectElement.innerHTML =
                        '<option value="">Select Item</option>';

                    data.forEach(item => {

                        const opt =
                            document.createElement("option");

                        opt.value = item.item_id;
                        opt.textContent = item.itemName;

                        if (selected == item.item_id) {
                            opt.selected = true;
                        }

                        selectElement.appendChild(opt);
                    });

                    refreshAllItemDropdowns();
                });
        }

        /* -------------------------------
           HIDE ALREADY SELECTED ITEMS
        --------------------------------*/
        function refreshAllItemDropdowns() {

            const selected = [];

            document.querySelectorAll(".item").forEach(sel => {
                if (sel.value !== "") selected.push(sel.value);
            });

            document.querySelectorAll(".item").forEach(sel => {

                const current = sel.value;

                [...sel.options].forEach(opt => {

                    if (opt.value === "") {
                        opt.hidden = false;
                        return;
                    }

                    if (
                        selected.includes(opt.value) &&
                        opt.value !== current
                    ) {
                        opt.hidden = true;
                    } else {
                        opt.hidden = false;
                    }
                });
            });
        }

        /* -------------------------------
           CALCULATE TOTAL
        --------------------------------*/
        function updateSalesTotal() {

            let subtotal = 0;
            let itemDiscountUsed = false;

            document.querySelectorAll(".itemField")
                .forEach(row => {

                    const qty =
                        parseFloat(row.querySelector(".qty").value) || 0;

                    const price =
                        parseFloat(row.querySelector(".price").value) || 0;

                    const dis =
                        parseFloat(row.querySelector(".discount").value) || 0;

                    const type =
                        row.querySelector(".discount_type").value;

                    let rowTotal = qty * price;

                    if (dis > 0) itemDiscountUsed = true;

                    if (type === "percent") {
                        rowTotal -= rowTotal * dis / 100;
                    }

                    if (type === "amount") {
                        rowTotal -= dis;
                    }

                    if (rowTotal < 0) rowTotal = 0;

                    row.querySelector(".subtotal").value =
                        rowTotal.toFixed(2);

                    subtotal += rowTotal;
                });

            document.getElementById("sales_subtotal").value =
                subtotal.toFixed(2);

            applyFinalDiscount(itemDiscountUsed);
        }

        /* -------------------------------
           FINAL DISCOUNT
        --------------------------------*/
        function applyFinalDiscount(itemDiscountUsed = false) {

            const sub =
                parseFloat(
                    document.getElementById("sales_subtotal").value
                ) || 0;

            const finalDiscount =
                parseFloat(
                    document.getElementById("final_discount").value
                ) || 0;

            const type =
                document.getElementById("final_discount_type").value;

            let total = sub;

            /* if any item discount used -> stop total discount */
            if (itemDiscountUsed) {

                document.getElementById("final_discount").value = "";
                document.getElementById("final_discount_type").value = "";

                total = sub;

            } else {

                if (type === "percent") {
                    total -= sub * finalDiscount / 100;
                }

                if (type === "amount") {
                    total -= finalDiscount;
                }
            }

            if (total < 0) total = 0;

            document.getElementById("total").value =
                total.toFixed(2);
        }

        /* -------------------------------
           FINAL DISCOUNT EVENTS
        --------------------------------*/
        document.addEventListener("input", function(e) {

            if (
                e.target.id === "final_discount" ||
                e.target.id === "final_discount_type"
            ) {
                updateSalesTotal();
            }
        });
    </script>
</body>

</html>