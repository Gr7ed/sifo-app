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
$campaigns = $campaignModel->getRecentCampaignsByCharity($userId, 4);
// Fetch statistics
$totalDonationsReceived = $donationModel->getTotalDonationsByCharity($userId, 'Delivered');
$totalPendingDonations = $donationModel->getTotalDonationsByCharity($userId, 'Pending');
$totalAvailableDonations = $donationModel->getTotalDonationsByCharity($userId, 'Available');
$totalCampaigns = $campaignModel->getTotalCampaigns($userId);
// Fetch recent donations (limit 5)
$recentDonations = $donationModel->getRecentDonationsByCharity($userId);
$translations_2 = [
    'en' => [
        'Received' => 'Received Donations',
        'Pending' => 'Pending Donations',
        'Available' => 'Available Donations',
        'Charity Statistics' => 'Donation Statistics'
    ],
    'ar' => [
        'Received' => 'تبرعات تم استلامها',
        'Pending' => 'تبرعات قيد الانتظار',
        'Available' => 'تبرعات متاحة',
        'Charity Statistics' => 'إحصائيات التبرعات'
    ]
];

$translation = $translations_2[$lang];
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

    .statistics,
    .recent-donations,
    .recent-campaigns,
    .chart-section {
        margin-bottom: 30px;
    }

    .statistics p,
    .recent-donations p,
    .chart-section p {
        color: #4a4947;
        margin: 5px 0;
    }

    .statistics {
        text-align: center;
    }

    .recent-donations,
    .recent-campaigns {
        background-color: #d8d2c2;
        padding: 15px;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
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

    .chart-section canvas {
        max-width: 100%;
        height: auto;
    }
</style>

<div class="dashboard-container">
    <p class="welcome-message"><?php echo translate('welcome'); ?>,
        <strong><?= htmlspecialchars($charity['charity_name'] ?? 'Charity'); ?></strong>!
    </p>
    <br>


    <!-- Chart Section -->
    <section class="chart-section">
        <h2><?php echo translate('charity-overview'); ?></h2>
        <p><strong><?php echo translate('total-campaigns'); ?>:</strong> <?= htmlspecialchars($totalCampaigns); ?></p>
        <canvas id="donationChart" width="300" height="100"></canvas>
    </section>

    <!-- Recent Donations Section -->
    <section class="recent-donations">
        <h2><?php echo translate('recent-donations-delivered'); ?></h2>
        <?php if (empty($recentDonations)): ?>
            <p><?php echo translate('no-recent-donations'); ?>.</p>
        <?php else: ?>
            <?php foreach ($recentDonations as $recent): ?>
                <p><strong><?php echo translate('donationid'); ?>:</strong> <?= htmlspecialchars($recent['donation_id']); ?></p>
                <p><strong><?php echo translate('description'); ?>:</strong> <?= htmlspecialchars($recent['description']); ?>
                </p>
                <p><strong><?php echo translate('status'); ?>:</strong>
                    <?= htmlspecialchars(translateStatus($recent['status'])); ?></p>
                <p><strong><?php echo translate('date'); ?>:</strong> <?= htmlspecialchars($recent['pickup_date_time']); ?></p>
                <hr>
            <?php endforeach; ?>
        <?php endif; ?>
    </section>

    <!-- Recent Campaigns Section -->
    <section class="recent-campaigns">
        <h2><?php echo translate('recent-campaigns'); ?></h2>
        <table>
            <thead>
                <tr>
                    <th><?php echo translate('campaign_name'); ?></th>
                    <th><?php echo translate('description'); ?></th>
                    <th><?php echo translate('target'); ?></th>
                    <th><?php echo translate('collected'); ?></th>
                    <th><?php echo translate('start_date'); ?></th>
                    <th><?php echo translate('end_date'); ?></th>
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
                        <td colspan="6" style="text-align: center; color: #a0a0a0;">No campaigns found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </section>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const data = {
        labels: [
            '<?= htmlspecialchars($translation['Received'] ?? 'Received'); ?>',
            '<?= htmlspecialchars($translation['Pending'] ?? 'Pending'); ?>',
            '<?= htmlspecialchars($translation['Available'] ?? 'Available'); ?>'],
        datasets: [{
            label: '<?= htmlspecialchars($translation['Charity Statistics'] ?? 'Charity Statistics'); ?>',
            data: [
                <?= $totalDonationsReceived; ?>,
                <?= $totalPendingDonations; ?>,
                <?= $totalAvailableDonations; ?>
            ],
            backgroundColor: [
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(54, 162, 235, 0.2)'
            ],
            borderColor: [
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(54, 162, 235, 1)'
            ],
            borderWidth: 1
        }]
    };

    const config = {
        type: 'bar',
        data: data,
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    suggestedMin: 0,
                    suggestedMax: 20
                }
            }
        }
    };

    new Chart(document.getElementById('donationChart'), config);
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>