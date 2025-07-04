function addRow() {
    const container = document.getElementById("item-rows");

    const newRow = document.createElement("div");
    newRow.classList.add("row", "g-3", "align-items-center", "mb-2");

    newRow.innerHTML = `
        <div class="col"><input type="text" name="item_name[]" class="form-control" placeholder="Item Name" required></div>
        <div class="col"><input type="number" name="qty[]" class="form-control qty" placeholder="Qty" required></div>
        <div class="col"><input type="number" name="price[]" class="form-control price" placeholder="Price" required></div>
        <div class="col"><input type="text" class="form-control total" readonly placeholder="Total"></div>
        <div class="col-auto"><button type="button" class="btn btn-danger" onclick="removeRow(this)">X</button></div>
    `;

    container.appendChild(newRow);
    attachEvents(newRow);
    calculateTotal();
}

function removeRow(button) {
    button.closest(".row").remove();
    calculateTotal();
}

function attachEvents(row) {
    const qty = row.querySelector(".qty");
    const price = row.querySelector(".price");

    [qty, price].forEach(input => {
        input.addEventListener("input", () => calculateTotal());
    });
}

function calculateTotal() {
    let grandTotal = 0;
    const rows = document.querySelectorAll("#item-rows .row");

    rows.forEach(row => {
        const qty = row.querySelector(".qty").value || 0;
        const price = row.querySelector(".price").value || 0;
        const total = parseFloat(qty) * parseFloat(price);
        row.querySelector(".total").value = total.toFixed(2);
        grandTotal += total;
    });

    const grandInput = document.getElementById("grand_total");
    if (grandInput) grandInput.value = grandTotal.toFixed(2);
}

document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll("#item-rows .row").forEach(attachEvents);
    calculateTotal();
});
