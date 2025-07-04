<?php
include '../includes/db.php';
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: /login.php");
    exit;
}

$invoice_id = $_POST['invoice_id'];
$client_id = $_POST['client_id'];
$item_names = $_POST['item_name'];
$qtys = $_POST['qty'];
$prices = $_POST['price'];
$grand_total = $_POST['grand_total'];

// Update main invoice
$conn->query("UPDATE invoices SET client_id = '$client_id', grand_total = '$grand_total' WHERE id = $invoice_id");

// Delete old items
$conn->query("DELETE FROM invoice_items WHERE invoice_id = $invoice_id");

// Insert new items
for ($i = 0; $i < count($item_names); $i++) {
    $name = $item_names[$i];
    $qty = $qtys[$i];
    $price = $prices[$i];
    $total = $qty * $price;

    $conn->query("INSERT INTO invoice_items (invoice_id, item_name, qty, price, total)
                  VALUES ('$invoice_id', '$name', '$qty', '$price', '$total')");
}

header("Location: view_invoice.php?id=$invoice_id");
exit;
