<?php
include __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../models/CampaignModel.php';

// Ensure the user is logged in as a donor
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'donor') {
    header("Location: /sifo-app/views/auth/login.php");
    exit();
}

// Fetch donor details
$userId = $_SESSION['user_id'];
$stmt = $db->prepare("SELECT first_name FROM donors WHERE user_id = ?");
$stmt->execute([$userId]);
$donor = $stmt->fetch();

// Validate donor details
if (!$donor) {
    die("Error: Donor data not found.");
}

// Fetch contribution details
$contributionId = $_GET['contribution_id'] ?? null;

if (!$contributionId) {
    die("Error: Contribution ID is missing.");
}

$stmt = $db->prepare("
    SELECT contributions.amount, campaigns.title AS campaign_title, charities.charity_name
    FROM contributions
    INNER JOIN campaigns ON contributions.campaign_id = campaigns.campaign_id
    INNER JOIN charities ON campaigns.charity_id = charities.user_id
    WHERE contributions.contribution_id = ?
");
$stmt->execute([$contributionId]);
$contribution = $stmt->fetch();

// Validate contribution details
if (!$contribution) {
    die("Error: Contribution not found.");
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

    .thanks-container {
        max-width: 800px;
        margin: 50px auto;
        padding: 20px;
        background-color: #fff;
        border-radius: 10px;
        text-align: center;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .thanks-container h1 {
        font-size: 2.5em;
        color: #4a4947;
        margin-bottom: 20px;
    }

    .thanks-container p {
        font-size: 1.2em;
        color: #6c6a68;
        margin-bottom: 20px;
    }

    .thanks-container .campaign-info {
        font-size: 1.1em;
        color: #4a4947;
        margin-bottom: 20px;
    }

    .thanks-container a {
        display: inline-block;
        background-color: #4a4947;
        color: #faf7f0;
        text-decoration: none;
        padding: 10px 20px;
        border-radius: 5px;
        font-size: 1em;
    }

    .thanks-container a:hover {
        background-color: #fccd2a;
    }
</style>

<div class="thanks-container">
    <h1>Thank You, <?= htmlspecialchars($donor['first_name']); ?>!</h1>
    <p>Your contribution of <strong><?= htmlspecialchars($contribution['amount']); ?> SAR</strong> to the campaign
        "<strong><?= htmlspecialchars($contribution['campaign_title']); ?></strong>" has been successfully recorded.</p>
    <div class="campaign-info">
        <p><strong>Contribution ID:</strong> <?= htmlspecialchars($contributionId); ?></p>
        <p><strong>Charity Name:</strong> <?= htmlspecialchars($contribution['charity_name']); ?></p>
    </div>
    <a href="/sifo-app/views/dashboard/donor_dashboard.php">Back to Dashboard</a>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>