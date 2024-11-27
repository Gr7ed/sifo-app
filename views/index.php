<?php
require_once __DIR__ . '/../config/config.php';
// Start the session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../models/CampaignModel.php';
$campaignModel = new CampaignModel($db);
try {
    $campaigns = $campaignModel->getAvailableCampaigns(5);
} catch (PDOException $e) {
    $campaigns = [];
    error_log("Error fetching campaigns: " . $e->getMessage());
}
include __DIR__ . '/layouts/header.php';
?>

<style>
    body {
        font-family: 'Alexandria', sans-serif;
        background-color: #faf7f0;
        color: #4a4947;
        margin: 0;
        padding: 0;
    }

    main {
        padding: 20px;
    }

    h1,
    h3,
    p {
        margin: 0;
    }

    /* Call-to-Action Section */
    .call-to-action {
        text-align: center;
        margin-bottom: 40px;
    }

    .call-to-action h3 {
        color: #4a4947;
    }

    /* Hero Section */
    .hero {
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .features {
        text-align: center;
        max-width: 600px;
        margin-bottom: 30px;
    }

    .features h3 {
        font-size: 24px;
        color: #4a4947;
    }

    .features p {
        font-size: 16px;
        line-height: 1.5;
    }

    .platform-details {
        display: flex;
        justify-content: space-around;
        max-width: 900px;
        width: 100%;
        gap: 20px;
    }

    .donor-section,
    .charity-section {
        flex: 1;
        padding: 20px;
        background-color: #d8d2c2;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        text-align: center;
    }

    button {
        background-color: #fccd2a;
        color: #4a4947;
        border: none;
        padding: 10px 20px;
        border-radius: 4px;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    button:hover {
        background-color: #e0b021;
    }

    .button {
        margin-top: 30px;
        display: inline-block;
        padding: 10px 20px;
        background-color: #e0b021;
        color: #faf7f0;
        text-decoration: none;
        border-radius: 4px;
        transition: background-color 0.3s;
    }

    .button:hover {
        background-color: #fccd2a;
    }

    /* Partners Section */
    .partners {
        margin-top: 50px;
        text-align: center;
        background-color: #d8d2c2;
        padding: 30px;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .partners h3 {
        color: #4a4947;
        margin-bottom: 20px;
    }

    .partners img {
        width: 150px;
        height: auto;
        object-fit: contain;
        transition: transform 0.3s;
    }

    .partners img:hover {
        transform: scale(1.1);
    }

    .partners div {
        display: flex;
        justify-content: center;
        gap: 20px;
        align-items: center;
    }

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


<main>
    <!-- Call-to-Action Section -->
    <section class="call-to-action">
        <?php if (isset($_SESSION['user_id'])): ?>
            <h3><?php echo translate('welcome_back'); ?>     <?= htmlspecialchars($_SESSION['username']); ?>!</h3>
        <?php endif; ?>
    </section>

    <!-- Hero Section -->
    <section class="hero">
        <div class="features">
            <h3><?php echo translate('our_aim'); ?></h3>
            <br>
            <p>
                <?php echo translate('intro_aim'); ?>
            </p>
        </div>
        <br>
        <h3><?php echo translate('how_work'); ?></h3>
        <br>
        <?php if (!isset($_SESSION['user_id'])): ?>
            <div class="platform-details">
                <!-- Donor Section -->
                <div class="donor-section">
                    <p>
                        <b><?php echo translate('as_donors'); ?></b><br />
                        <br>
                        <?php echo translate('intro_donors'); ?>
                    </p>
                    <br>
                    <button onclick="location.href='/sifo-app/views/donations/donate.php';">
                        <?php echo translate('donate_now'); ?>
                    </button>
                    <p style="font-size: 10px; margin-top: 10px;">
                        <span style="color: #000000;"><b><?php echo translate('note'); ?></b></span>:
                        <?php echo translate('note_text'); ?>
                    </p>
                </div>

                <!-- Charity Section -->
                <div class="charity-section">
                    <p>
                        <b><?php echo translate('as_charities'); ?></b><br />
                        <br>
                        <?php echo translate('intro_charities'); ?>
                    </p>
                    <br>
                    <button onclick="location.href='/sifo-app/views/dashboard/charity_dashboard.php';">
                        <?php echo translate('manage_donations'); ?>
                    </button>
                    <p style="font-size: 10px; margin-top: 10px;">
                        <span style="color: #000000;"><b><?php echo translate('note'); ?></b></span>:
                        <?php echo translate('charity_note'); ?>
                    </p>
                </div>
            </div>

        <?php else: ?>
            <a href="/sifo-app/views/dashboard/<?= strtolower($_SESSION['user_type']); ?>_dashboard.php" class="button">
                <?php echo translate("go-dashboard"); ?>
            </a>
        <?php endif; ?>
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
    <!-- Partners Section -->
    <section class="partners">
        <h3><?php echo translate('our_partners'); ?></h3>
        <div>
            <a href="#"><img src="/sifo-app/assets/img/albaik.png" alt="Partner Logo"></a>
            <a href="#"><img src="/sifo-app/assets/img/altazaj.png" alt="Partner Logo"></a>
            <a href="#"><img src="/sifo-app/assets/img/Fake.png" alt="Partner Logo"></a>
        </div>
    </section>
</main>

<?php include __DIR__ . '/layouts/footer.php'; ?>