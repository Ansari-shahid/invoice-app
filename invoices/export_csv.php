<?php
include '../includes/db.php';
header('Content-Type: text/csv');
header('Content-Disposition: attachment;filename="invoices.csv"');

$output = fopen('php://output', 'w');
fputcsv($output, ['Invoice #', 'Client', 'Total', 'Date']);

$sql = "SELECT invoices.invoice_number, clients.name AS client_name, invoices.grand_total, invoices.created_at 
        FROM invoices 
        JOIN clients ON invoices.client_id = clients.id 
        ORDER BY invoices.created_at DESC";

$result = $conn->query($sql);
while ($row = $result->fetch_assoc()) {
    fputcsv($output, [
        $row['invoice_number'],
        $row['client_name'],
        number_format($row['grand_total'], 2),
        date('d M Y', strtotime($row['created_at']))
    ]);
}
fclose($output);
exit;
