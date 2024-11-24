<?php
include __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../models/DonationModel.php';

// Ensure the user is logged in and is a charity
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'charity') {
    die("Access denied. Only charities can view this page.");
}

// Fetch charity details
$userId = $_SESSION['user_id'];
$stmt = $db->prepare("SELECT * FROM charities WHERE user_id = ?");
$stmt->execute([$userId]);
$charity = $stmt->fetch();

// Check if charity data exists
if (!$charity) {
    die("Error: Charity data not found.");
}

$charityCity = $charity['city'];
$acceptedTypes = explode(',', $charity['accepted_types']); // Assume 'accepted_types' is a comma-separated string

// Initialize donation model
$donationModel = new DonationModel($db);

// Get donations based on status, city, and type
$status = $_GET['status'] ?? 'Available'; // Default to 'Available'
$type = $_GET['type'] ?? ($acceptedTypes[0] ?? 'Food'); // Default to first accepted type
$donations = $donationModel->getDonationsByStatus($status, $charityCity, $type);

include __DIR__ . '/../layouts/header.php';
?>

<h1>Charity Dashboard</h1>
<p>Welcome, <?= htmlspecialchars($charity['charity_name'] ?? 'Charity'); ?>!</p>

<section class="donation-actions">
    <h2>Actions</h2>
    <ul>
        <li><a href="/sifo-app/views/campaigns/create_campaign.php"><?php echo translate('add_campaign'); ?></a></li>
        <li><a href="/sifo-app/views/campaigns/view_campaigns.php">View Campaigns History</a></li>
    </ul>
</section>
<form method="GET" action="">
    <label for="status">Filter by Status:</label>
    <select name="status" id="status" onchange="this.form.submit()">
        <option value="Available" <?= $status === 'Available' ? 'selected' : ''; ?>>Available</option>
        <option value="Pending" <?= $status === 'Pending' ? 'selected' : ''; ?>>Pending</option>
        <option value="Forwarded" <?= $status === 'Forwarded' ? 'selected' : ''; ?>>Forwarded</option>
        <option value="Delivered" <?= $status === 'Delivered' ? 'selected' : ''; ?>>Delivered</option>
    </select>

    <label for="type">Filter by Type:</label>
    <select name="type" id="type" onchange="this.form.submit()">
        <?php foreach ($acceptedTypes as $acceptedType): ?>
            <option value="<?= htmlspecialchars($acceptedType); ?>" <?= $type === $acceptedType ? 'selected' : ''; ?>>
                <?= htmlspecialchars($acceptedType); ?>
            </option>
        <?php endforeach; ?>
    </select>
</form>

<table>
    <thead>
        <tr>
            <th>Donation ID</th>
            <th>Type</th>
            <th>Description</th>
            <th>Condition</th>
            <th>City</th>
            <th>District</th>
            <th>Pickup Date</th>
            <th>Amount</th>
            <th>Status</th>
            <th>Donor Name</th>
            <th>Donor Phone</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($donations)): ?>
            <?php foreach ($donations as $donation): ?>
                <tr>
                    <td><?= htmlspecialchars($donation['donation_id']); ?></td>
                    <td><?= htmlspecialchars($donation['type']); ?></td>
                    <td><?= htmlspecialchars($donation['description']); ?></td>
                    <td><?= htmlspecialchars($donation['donate_condition']); ?></td>
                    <td><?= htmlspecialchars($donation['city']); ?></td>
                    <td><?= htmlspecialchars($donation['district']); ?></td>
                    <td><?= htmlspecialchars($donation['pickup_date_time']); ?></td>
                    <td><?= htmlspecialchars($donation['amount']); ?></td>
                    <td><?= htmlspecialchars($donation['status']); ?></td>
                    <td><?= htmlspecialchars($donation['donor_name']); ?></td>
                    <td><?= htmlspecialchars($donation['donor_phone']); ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="11">No donations found for the selected filters.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<?php include __DIR__ . '/../layouts/footer.php'; ?>