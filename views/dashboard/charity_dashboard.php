<?php
include __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../models/DonationModel.php';
require_once __DIR__ . '/../../models/CampaignModel.php';


// Ensure the user is logged in and is a charity
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'charity') {
    die("Access denied. Only charities can view this page.");
}

// Fetch charity details
$userId = $_SESSION['user_id'];
$stmt = $db->prepare("SELECT * FROM charities WHERE user_id = ?");
$stmt->execute([$userId]);
$charity = $stmt->fetch();

if (!$charity) {
    die("Error: Charity data not found.");
}

$charityCity = $charity['city'];
$acceptedTypes = explode(',', $charity['accepted_types']); // Assume 'accepted_types' is a comma-separated string

// Initialize donation model
$donationModel = new DonationModel($db);
$campaignModel = new CampaignModel($db);
// Get donations based on status, city, and type
$status = $_GET['status'] ?? 'Available'; // Default to 'Available'
$type = $_GET['type'] ?? ($acceptedTypes[0] ?? 'Food'); // Default to first accepted type
$donations = $donationModel->getDonationsByStatus($status, $charityCity, $type);
$campaigns = $campaignModel->getCampaignsByCharity($userId);
// Fetch statistics
$totalDonationsReceived = $donationModel->getTotalDonationsByCharity($userId, 'Delivered');
$totalPendingDonations = $donationModel->getTotalDonationsByCharity($userId, 'Pending');
$totalAvailableDonations = $donationModel->getTotalDonationsByCharity($userId, 'Available');
$totalCampaigns = $campaignModel->getTotalCampaigns($userId);
// Fetch recent donations (limit 5)
$recentDonations = $donationModel->getRecentDonationsByCharity($userId);

include __DIR__ . '/../layouts/header.php';
?>

<style>
    .dashboard-container {
        padding: 20px;
        max-width: 900px;
        margin: 0 auto;
        background-color: #faf7f0;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    h1,
    h2 {
        text-align: center;
        color: #4a4947;
    }

    .welcome-message {
        text-align: center;
        margin-bottom: 20px;
        color: #4a4947;
    }

    .statistics,
    .recent-donations,
    .donation-actions {
        margin-bottom: 30px;
    }

    .statistics p,
    .recent-donations p {
        color: #4a4947;
        margin: 5px 0;
    }

    .statistics {
        text-align: center;
    }

    .recent-donations {
        background-color: #d8d2c2;
        padding: 15px;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    form {
        margin-bottom: 20px;
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        align-items: center;
    }

    label {
        color: #4a4947;
    }

    select {
        padding: 8px;
        border: 1px solid #d8d2c2;
        border-radius: 4px;
    }


    table {
        width: 100%;
        border-collapse: collapse;
        background-color: #fff;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    table thead tr {
        background-color: #4a4947;
        color: #faf7f0;
    }

    table thead th {
        padding: 10px;
        text-align: left;
        font-size: 16px;
    }

    table tbody tr:nth-child(odd) {
        background-color: #f8f8f8;
    }

    table tbody tr:nth-child(even) {
        background-color: #fff;
    }

    table tbody td {
        padding: 10px;
        font-size: 14px;
        color: #4a4947;
    }

    table tbody tr:hover {
        background-color: #f0e6d2;
    }
</style>

<div class="dashboard-container">
    <h1>Charity Dashboard</h1>
    <p class="welcome-message">Welcome, <?= htmlspecialchars($charity['charity_name'] ?? 'Charity'); ?>!</p>

    <!-- Statistics Section -->
    <section class="statistics">
        <h2>Donation Statistics:</h2>
        <p>Total Campaigns: <?= $totalCampaigns; ?></p>
        <p>Total Donations Received: <?= $totalDonationsReceived; ?></p>
        <p>Pending Donations: <?= $totalPendingDonations; ?></p>
        <p>Available Donations: <?= $totalAvailableDonations; ?></p>
    </section>

    <!-- Recent Donations Section -->
    <section class="recent-donations">
        <h2>Recent Donations Delivered</h2>
        <?php if (empty($recentDonations)): ?>
            <p>No recent donations available.</p>
        <?php else: ?>
            <?php foreach ($recentDonations as $recent): ?>
                <p><strong>Donation ID:</strong> <?= htmlspecialchars($recent['donation_id']); ?></p>
                <p><strong>Description:</strong> <?= htmlspecialchars($recent['description']); ?></p>
                <p><strong>Status:</strong> <?= htmlspecialchars($recent['status']); ?></p>
                <p><strong>Date:</strong> <?= htmlspecialchars($recent['pickup_date_time']); ?></p>
                <hr>
            <?php endforeach; ?>
        <?php endif; ?>
    </section>
    <section class="recent-campaigns">
        <h2>Recent Campaigns</h2>
        <br>
        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Target Amount</th>
                    <th>Collected Amount</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($campaigns)): ?>
                    <?php foreach ($campaigns as $campaign): ?>
                        <tr>
                            <td><?= htmlspecialchars($campaign['title']); ?></td>
                            <td><?= htmlspecialchars($campaign['description']); ?></td>
                            <td><?= htmlspecialchars($campaign['target_amount']); ?></td>
                            <td><?= htmlspecialchars($campaign['collected_amount']); ?></td>
                            <td><?= htmlspecialchars($campaign['start_date']); ?></td>
                            <td><?= htmlspecialchars($campaign['end_date']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" style="text-align: center; color: #a0a0a0;">No campaigns found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

    </section>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>