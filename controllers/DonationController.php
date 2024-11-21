<?php
require_once '../config/config.php';
require_once '../models/DonationModel.php';


class DonationController
{
    private $donationModel;

    public function __construct()
    {
        global $db; // Use the global PDO instance from config.php
        $this->donationModel = new DonationModel($db);
    }

    public function processDonation($data, $files)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            header("Location: /sifo-app/views/auth/login.php");
            exit();
        }

        // Validate required fields
        $requiredFields = ['type', 'description', 'donate_condition', 'location', 'pickup_date_time', 'amount'];
        foreach ($requiredFields as $field) {
            if (empty($data[$field])) {
                die("Field '{$field}' is required.");
            }
        }

        // Assign the logged-in user's ID
        $data['donor_id'] = $_SESSION['user_id'];

        // Save the donation
        try {
            $donationId = $this->donationModel->saveDonation($data);

            // Handle file uploads for Non-Food donations
            if ($data['type'] === 'Non-Food' && !empty($files['photos']['name'][0])) {
                $this->donationModel->savePhotos($donationId, $files['photos']);
            }

            header("Location: /sifo-app/views/donations/receipt.php?donation_id=$donationId");
            exit();
        } catch (Exception $e) {
            error_log("Error processing donation: " . $e->getMessage());
            die("An error occurred while processing your donation. Please try again later.");
        }
    }


    public function processSnapDonation($data)
    {
        $this->donationModel->saveSnapDonation($data);

        header("Location: /sifo-app/views/donations/receipt.php?snap_id=" . $this->donationModel->getLastSnapId());
        exit();
    }
}

// session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new DonationController();

    if ($_GET['action'] === 'donate') {
        $controller->processDonation($_POST, $_FILES);
    } elseif ($_GET['action'] === 'snap') {
        $controller->processSnapDonation($_POST);
    }
}
