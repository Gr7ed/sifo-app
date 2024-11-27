<?php

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../models/CampaignModel.php';

$campaignModel = new CampaignModel($db);


// Fetch first 5 campaigns
try {
    $campaigns = $campaignModel->getAllCampaigns();
} catch (PDOException $e) {
    $campaigns = [];
    error_log("Error fetching campaigns: " . $e->getMessage());
}

include __DIR__ . '/../layouts/header.php';
?>
<style>
    /* Campaigns Section */
    .campaigns-section {
        margin-top: 30px;
        text-align: center;
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
        display: flex;
        flex-direction: column;
        justify-content: space-between;
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
</style>
<div>
    <!-- Campaigns Section -->
    <section class="campaigns-section">
        <h2><?php echo translate('campaigns'); ?></h2>
        <div class="campaign-grid">
            <?php if (!empty($campaigns)): ?>
                <?php foreach ($campaigns as $campaign): ?>
                    <div class="campaign-card">
                        <div class="campaign-title"><?= htmlspecialchars($campaign['charity_name']); ?></div>
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
    </section>
</div>


<?php include __DIR__ . '/../layouts/footer.php'; ?>