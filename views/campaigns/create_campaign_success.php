<?php
include __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../../config/config.php';
// Get success message key from query parameters and provide a default key
$successKey = $_GET['success'] ?? 'operation_completed';
?>

<style>
    body {
        font-family: "Alexandria", sans-serif;
        background-color: #faf7f0;
        margin: 0;
        padding: 0;
    }

    .success-container {
        max-width: 800px;
        margin: 50px auto;
        padding: 20px;
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        text-align: center;
        color: #4a4947;
    }

    .success-container h1 {
        font-size: 2.5em;
        color: #4CAF50;
        margin-bottom: 20px;
    }

    .success-container p {
        font-size: 1.2em;
        color: #6c6a68;
        margin-bottom: 20px;
    }

    .success-container a {
        display: inline-block;
        margin-top: 20px;
        padding: 10px 20px;
        background-color: #4a4947;
        color: #faf7f0;
        text-decoration: none;
        border-radius: 5px;
        font-size: 1.1em;
        transition: background-color 0.3s;
    }

    .success-container a:hover {
        background-color: #fccd2a;
    }
</style>

<div class="success-container">
    <p><?= translate($successKey); ?></p>
    <a href="/sifo-app/views/campaigns/charity_campaigns.php"><?= translate('view-campaigns'); ?></a>
    <a href="/sifo-app/views/dashboard/charity_dashboard.php"
        style="margin-left: 10px;"><?= translate('back-dashboard'); ?></a>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>