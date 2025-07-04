<?php
include '../includes/db.php';
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: /login.php");
    exit;
}

if (isset($_GET['id'])) {
    $invoice_id = $_GET['id'];

    // First delete invoice items
    $conn->query("DELETE FROM invoice_items WHERE invoice_id = $invoice_id");

    // Then delete invoice
    $conn->query("DELETE FROM invoices WHERE id = $invoice_id");

    header("Location: ../dashboard.php");
    exit;
} else {
    echo "Invalid invoice ID.";
}
