<?php
require_once '../config/config.php';
require_once '../models/CampaignModel.php';

class CampaignController
{
    private $campaignModel;

    public function __construct()
    {
        global $db; // Use the global PDO instance from config.php
        $this->campaignModel = new CampaignModel($db);
        // Start the session if not already active
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function createCampaign($data)
    {
        try {
            // Ensure the user is logged in and is a charity
            if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'charity') {
                throw new Exception("Unauthorized access. Only charities can create campaigns.");
            }

            // Add charity_id to the campaign data
            $data['charity_id'] = $_SESSION['user_id'];

            $this->campaignModel->createCampaign($data);

            // Redirect to the campaigns page
            header("Location: /sifo-app/views/campaigns/view_campaigns.php?success=1");
            exit();
        } catch (Exception $e) {
            error_log("Error creating campaign: ");
            die("An error occurred while creating the campaign. Please try again." . $e->getMessage());
        }
    }

    public function getCampaignsByCharity($charityId)
    {
        return $this->campaignModel->getCampaignsByCharity($charityId);
    }
}

// // Handle form submissions
// session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new CampaignController();

    if ($_GET['action'] === 'create') {
        $controller->createCampaign($_POST);
    }
}
