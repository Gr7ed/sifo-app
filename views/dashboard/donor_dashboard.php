<?php
include __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../models/DonationModel.php';
require_once __DIR__ . '/../../models/CampaignModel.php';

// Redirect if the user is not an Individual Donor
if ($_SESSION['user_type'] !== 'donor') {
    header("Location: /sifo-app/views/auth/login.php");
    exit();
}

// Fetch donor details
$userId = $_SESSION['user_id'];
$stmt = $db->prepare("SELECT * FROM donors WHERE user_id = ?");
$stmt->execute([$userId]);
$donor = $stmt->fetch();

if (!$donor) {
    die("Error: Donor data not found.");
}

$donationModel = new DonationModel($db);
$campaignModel = new CampaignModel($db);

// Fetch recent donations for the logged-in donor
try {
    $recentDonations = $donationModel->getRecentDonationsByDonor($userId, 5);
} catch (PDOException $e) {
    $recentDonations = [];
    error_log("Error fetching recent donations: " . $e->getMessage());
}

// Fetch first 5 campaigns
try {
    $campaigns = $campaignModel->getRecentCampaigns(5);
} catch (PDOException $e) {
    $campaigns = [];
    error_log("Error fetching campaigns: " . $e->getMessage());
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

    .dashboard-container {
        max-width: 1200px;
        margin: 20px auto;
        padding: 20px;
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    h1,
    h2 {
        color: #4a4947;
        text-align: center;
    }

    .dashboard-header {
        text-align: center;
        margin-bottom: 20px;
    }

    .logout-btn {
        background-color: #4a4947;
        color: #faf7f0;
        border: none;
        padding: 10px 20px;
        font-size: 1em;
        border-radius: 5px;
        cursor: pointer;
    }

    .logout-btn:hover {
        background-color: #fccd2a;
    }

    .actions-section ul {
        list-style: none;
        padding: 0;
        margin: 20px 0;
        display: flex;
        gap: 10px;
        justify-content: center;
    }

    .actions-section ul li a {
        background-color: #4a4947;
        color: #faf7f0;
        text-decoration: none;
        padding: 10px 20px;
        border-radius: 5px;
        font-size: 1em;
    }

    .actions-section ul li a:hover {
        background-color: #fccd2a;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    table th,
    table td {
        padding: 10px;
        text-align: left;
        border: 1px solid #d8d2c2;
    }

    table thead {
        background-color: #4a4947;
        color: #faf7f0;
    }

    table tbody tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    table tbody tr:hover {
        background-color: #e0b021;
    }

    /* Campaigns Section */
    .campaigns-section {
        margin-top: 30px;
    }

    .campaign-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 20px;
        margin-top: 20px;
    }

    .campaign-card {
        background-color: #f9f9f9;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .campaign-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }

    .campaign-title {
        font-size: 1.2em;
        font-weight: bold;
        margin-bottom: 10px;
        color: #4a4947;
    }

    .campaign-description {
        font-size: 0.9em;
        color: #6c6a68;
        margin-bottom: 15px;
    }

    .campaign-target,
    .campaign-collected {
        font-size: 0.9em;
        margin-bottom: 10px;
        color: #4a4947;
    }

    .campaign-contribute {
        text-align: center;
    }

    .campaign-contribute input[type="number"] {
        width: 70%;
        padding: 8px;
        margin-bottom: 10px;
        border: 1px solid #d8d2c2;
        border-radius: 5px;
    }

    .campaign-contribute button {
        background-color: #4a4947;
        color: #faf7f0;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        cursor: pointer;
        font-size: 1em;
    }

    .campaign-contribute button:hover {
        background-color: #fccd2a;
    }

    .view-all-link {
        text-align: center;
        margin-top: 10px;
    }

    .view-all-link a {
        text-decoration: none;
        color: #4a4947;
        font-weight: bold;
        border: 1px solid #4a4947;
        padding: 8px 15px;
        border-radius: 4px;
        transition: all 0.3s ease;
    }

    .view-all-link a:hover {
        background-color: #fccd2a;
        color: #faf7f0;
    }
</style>

<div class="dashboard-container">
    <!-- Dashboard Header -->
    <header class="dashboard-header">
        <h1><?php echo translate('welcome_back'); ?> <?= htmlspecialchars($donor['first_name'] ?? 'Donor'); ?>!</h1>
    </header>

    <!-- Actions Section -->
    <section class="actions-section">
        <ul>
            <li><a href="/sifo-app/views/donations/donate.php"><?php echo translate('make_donation'); ?></a></li>
            <li><a
                    href="/sifo-app/views/donations/donor_donations.php"><?php echo translate('view_donation_history'); ?></a>
            </li>
        </ul>
    </section>

    <!-- Recent Donations -->
    <section class="recent-donations">
        <h2><?php echo translate('recent_donations'); ?></h2>
        <table>
            <thead>
                <tr>
                    <th><?php echo translate('date'); ?></th>
                    <th><?php echo translate('item'); ?></th>
                    <th><?php echo translate('recipient'); ?></th>
                    <th><?php echo translate('status'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($recentDonations)): ?>
                    <?php foreach ($recentDonations as $donation): ?>
                        <tr>
                            <td><?= htmlspecialchars($donation['date']); ?></td>
                            <td><?= htmlspecialchars($donation['item']); ?></td>
                            <td><?= htmlspecialchars($donation['recipient'] ?? 'Not Assigned'); ?></td>
                            <td><?= htmlspecialchars(translateStatus($donation['status'])); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="no-data">No recent donations found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </section>

    <!-- Campaigns Section -->
    <section class="campaigns-section">
        <h2><?php echo translate('campaigns'); ?></h2>
        <div class="campaign-grid">
            <?php if (!empty($campaigns)): ?>
                <?php foreach ($campaigns as $campaign): ?>
                    <div class="campaign-card">
                        <div class="campaign-title"><?= htmlspecialchars($campaign['title']); ?></div>
                        <div class="campaign-description"><?= htmlspecialchars($campaign['description']); ?></div>
                        <div class="campaign-target"><?php echo translate('target'); ?>:
                            <?= htmlspecialchars($campaign['target_amount']); ?>
                        </div>
                        <div class="campaign-collected"><?php echo translate('collected'); ?>:
                            <?= htmlspecialchars($campaign['collected_amount']); ?>
                        </div>
                        <div class="campaign-contribute">
                            <form method="POST" action="/sifo-app/controllers/CampaignController.php?action=contribute">
                                <input type="hidden" name="campaign_id" value="<?= $campaign['campaign_id']; ?>">
                                <input type="number" name="amount" placeholder="Enter amount" required min="1">
                                <button type="submit"><?php echo translate('contribute'); ?></button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="no-data"><?php echo translate('no-campaigns'); ?></p>
            <?php endif; ?>
        </div>
        <br>
        <p class="view-all-link"><a
                href="/sifo-app/views/campaigns/campaigns_list.php"><?php echo translate('view-all'); ?></a></p>
    </section>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>