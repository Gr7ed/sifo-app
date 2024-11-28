<?php
require_once __DIR__ . '/../../config/config.php';

if (!isset($_GET['snap_id'])) {
    die("Snap donation ID is missing.");
}

$snapId = $_GET['snap_id'];

// Fetch donation details
$stmt = $db->prepare("SELECT donor_name, amount, created_at FROM snap_donors WHERE snap_id = ?");
$stmt->execute([$snapId]);
$donation = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$donation) {
    die("Snap donation not found.");
}

include __DIR__ . '/../layouts/header.php';
?>

<!-- Styles -->
<style>
    body {
        font-family: "Alexandria", sans-serif;
        background-color: #faf7f0;
        margin: 0;
        padding: 0;
    }

    .receipt-container {
        max-width: 800px;
        margin: 50px auto;
        padding: 20px;
        background-color: #fff;
        border-radius: 10px;
        text-align: center;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        border: 1px solid #d8d2c2;
    }

    .receipt-container h1 {
        font-size: 2.5em;
        color: #4a4947;
        margin-bottom: 20px;
    }

    .receipt-container p {
        font-size: 1.2em;
        color: #6c6a68;
        margin-bottom: 20px;
    }

    .receipt-container p strong {
        color: #4a4947;
    }

    .receipt-container a {
        display: inline-block;
        margin-top: 20px;
        background-color: #4a4947;
        color: #faf7f0;
        text-decoration: none;
        padding: 10px 20px;
        border-radius: 5px;
        font-size: 1em;
        transition: background-color 0.3s ease;
    }

    .receipt-container a:hover {
        background-color: #fccd2a;
    }

    .receipt-details {
        margin-top: 30px;
        padding: 10px 20px;
        background-color: #d8d2c2;
        border-radius: 8px;
        color: #4a4947;
        text-align: left;
        line-height: 1.8;
        display: inline-block;
    }

    .receipt-details p {
        margin: 5px 0;
    }
</style>

<!-- Receipt Content -->
<div class="receipt-container">
    <h1><?php echo translate('thanks'); ?>, <?= htmlspecialchars($donation['donor_name']); ?></h1>
    <div class="receipt-details">
        <p><strong><?php echo translate('snapid'); ?>:</strong> <?= htmlspecialchars($snapId); ?></p>
        <p><strong><?php echo translate('donation_amount'); ?>:</strong> <?= htmlspecialchars($donation['amount']); ?>
            <?php echo translate('sar'); ?>
        </p>
        <p><strong><?php echo translate('donation_date'); ?>:</strong> <?= htmlspecialchars($donation['created_at']); ?>
        </p>
    </div>
    <p><?php echo translate('snapid_thanks'); ?></p>
    <a href="/sifo-app/views/donations/snap_donate.php"><?php echo translate('make-another-donation'); ?></a>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>