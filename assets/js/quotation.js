let areaIndex = 0;

function addArea() {
    let currentAreas = document.querySelectorAll('.areaBox').length;

    let html = `
<div class="areaBox border p-3 mb-3 rounded shadow-sm">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <input type="text"
               name="area_name[]"
               placeholder="Area Name"
               class="form-control me-2">

        <button type="button"
                class="btn btn-danger"
                onclick="
                        this.closest('.areaBox').remove();
                        refreshAllCalculations();
                        ">
            X
        </button>
    </div>

    <table class="table table-bordered align-middle">
        <thead class="table-dark">
            <tr>
                <th>Item</th>
                <th width="120">Qty</th>
                <th width="150">Unit Price</th>
                <th width="150">Total</th>
                <th width="70"></th>
            </tr>
        </thead>

        <tbody class="itemBody"></tbody>
    </table>

    <button type="button"
            class="btn btn-sm btn-success mb-3"
            onclick="addRow(this)">
        + Add Item
    </button>

    <!-- PERFECT GRID ROW -->
    <div class="row g-3">

        <div class="col-md-3">
            <label class="form-label small">Total Material Cost</label>

            <input type="number"
                   name="material_cost[]"
                   class="form-control material-cost"
                   readonly>
        </div>

        <div class="col-md-3">
            <label class="form-label small">Labour / Installation</label>

            <input type="number"
                   name="labour_charge[]"
                   class="form-control labour-charge"
                   value="0"
                   oninput="updateAreaTotal(this.closest('.areaBox'))">
        </div>

        <div class="col-md-3">
            <label class="form-label small">Transport</label>

            <input type="number"
                   name="transport[]"
                   class="form-control transport"
                   value="0"
                   oninput="updateAreaTotal(this.closest('.areaBox'))">
        </div>

        <div class="col-md-3">
            <label class="form-label small">Other Charges</label>

            <input type="number"
                   name="other_charges[]"
                   class="form-control other-charges"
                   value="0"
                   oninput="updateAreaTotal(this.closest('.areaBox'))">
        </div>
      <div class="col-md-3">
            <label class="form-label small">Area Sub Total</label>

            <input type="number"
                   name="area_total[]"
                   class="form-control area-total"
                   readonly>
        </div>
        <div class="col-md-9">
            <label class="form-label small">Description</label>

            <textarea name="area_description[]"
                      class="form-control"
                      rows="2"></textarea>
        </div>

    </div>

</div>
`;

    document.getElementById("areaContainer").insertAdjacentHTML("beforeend", html);
}
function addRow(btn) {
    let areaBox = btn.closest('.areaBox');
    // Find the index of this areaBox relative to others
    let allAreas = Array.from(document.querySelectorAll('.areaBox'));
    let currentIndex = allAreas.indexOf(areaBox);

    let tbody = areaBox.querySelector('.itemBody');
    let row = `
        <tr>
   <td>
    <select
        name="item_id[${currentIndex}][]"
        class="form-control item-name"
        onchange="setUnitPrice(this)">
        ${window.itemOptions}
    </select>
</td>
            <td><input type="number" name="qty[${currentIndex}][]" class="form-control qty" oninput="calcRow(this)"></td>
            <td><input type="number" name="unit_price[${currentIndex}][]" class="form-control price" oninput="calcRow(this)"></td>
            <td><input type="number" name="sub_total[${currentIndex}][]" class="form-control total" readonly></td>
            <td><button type="button" class="btn btn-danger btn-sm" onclick="this.closest('tr').remove(); updateAreaTotal(this.closest('.areaBox'))">X</button></td>
        </tr>`;
    tbody.insertAdjacentHTML("beforeend", row);
}



function calcRow(el) {

    let row = el.closest("tr");

    let qty = parseFloat(row.querySelector(".qty")?.value) || 0;
    let price = parseFloat(row.querySelector(".price")?.value) || 0;

    let total = qty * price;

    row.querySelector(".total").value = total.toFixed(2);

    updateMaterialCost(row.closest(".areaBox"));
    updateAreaTotal(row.closest(".areaBox"));
}


// Update the area Total
function updateAreaTotal(area) {
    let materialTotal = 0;
    // Material total = qty × unit price totals
    area.querySelectorAll('.total').forEach(t => {
        materialTotal += parseFloat(t.value) || 0;
    });
    // Extra charges
    let labour =
        parseFloat(area.querySelector('.labour-charge')?.value) || 0;
    let transport =
        parseFloat(area.querySelector('.transport')?.value) || 0;
    let other =
        parseFloat(area.querySelector('.other-charges')?.value) || 0;
    // Final area total
    let finalTotal =
        materialTotal +
        labour +
        transport +
        other;
    // Set area total
    area.querySelector('.area-total').value =
        finalTotal.toFixed(2);
    // Update full quotation total
    updateGrandTotal();
}

function updateGrandTotal() {
    let grand = 0;
    document.querySelectorAll('.area-total').forEach(t => {
        grand += parseFloat(t.value) || 0;
    });
    const totalInput = document.getElementById('total');
    if (totalInput) {
        totalInput.value = grand.toFixed(2);
    }
}

function initQuotationUI(mode = "add") {

    // 1. Load items
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

            window.itemOptions = options;

            console.log("Items loaded");
        });

    // 2. Load clients ONLY for ADD
    if (mode === "add") {
        fetch('/decorluxe/business/admin/clients/get_clients.php')
            .then(res => res.json())
            .then(data => {
                const select = document.getElementById('client');
                if (!select) return;

                select.innerHTML = '<option value="">Select Client</option>';

                data.forEach(client => {
                    const opt = document.createElement('option');
                    opt.value = client.client_id;
                    opt.textContent = client.fullname;
                    select.appendChild(opt);
                });
            });
    }

    // 3. Always enable addArea button
    document.querySelectorAll('.addAreaBtn').forEach(btn => {
        btn.disabled = false;
    });
}

function refreshAllCalculations() {

    document.querySelectorAll('.areaBox').forEach(area => {

        // Recalculate every row
        area.querySelectorAll('tr').forEach(row => {

            let qty =
                parseFloat(row.querySelector('.qty')?.value) || 0;

            let price =
                parseFloat(row.querySelector('.price')?.value) || 0;

            let totalInput = row.querySelector('.total');

            if (totalInput) {
                totalInput.value = (qty * price).toFixed(2);
            }
        });

        // Recalculate area total
        updateAreaTotal(area);
    });

    // Recalculate grand total
    updateGrandTotal();
}

function updateMaterialCost(areaBox) {

    let rows = areaBox.querySelectorAll("tbody tr");

    let materialTotal = 0;

    rows.forEach(row => {

        let qty = parseFloat(row.querySelector(".qty")?.value) || 0;
        let price = parseFloat(row.querySelector(".price")?.value) || 0;

        materialTotal += qty * price;
    });

    let materialInput = areaBox.querySelector(".material-cost");

    if (materialInput) {
        materialInput.value = materialTotal.toFixed(2);
    }
}

// function setUnitPrice(select) {

//     const option = select.options[select.selectedIndex];

//     if (!option) return;

//     const price = option.dataset.price || 0;

//     const row = select.closest("tr");

//     const priceInput = row.querySelector(".price");

//     priceInput.value = price;

//     calcRow(priceInput);
// }


// function setUnitPrice(select) {

//     console.log(select);

//     const option = select.options[select.selectedIndex];

//     console.log(option);

//     console.log(option.dataset);

//     console.log(option.dataset.price);

//     const row = select.closest("tr");

//     const priceInput = row.querySelector(".price");

//     priceInput.value = option.dataset.price || 0;

//     calcRow(priceInput);
// }

function setUnitPrice(select) {

    const option = select.options[select.selectedIndex];

    console.log(option.outerHTML);
    console.log("Price =", option.getAttribute("data-price"));

    const row = select.closest("tr");

    row.querySelector(".price").value =
        option.getAttribute("data-price") || 0;

    calcRow(row.querySelector(".price"));
}