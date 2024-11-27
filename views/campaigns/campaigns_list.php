<?php
include __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../config/config.php';
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

$campaignModel = new CampaignModel($db);


// Fetch first 5 campaigns
try {
    $campaigns = $campaignModel->getAvailableCampaigns(5);
} catch (PDOException $e) {
    $campaigns = [];
    error_log("Error fetching campaigns: " . $e->getMessage());
}

include __DIR__ . '/../layouts/header.php';
?>