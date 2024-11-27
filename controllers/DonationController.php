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

        try {
            // Determine the type of donation and normalize data
            if ($data['type'] === 'Food') {
                $normalizedData = [
                    'donor_id' => $_SESSION['user_id'],
                    'type' => $data['type'],
                    'description' => $data['description_food'],
                    'donate_condition' => $data['donate_condition_food'],
                    'city' => $data['city_food'],
                    'district' => $data['district_food'],
                    'pickup_date_time' => $data['pickup_date_time_food'],
                    'amount' => $data['amount_food']
                ];
            } elseif ($data['type'] === 'Non-Food') {
                $normalizedData = [
                    'donor_id' => $_SESSION['user_id'],
                    'type' => $data['type'],
                    'description' => $data['description_nonfood'],
                    'donate_condition' => $data['donate_condition_nonfood'],
                    'city' => $data['city_nonfood'],
                    'district' => $data['district_nonfood'],
                    'pickup_date_time' => $data['pickup_date_time_nonfood'],
                    'amount' => $data['amount_nonfood']
                ];
            } else {
                throw new Exception("Invalid donation type selected.");
            }
            foreach ($normalizedData as $field) {
                if (empty([$field])) {
                    die("Field '{$field}' is required.");
                }
            }

            // Save the donation
            $donationId = $this->donationModel->saveDonation($normalizedData);

            // Handle file uploads for Non-Food donations
            if ($data['type'] === 'Non-Food' && !empty($files['photos']['name'][0])) {
                $this->donationModel->savePhotos($donationId, $files['photos']);
            }

            // Redirect to receipt page
            header("Location: /sifo-app/views/donations/receipt.php?donation_id=$donationId");
            exit();

        } catch (Exception $e) {
            error_log("Error processing donation: " . $e->getMessage());
            die("An error occurred while processing your donation. Please try again later.");
        }
    }
    public function updateDonationStatus($donationId, $newStatus)
    {
        try {
            session_start();

            // Ensure the user is logged in and is a charity
            if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'charity') {
                die("Unauthorized access.");
            }

            $charityId = $_SESSION['user_id'];

            // Get donation details
            $donation = $this->donationModel->getDonationById($donationId);

            if (!$donation) {
                die("Donation not found.");
            }

            // Status transition logic
            if ($newStatus === 'Pending' && $donation['status'] === 'Available') {
                // Charity receives the donation
                $this->donationModel->updateDonationStatus($donationId, $newStatus, $charityId);
            } elseif ($newStatus === 'Delivered' && $donation['status'] === 'Pending' && $donation['forwarded_to'] == $charityId) {
                // Charity marks the donation as delivered
                $this->donationModel->updateDonationStatus($donationId, $newStatus);
            } else {
                die("Invalid status transition.");
            }

            // Redirect to dashboard
            header("Location: /sifo-app/views/donations/charity_donations.php?status=updated");
            exit();
        } catch (Exception $e) {
            error_log("Error updating donation status: " . $e->getMessage());
            die("An error occurred while updating the donation status. Please try again.");
        }
    }


    public function processSnapDonation($data)
    {
        $this->donationModel->saveSnapDonation($data);

        header("Location: /sifo-app/views/donations/receipt.php?snap_id=" . $this->donationModel->getLastSnapId());
        exit();
    }
    public function viewDonationsByStatus($status, $city, $type)
    {
        try {
            return $this->donationModel->getDonationsByStatus($status, $city, $type);
        } catch (Exception $e) {
            die("Error: " . $e->getMessage());
        }
    }

}



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new DonationController();

    if ($_GET['action'] === 'donate') {
        $controller->processDonation($_POST, $_FILES);
    } elseif ($_GET['action'] === 'snap') {
        $controller->processSnapDonation($_POST);
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_GET['action'] === 'update_status') {
    $donationId = htmlspecialchars($_POST['donation_id']);
    $newStatus = htmlspecialchars($_POST['new_status']);

    $controller = new DonationController();
    $controller->updateDonationStatus($donationId, $newStatus);
}

