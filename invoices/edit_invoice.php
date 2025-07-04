<?php
include '../includes/db.php';
include '../includes/header.php';
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: /login.php");
    exit;
}

$invoice_id = $_GET['id'];
$invoice = $conn->query("SELECT * FROM invoices WHERE id = $invoice_id")->fetch_assoc();
$items = $conn->query("SELECT * FROM invoice_items WHERE invoice_id = $invoice_id");
?>

<h2>Edit Invoice #<?= $invoice['invoice_number'] ?></h2>

<form action="update_invoice.php" method="POST">
    <input type="hidden" name="invoice_id" value="<?= $invoice_id ?>">

    <div class="mb-3">
        <label>Client</label>
        <select name="client_id" class="form-select" required>
            <?php
            $clients = $conn->query("SELECT * FROM clients");
            while($c = $clients->fetch_assoc()):
            ?>
                <option value="<?= $c['id']; ?>" <?= ($c['id'] == $invoice['client_id']) ? 'selected' : '' ?>>
                    <?= $c['name']; ?>
                </option>
            <?php endwhile; ?>
        </select>
    </div>

    <div id="item-rows">
        <?php while($item = $items->fetch_assoc()): ?>
        <div class="row g-3 align-items-center mb-2">
            <div class="col"><input type="text" name="item_name[]" class="form-control" value="<?= $item['item_name'] ?>" required></div>
            <div class="col"><input type="number" name="qty[]" class="form-control qty" value="<?= $item['qty'] ?>" required></div>
            <div class="col"><input type="number" name="price[]" class="form-control price" value="<?= $item['price'] ?>" required></div>
            <div class="col"><input type="text" class="form-control total" readonly value="<?= number_format($item['total'], 2) ?>"></div>
            <div class="col-auto">
                <button type="button" class="btn btn-danger" onclick="removeRow(this)">X</button>
            </div>
        </div>
        <?php endwhile; ?>
    </div>

    <button type="button" class="btn btn-secondary mb-3" onclick="addRow()">Add Item</button>

    <div class="mb-3">
        <label>Grand Total (₹)</label>
        <input type="text" name="grand_total" id="grand_total" class="form-control" readonly>
    </div>

    <button type="submit" class="btn btn-primary">Update Invoice</button>
</form>

<?php include '../includes/footer.php'; ?>
