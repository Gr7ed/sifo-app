<?php
require_once '../config/config.php';

class DashboardController
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * Get recent donations for an Individual Donor
     * @param int $userId
     * @return array
     */
    public function getRecentDonations($userId)
    {
        $stmt = $this->db->prepare("
            SELECT donations.date, donations.item, charities.name AS recipient, donations.status
            FROM donations
            JOIN charities ON donations.recipient_id = charities.id
            WHERE donations.donor_id = ?
            ORDER BY donations.date DESC
            LIMIT 5
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    /**
     * Get organization-specific stats
     * @param int $userId
     * @return array
     */
    public function getOrganizationStats($userId)
    {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) AS total_donations, SUM(donations.amount) AS total_amount
            FROM donations
            WHERE donor_id = ?
        ");
        $stmt->execute([$userId]);
        return $stmt->fetch();
    }

    /**
     * Get charity-specific stats
     * @param int $userId
     * @return array
     */
    public function getCharityStats($userId)
    {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) AS total_received, SUM(donations.amount) AS total_value
            FROM donations
            WHERE recipient_id = ?
        ");
        $stmt->execute([$userId]);
        return $stmt->fetch();
    }
}

// Initialize the DashboardController
session_start();
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_type'])) {
    header("Location: /sifo-app/views/auth/login.php");
    exit();
}

$dashboardController = new DashboardController($db);

// Fetch data based on user type
if ($_SESSION['user_type'] === 'donor') {
    $recentDonations = $dashboardController->getRecentDonations($_SESSION['user_id']);
    include '../views/dashboard/individual_dashboard.php';
} elseif ($_SESSION['user_type'] === 'charity') {
    $charityStats = $dashboardController->getCharityStats($_SESSION['user_id']);
    include '../views/dashboard/charity_dashboard.php';
} else {
    die("Invalid user type.");
}
