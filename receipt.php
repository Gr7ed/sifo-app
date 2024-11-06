<?php
require 'vendor/autoload.php';
use Dompdf\Dompdf;

include 'config.php';

if (isset($_GET['transaction_id'])) {
    $transaction_id = $_GET['transaction_id'];

    // Fetch the donation details from the database
    $stmt = $pdo->prepare('SELECT * FROM snap_donations WHERE transaction_id = ?');
    $stmt->execute([$transaction_id]);
    $donation = $stmt->fetch();

    if ($donation) {
        $name = $donation['name'];
        $email = $donation['email'];
        $amount = $donation['amount'];
        $date = $donation['created_at'];

        // Generate the HTML for the receipt
        $html = "
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; }
                h1 { color: #4CAF50; }
                p { font-size: 16px; line-height: 1.6; }
                .main-content { margin: 20px; padding: 20px; border: 1px solid #ddd; border-radius: 5px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); }
            </style>
        </head>
        <body>
            <div class='main-content'>
                <h1>Thank You for Your Donation!</h1>
                <p>Dear $name,</p>
                <p>Thank you for your generous donation of \$$amount.</p>
                <p>Transaction ID: $transaction_id</p>
                <p>Date: $date</p>
                <p>Your support is greatly appreciated!</p>
                <p>Best Regards,</p>
                <p>The SIFO Team</p>
            </div>
        </body>
        </html>";

        // Initialize dompdf and load the HTML
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to browser
        $dompdf->stream("receipt_$transaction_id.pdf", array("Attachment" => 0));
    } else {
        echo "Invalid transaction ID.";
    }
} else {
    echo "No transaction ID provided.";
}
?>