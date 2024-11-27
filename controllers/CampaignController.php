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

    /**
     * Create a new campaign
     * @param array $data
     */
    public function createCampaign($data)
    {
        try {
            // Ensure the user is logged in and is a charity
            if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'charity') {
                throw new Exception("Unauthorized access. Only charities can create campaigns.");
            }

            // Validate required fields
            $requiredFields = ['title', 'description', 'target_amount', 'start_date', 'end_date'];
            foreach ($requiredFields as $field) {
                if (empty($data[$field])) {
                    throw new Exception("The field '$field' is required.");
                }
            }

            // Add charity_id to the campaign data
            $data['charity_id'] = $_SESSION['user_id'];

            // Create the campaign
            $this->campaignModel->createCampaign($data);

            // Redirect with success message
            header("Location: /sifo-app/views/campaigns/view_campaigns.php?success=Campaign created successfully.");
            exit();
        } catch (Exception $e) {
            error_log("Error creating campaign: " . $e->getMessage());
            header("Location: /sifo-app/views/campaigns/create_campaign.php?error=" . urlencode($e->getMessage()));
            exit();
        }
    }

    /**
     * Get campaigns by charity ID
     * @param int $charityId
     * @return array
     */
    public function getCampaignsByCharity($charityId)
    {
        return $this->campaignModel->getCampaignsByCharity($charityId);
    }

    /**
     * Contribute to a campaign
     * @param int $campaignId
     * @param int $donorId
     * @param float $amount
     */
    public function contributeToCampaign($campaignId, $donorId, $amount)
    {
        try {
            // Validate the contribution amount
            if (empty($amount) || $amount <= 0) {
                throw new Exception("Invalid contribution amount.");
            }

            // Process the contribution and get the contribution ID
            $contributionId = $this->campaignModel->contributeToCampaign($campaignId, $donorId, $amount);

            // Redirect to success page with the contribution ID
            header("Location: /sifo-app/views/campaigns/contribute_success.php?contribution_id=$contributionId");
            exit();
        } catch (Exception $e) {
            error_log("Error contributing to campaign: " . $e->getMessage());
            // Redirect back to dashboard with error message
            header("Location: /sifo-app/views/dashboard/donor_dashboard.php?error=" . urlencode($e->getMessage()));
            exit();
        }
    }

}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new CampaignController();

    try {
        if ($_GET['action'] === 'create') {
            $controller->createCampaign($_POST);
        } elseif ($_GET['action'] === 'contribute') {
            $controller->contributeToCampaign(
                $_POST['campaign_id'],
                $_SESSION['user_id'], // Ensure the donor is logged in
                $_POST['amount']
            );
        } else {
            throw new Exception("Invalid action.");
        }
    } catch (Exception $e) {
        error_log("Error handling form submission: " . $e->getMessage());
        header("Location: /sifo-app/views/campaigns/view_campaigns.php?error=" . urlencode($e->getMessage()));
        exit();
    }
}
