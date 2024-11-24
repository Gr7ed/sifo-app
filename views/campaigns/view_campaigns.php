<?php
include __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../models/CampaignModel.php';

$campaignModel = new CampaignModel($db);

// Ensure the user is a charity
if ($_SESSION['user_type'] !== 'charity') {
    die("Access denied. Only charities can view campaigns.");
}

$charityId = $_SESSION['user_id']; // Assume user_id is the charity's ID
$campaigns = $campaignModel->getCampaignsByCharity($charityId);

include __DIR__ . '/../layouts/header.php';
?>

<h1>Your Campaigns</h1>

<table>
    <thead>
        <tr>
            <th>Title</th>
            <th>Description</th>
            <th>Target Amount</th>
            <th>Collected Amount</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($campaigns as $campaign): ?>
            <tr>
                <td><?= htmlspecialchars($campaign['title']); ?></td>
                <td><?= htmlspecialchars($campaign['description']); ?></td>
                <td><?= htmlspecialchars($campaign['target_amount']); ?></td>
                <td><?= htmlspecialchars($campaign['collected_amount']); ?></td>
                <td><?= htmlspecialchars($campaign['start_date']); ?></td>
                <td><?= htmlspecialchars($campaign['end_date']); ?></td>
                <td>
                    <!-- Actions can include editing or deleting campaigns -->
                    <a
                        href="/sifo-app/views/campaigns/edit_campaign.php?campaign_id=<?= $campaign['campaign_id']; ?>">Edit</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include __DIR__ . '/../layouts/footer.php'; ?>