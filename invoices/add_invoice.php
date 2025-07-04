<?php
include '../includes/header.php';
include '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $client_id = $_POST['client_id'];
    $subtotal = $_POST['subtotal'];
    $tax = $_POST['tax'];
    $grand_total = $_POST['grand_total'];

    // Insert invoice
    $stmt = $conn->prepare("INSERT INTO invoices (client_id, subtotal, tax, grand_total) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iddd", $client_id, $subtotal, $tax, $grand_total);
    $stmt->execute();
    $invoice_id = $stmt->insert_id;

    // Insert invoice items
    $item_names = $_POST['item_name'];
    $quantities = $_POST['quantity'];
    $prices = $_POST['price'];

    $item_stmt = $conn->prepare("INSERT INTO invoice_items (invoice_id, item_name, qty, price, total) VALUES (?, ?, ?, ?, ?)");

    foreach ($item_names as $index => $item_name) {
        $qty = $quantities[$index];
        $price = $prices[$index];
        $total = $qty * $price;
        $item_stmt->bind_param("isidd", $invoice_id, $item_name, $qty, $price, $total);
        $item_stmt->execute();
    }

    header("Location: view_invoice.php?id=" . $invoice_id);
    exit;
}
?>

<div class="container mt-4">
    <h2 class="mb-4">Create New Invoice</h2>

    <form method="POST" action="">
        <!-- 🔹 Select Client -->
        <div class="mb-3">
            <label for="client_id">Select Client</label>
            <select name="client_id" class="form-control" required>
                <option value="">-- Select Client --</option>
                <?php
                $result = $conn->query("SELECT id, name FROM clients ORDER BY name ASC");
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='" . $row['id'] . "'>" . htmlspecialchars($row['name']) . "</option>";
                }
                ?>
            </select>
        </div>

        <!-- 🔹 Item Table -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Item Name</th>
                    <th>Qty</th>
                    <th>Price</th>
                    <th>Total</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="invoice-items">
                <tr class="item-row">
                    <td><input type="text" name="item_name[]" class="form-control" required></td>
                    <td><input type="number" name="quantity[]" class="form-control quantity" value="1" required></td>
                    <td><input type="number" name="price[]" class="form-control price" step="0.01" required></td>
                    <td><input type="text" class="form-control total" readonly></td>
                    <td><button type="button" class="btn btn-danger btn-sm remove-item">X</button></td>
                </tr>
            </tbody>
        </table>

        <!-- 🔘 Add More Button -->
        <div class="mb-3">
            <button type="button" id="add-item" class="btn btn-secondary">+ Add Item</button>
        </div>

        <!-- 🔹 Totals -->
        <div class="mb-3 row">
            <label class="col-sm-2 col-form-label">Subtotal</label>
            <div class="col-sm-10">
                <input type="text" id="subtotal" name="subtotal" class="form-control" readonly>
            </div>
        </div>

        <div class="mb-3 row">
            <label class="col-sm-2 col-form-label">Tax (%)</label>
            <div class="col-sm-10">
                <input type="number" id="tax" name="tax" class="form-control" value="0">
            </div>
        </div>

        <div class="mb-3 row">
            <label class="col-sm-2 col-form-label">Grand Total</label>
            <div class="col-sm-10">
                <input type="text" id="grand_total" name="grand_total" class="form-control" readonly>
            </div>
        </div>

        <button type="submit" class="btn btn-success">Save Invoice</button>
    </form>
</div>

<!-- 🔹 Auto-calculate Totals & Add/Remove Items -->
<script>
function calculateTotals() {
    let subtotal = 0;
    document.querySelectorAll('.item-row').forEach(row => {
        const qty = parseFloat(row.querySelector('.quantity').value) || 0;
        const price = parseFloat(row.querySelector('.price').value) || 0;
        const total = qty * price;
        row.querySelector('.total').value = total.toFixed(2);
        subtotal += total;
    });

    document.getElementById('subtotal').value = subtotal.toFixed(2);
    const tax = parseFloat(document.getElementById('tax').value) || 0;
    const grandTotal = subtotal + (subtotal * tax / 100);
    document.getElementById('grand_total').value = grandTotal.toFixed(2);
}

// 🔘 Add new item row
document.getElementById('add-item').addEventListener('click', function () {
    const tbody = document.getElementById('invoice-items');
    const newRow = document.createElement('tr');
    newRow.className = 'item-row';
    newRow.innerHTML = `
        <td><input type="text" name="item_name[]" class="form-control" required></td>
        <td><input type="number" name="quantity[]" class="form-control quantity" value="1" required></td>
        <td><input type="number" name="price[]" class="form-control price" step="0.01" required></td>
        <td><input type="text" class="form-control total" readonly></td>
        <td><button type="button" class="btn btn-danger btn-sm remove-item">X</button></td>
    `;
    tbody.appendChild(newRow);
});

// ❌ Remove item row
document.addEventListener('click', function (e) {
    if (e.target.classList.contains('remove-item')) {
        e.target.closest('tr').remove();
        calculateTotals();
    }
});

// 🔁 Recalculate on input change
document.addEventListener('input', function (e) {
    if (
        e.target.classList.contains('quantity') ||
        e.target.classList.contains('price') ||
        e.target.id === 'tax'
    ) {
        calculateTotals();
    }
});

document.addEventListener('DOMContentLoaded', calculateTotals);
</script>

<?php include '../includes/footer.php'; ?>
