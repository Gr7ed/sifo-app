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
$campaigns = $campaignModel->getAllCampaignsByCharity($charityId);

include __DIR__ . '/../layouts/header.php';
?>

<style>
    main {
        padding: 20px;
        max-width: 1000px;
        margin: 0 auto;
        background-color: #faf7f0;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        font-family: 'Alexandria', sans-serif;
    }

    h1 {
        text-align: center;
        margin-bottom: 20px;
        color: #4a4947;
        font-size: 28px;
    }

    .add-campaign {
        display: inline-block;
        margin-bottom: 20px;
        padding: 10px 20px;
        background-color: #4a4947;
        color: #faf7f0;
        border: none;
        border-radius: 5px;
        text-decoration: none;
        font-size: 16px;
        font-weight: bold;
        transition: background-color 0.3s ease;
    }

    .add-campaign:hover {
        background-color: #fccd2a;
        color: #4a4947;
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

    .actions a {
        color: #4a4947;
        text-decoration: none;
        font-weight: bold;
        padding: 5px 10px;
        background-color: #e7e7e7;
        border-radius: 4px;
        transition: background-color 0.3s ease, color 0.3s ease;
    }

    .actions a:hover {
        background-color: #fccd2a;
        color: #4a4947;
    }
</style>

<main>
    <h1><?php echo translate('your-campaigns'); ?></h1>
    <a href="/sifo-app/views/campaigns/create_campaign.php" class="add-campaign"><?= translate('add_campaign'); ?></a>
    <table>
        <thead>
            <tr>
                <th><?php echo translate('campaign_name'); ?></th>
                <th><?php echo translate('description'); ?></th>
                <th><?php echo translate('target'); ?></th>
                <th><?php echo translate('collected'); ?></th>
                <th><?php echo translate('start_date'); ?></th>
                <th><?php echo translate('end_date'); ?></th>
                <th><?php echo translate('actions'); ?></th>
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
                        <td class="actions">
                            <a
                                href="/sifo-app/views/campaigns/edit_campaign.php?campaign_id=<?= $campaign['campaign_id']; ?>"><?php echo translate('edit'); ?></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" style="text-align: center; color: #a0a0a0;"><?php echo translate('no-campaigns'); ?>
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</main>

<?php include __DIR__ . '/../layouts/footer.php'; ?>