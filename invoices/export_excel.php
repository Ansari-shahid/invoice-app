<?php
include '../includes/db.php';
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=invoices.xls");

echo "<table border='1'>";
echo "<tr><th>Invoice #</th><th>Client</th><th>Total</th><th>Date</th></tr>";

$sql = "SELECT invoices.invoice_number, clients.name AS client_name, invoices.grand_total, invoices.created_at 
        FROM invoices 
        JOIN clients ON invoices.client_id = clients.id 
        ORDER BY invoices.created_at DESC";

$result = $conn->query($sql);
while ($row = $result->fetch_assoc()) {
    echo "<tr>
            <td>{$row['invoice_number']}</td>
            <td>{$row['client_name']}</td>
            <td>₹" . number_format($row['grand_total'], 2) . "</td>
            <td>" . date('d M Y', strtotime($row['created_at'])) . "</td>
          </tr>";
}

echo "</table>";
exit;
