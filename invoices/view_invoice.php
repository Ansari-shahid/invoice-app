<?php
include '../includes/db.php';
include '../includes/header.php';

$invoice_id = $_GET['id'];
$invoice = $conn->query("SELECT invoices.*, clients.name, clients.email, clients.address 
                         FROM invoices 
                         JOIN clients ON invoices.client_id = clients.id 
                         WHERE invoices.id = $invoice_id")->fetch_assoc();

$items = $conn->query("SELECT * FROM invoice_items WHERE invoice_id = $invoice_id");
?>

<h2>Invoice #<?= $invoice['invoice_number'] ?></h2>

<div class="card mb-4">
    <div class="card-body">
        <h5>Client: <?= $invoice['name'] ?></h5>
        <p>Email: <?= $invoice['email'] ?><br>
        Address: <?= nl2br($invoice['address']) ?></p>
        <p><strong>Date:</strong> <?= date('d M Y', strtotime($invoice['created_at'])) ?></p>
    </div>
</div>

<table class="table table-bordered">
    <thead class="table-light">
        <tr>
            <th>Item</th>
            <th>Qty</th>
            <th>Price</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
    <?php while($item = $items->fetch_assoc()): ?>
        <tr>
            <td><?= $item['item_name'] ?></td>
            <td><?= $item['qty'] ?></td>
            <td>₹<?= number_format($item['price'], 2) ?></td>
            <td>₹<?= number_format($item['total'], 2) ?></td>
        </tr>
    <?php endwhile; ?>
    </tbody>
    <tfoot>
        <tr>
            <th colspan="3" class="text-end">Grand Total</th>
            <th>₹<?= number_format($invoice['grand_total'], 2) ?></th>
        </tr>
    </tfoot>
</table>

<a href="javascript:window.print()" class="btn btn-primary">Print</a>
<a href="generate_pdf.php?id=<?= $invoice['id'] ?>" class="btn btn-warning">Download PDF</a>
<a href="email_invoice.php?id=<?= $invoice['id'] ?>" class="btn btn-danger">Email Invoice</a>



<?php include '../includes/footer.php'; ?>
