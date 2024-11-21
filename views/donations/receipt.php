<?php
include __DIR__ . '/../../includes/session.php';
include __DIR__ . '/../layouts/header.php';

$donationId = $_GET['donation_id'] ?? null;
$snapId = $_GET['snap_id'] ?? null;

if ($donationId) {
    $stmt = $db->prepare("SELECT * FROM donations WHERE donation_id = ?");
    $stmt->execute([$donationId]);
    $donation = $stmt->fetch();
    echo "<h1>Donation Receipt</h1>";
    echo "<p>Type: " . htmlspecialchars($donation['type']) . "</p>";
    echo "<p>Amount: " . htmlspecialchars($donation['amount']) . "</p>";
    echo "<p>Status: " . htmlspecialchars($donation['status']) . "</p>";
} elseif ($snapId) {
    $stmt = $db->prepare("SELECT * FROM snap_donors WHERE snap_id = ?");
    $stmt->execute([$snapId]);
    $snapDonation = $stmt->fetch();
    echo "<h1>Snap Donation Receipt</h1>";
    echo "<p>Donor Name: " . htmlspecialchars($snapDonation['donor_name']) . "</p>";
    echo "<p>Amount: " . htmlspecialchars($snapDonation['amount']) . "</p>";
    echo "<p>Date: " . htmlspecialchars($snapDonation['created_at']) . "</p>";
} else {
    echo "<p>Invalid donation reference.</p>";
}

include __DIR__ . '/../layouts/footer.php';
?>