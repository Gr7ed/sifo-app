<?php
include __DIR__ . '/../../includes/session.php';

// Ensure only charity users can access this page
if ($_SESSION['user_type'] !== 'charity') {
    header("Location: /sifo-app/views/auth/login.php");
    exit();
}

// Include necessary files
require_once __DIR__ . '/../../config/config.php';
include __DIR__ . '/../layouts/header.php';

// Fetch forwarded donations
$charityId = $_SESSION['user_id'];
$stmt = $db->prepare("
    SELECT d.donation_id, d.type, d.description, d.location, d.pickup_date_time, d.amount, d.status, u.username AS donor_name
    FROM donations d
    JOIN users u ON d.donor_id = u.user_id
    WHERE d.forwarded_to = ? AND d.status = 'Forwarded'
    ORDER BY d.created_at DESC
");
$stmt->execute([$charityId]);
$donations = $stmt->fetchAll();
?>

<h1>Welcome, <?= htmlspecialchars($_SESSION['username']); ?>!</h1>
<p>This is your Charity Dashboard. Below are the donations forwarded to you.</p>

<h2>Forwarded Donations</h2>
<?php if ($donations): ?>
    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th>Donation ID</th>
                <th>Type</th>
                <th>Description</th>
                <th>Location</th>
                <th>Pickup Date & Time</th>
                <th>Amount</th>
                <th>Donor</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($donations as $donation): ?>
                <tr>
                    <td><?= htmlspecialchars($donation['donation_id']); ?></td>
                    <td><?= htmlspecialchars($donation['type']); ?></td>
                    <td><?= htmlspecialchars($donation['description']); ?></td>
                    <td><?= htmlspecialchars($donation['location']); ?></td>
                    <td><?= htmlspecialchars($donation['pickup_date_time']); ?></td>
                    <td><?= htmlspecialchars($donation['amount']); ?></td>
                    <td><?= htmlspecialchars($donation['donor_name']); ?></td>
                    <td>
                        <form method="POST" action="/sifo-app/controllers/DonationController.php?action=mark_completed">
                            <input type="hidden" name="donation_id" value="<?= htmlspecialchars($donation['donation_id']); ?>">
                            <button type="submit">Mark as Completed</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>No donations have been forwarded to you yet.</p>
<?php endif; ?>

<?php include __DIR__ . '/../layouts/footer.php'; ?>