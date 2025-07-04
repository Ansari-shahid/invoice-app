<?php
require_once '../vendor/autoload.php';
include '../includes/db.php';

use Dompdf\Dompdf;

$invoice_id = $_GET['id'];

// Fetch invoice data
$invoice = $conn->query("SELECT invoices.*, clients.name, clients.email, clients.address 
                         FROM invoices 
                         JOIN clients ON invoices.client_id = clients.id 
                         WHERE invoices.id = $invoice_id")->fetch_assoc();

$items = $conn->query("SELECT * FROM invoice_items WHERE invoice_id = $invoice_id");

$html = '
<h2>Invoice #' . $invoice['invoice_number'] . '</h2>
<p><strong>Client:</strong> ' . $invoice['name'] . '<br>
<strong>Email:</strong> ' . $invoice['email'] . '<br>
<strong>Address:</strong> ' . nl2br($invoice['address']) . '<br>
<strong>Date:</strong> ' . date('d M Y', strtotime($invoice['created_at'])) . '</p>

<table width="100%" border="1" cellspacing="0" cellpadding="5">
<thead>
<tr style="background:#eee;">
    <th>Item</th>
    <th>Qty</th>
    <th>Price</th>
    <th>Total</th>
</tr>
</thead>
<tbody>';

while ($item = $items->fetch_assoc()) {
    $html .= '<tr>
        <td>' . $item['item_name'] . '</td>
        <td>' . $item['qty'] . '</td>
        <td>₹' . number_format($item['price'], 2) . '</td>
        <td>₹' . number_format($item['total'], 2) . '</td>
    </tr>';
}

$html .= '</tbody>
<tfoot>
<tr>
    <td colspan="3" align="right"><strong>Grand Total</strong></td>
    <td>₹' . number_format($invoice['grand_total'], 2) . '</td>
</tr>
</tfoot>
</table>';

// Generate PDF
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("invoice_{$invoice['invoice_number']}.pdf");
exit;
