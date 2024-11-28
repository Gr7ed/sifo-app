<?php
include __DIR__ . '/../../includes/session.php';
include __DIR__ . '/../layouts/header.php';

$donationId = $_GET['donation_id'] ?? null;


?>

<style>
    body {
        font-family: "Alexandria", sans-serif;
        background-color: #faf7f0;
        margin: 0;
        padding: 0;
    }

    .receipt-container {
        max-width: 600px;
        margin: 50px auto;
        padding: 20px;
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        text-align: center;
        color: #4a4947;
    }

    .receipt-container h1 {
        font-size: 2.5em;
        color: #4a4947;
        margin-bottom: 20px;
    }

    .receipt-details {
        margin-top: 20px;
        text-align: left;
        line-height: 1.6;
    }

    .receipt-details p {
        font-size: 1.1em;
        color: #6c6a68;
    }

    .invalid-reference {
        color: #d9534f;
        font-weight: bold;
        text-align: center;
    }

    .back-button {
        display: inline-block;
        margin-top: 20px;
        padding: 10px 20px;
        background-color: #4a4947;
        color: #faf7f0;
        text-decoration: none;
        border-radius: 5px;
        font-size: 1.1em;
        transition: background-color 0.3s;
    }

    .back-button:hover {
        background-color: #fccd2a;
    }
</style>

<main class="receipt-container">
    <?php if ($donationId): ?>
        <?php
        $stmt = $db->prepare("SELECT * FROM donations WHERE donation_id = ?");
        $stmt->execute([$donationId]);
        $donation = $stmt->fetch();
        ?>
        <?php if ($donation): ?>
            <h1><?php echo translate('donation_receipt'); ?></h1>
            <div class="receipt-details">
                <p><strong><?php echo translate('donationid'); ?>:</strong> <?= htmlspecialchars($donation['donation_id']); ?>
                </p>
                <p><strong><?php echo translate('donation_type'); ?>:</strong>
                    <?= htmlspecialchars(translateStatus($donation['type'])); ?></p>
                <p><strong><?php echo translate('num_amount'); ?>:</strong> <?= htmlspecialchars($donation['amount']); ?></p>
                <p><strong><?php echo translate('status'); ?>:</strong>
                    <?= htmlspecialchars(translateStatus($donation['status'])); ?>
                </p>
            </div>
            <a href="/sifo-app/views/dashboard/donor_dashboard.php"
                class="back-button"><?php echo translate('back-dashboard'); ?></a>
        <?php endif; ?>
    <?php else: ?>
        <h1 class="invalid-reference">Invalid Donation Reference</h1>
        <p class="invalid-reference">The donation reference provided is not valid. Please check your input and try again.
        </p>
        <a href="/sifo-app/views/donations/donor_donations.php"
            class="back-button"><?php echo translate('view-donations'); ?></a>
    <?php endif; ?>
    <a href="/sifo-app/views/donations/donor_donations.php"
        class="back-button"><?php echo translate('view-donations'); ?></a>
</main>

<?php include __DIR__ . '/../layouts/footer.php'; ?>