<?php
require '../vendor/autoload.php'; // Dompdf + PHPMailer
include '../includes/db.php';

use PHPMailer\PHPMailer\PHPMailer;
use Dompdf\Dompdf;

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: /login.php");
    exit;
}

$invoice_id = $_GET['id'];

// Fetch invoice
$invoice = $conn->query("SELECT invoices.*, clients.name, clients.email, clients.address 
                         FROM invoices 
                         JOIN clients ON invoices.client_id = clients.id 
                         WHERE invoices.id = $invoice_id")->fetch_assoc();

$items = $conn->query("SELECT * FROM invoice_items WHERE invoice_id = $invoice_id");

// Generate PDF HTML
$html = '
<h2>Invoice #' . $invoice['invoice_number'] . '</h2>
<p><strong>Client:</strong> ' . $invoice['name'] . '<br>
<strong>Email:</strong> ' . $invoice['email'] . '<br>
<strong>Date:</strong> ' . date('d M Y', strtotime($invoice['created_at'])) . '</p>
<table width="100%" border="1" cellspacing="0" cellpadding="5">
<thead><tr><th>Item</th><th>Qty</th><th>Price</th><th>Total</th></tr></thead>
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
<tr><td colspan="3" align="right"><strong>Grand Total</strong></td>
<td>₹' . number_format($invoice['grand_total'], 2) . '</td></tr>
</tfoot></table>';

// Generate PDF
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->render();
$pdf = $dompdf->output();

// Email settings
$mail = new PHPMailer(true);

try {
    // SMTP setup (you can use Gmail SMTP or others)
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';         // Replace if needed
    $mail->SMTPAuth = true;
    $mail->Username = 'mohdshahidansari4990@gmail.com'; // Replace with your Gmail
    $mail->Password = 'lfst oxez odoi fgeh';     // Use App Password (not Gmail password)
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    // Sender and recipient
    $mail->setFrom('youremail@gmail.com', 'Invoice Generator');
    $mail->addAddress($invoice['email'], $invoice['name']);

    // Attach PDF
    $mail->addStringAttachment($pdf, "Invoice_{$invoice['invoice_number']}.pdf");

    // Email content
    $mail->isHTML(true);
    $mail->Subject = "Your Invoice #{$invoice['invoice_number']}";
    $mail->Body    = "Dear {$invoice['name']},<br><br>Please find attached your invoice.<br><br>Thanks,<br>Invoice Generator Team";

    $mail->send();
    echo "Invoice emailed to client successfully.";
} catch (Exception $e) {
    echo "Mailer Error: " . $mail->ErrorInfo;
}
