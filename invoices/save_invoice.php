<?php
include '../includes/db.php';

$client_id = $_POST['client_id'];
$item_names = $_POST['item_name'];
$qtys = $_POST['qty'];
$prices = $_POST['price'];
$grand_total = $_POST['grand_total'];

$invoice_number = 'INV' . time();

$conn->query("INSERT INTO invoices (client_id, invoice_number, grand_total) VALUES ('$client_id', '$invoice_number', '$grand_total')");
$invoice_id = $conn->insert_id;

for($i = 0; $i < count($item_names); $i++) {
    $name = $item_names[$i];
    $qty = $qtys[$i];
    $price = $prices[$i];
    $total = $qty * $price;

    $conn->query("INSERT INTO invoice_items (invoice_id, item_name, qty, price, total)
                  VALUES ('$invoice_id', '$name', '$qty', '$price', '$total')");
}

header("Location: view_invoice.php?id=$invoice_id");
exit;
