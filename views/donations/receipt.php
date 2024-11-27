<?php
include __DIR__ . '/../../includes/session.php';
include __DIR__ . '/../layouts/header.php';

$donationId = $_GET['donation_id'] ?? null;
$snapId = $_GET['snap_id'] ?? null;
?>

<main
    style="padding: 20px; font-family: 'Alexandria', sans-serif; background-color: #faf7f0; color: #4a4947; max-width: 600px; margin: 0 auto; border-radius: 8px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
    <?php if ($donationId): ?>
        <?php
        $stmt = $db->prepare("SELECT * FROM donations WHERE donation_id = ?");
        $stmt->execute([$donationId]);
        $donation = $stmt->fetch();
        ?>
        <h1 style="text-align: center; color: #4a4947;">Donation Receipt</h1>
        <div style="margin-top: 20px;">
            <p><strong>Donation ID:</strong> <?= htmlspecialchars($donation['donation_id']); ?></p>
            <p><strong>Type:</strong> <?= htmlspecialchars($donation['type']); ?></p>
            <p><strong>Amount:</strong> <?= htmlspecialchars($donation['amount']); ?></p>
            <p><strong>Status:</strong> <?= htmlspecialchars($donation['status']); ?></p>
        </div>
    <?php elseif ($snapId): ?>
        <?php
        $stmt = $db->prepare("SELECT * FROM snap_donors WHERE snap_id = ?");
        $stmt->execute([$snapId]);
        $snapDonation = $stmt->fetch();
        ?>
        <h1 style="text-align: center; color: #4a4947;">Snap Donation Receipt</h1>
        <div style="margin-top: 20px;">
            <p><strong>Donor Name:</strong> <?= htmlspecialchars($snapDonation['donor_name']); ?></p>
            <p><strong>Amount:</strong> <?= htmlspecialchars($snapDonation['amount']); ?></p>
            <p><strong>Date:</strong> <?= htmlspecialchars($snapDonation['created_at']); ?></p>
        </div>
    <?php else: ?>
        <h1 style="text-align: center; color: #4a4947;">Invalid Donation Reference</h1>
        <p style="text-align: center; color: #4a4947;">The donation reference provided is not valid. Please check your input
            and try again.</p>
    <?php endif; ?>
</main>

<?php include __DIR__ . '/../layouts/footer.php'; ?>