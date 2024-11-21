<?php

class DonationModel
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * Save a regular donation (Food or Non-Food)
     * @param array $data
     * @return int Donation ID
     */
    public function saveDonation($data)
    {
        try {
            $stmt = $this->db->prepare("
            INSERT INTO donations (donor_id, type, description, donate_condition, location, pickup_date_time, amount, forwarded_to, created_at, status) 
            VALUES (?, ?, ?, ?, ?, ?, ?, NULL, NOW(), 'Pending')
        ");

            $stmt->execute([
                $data['donor_id'],
                $data['type'],
                $data['description'],
                $data['donate_condition'],
                $data['location'],
                $data['pickup_date_time'],
                $data['amount']
            ]);

            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            error_log("Error saving donation: " . $e->getMessage());
            throw new Exception("Unable to save donation. Please try again.");
        }
    }


    /**
     * Save photos for Non-Food donations
     * @param int $donationId
     * @param array $files
     */
    public function savePhotos($donationId, $files)
    {
        $uploadDir = __DIR__ . '/../assets/uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        foreach ($files['tmp_name'] as $key => $tmpName) {
            $fileName = uniqid() . '-' . $files['name'][$key];
            $filePath = $uploadDir . $fileName;

            if (move_uploaded_file($tmpName, $filePath)) {
                try {
                    $stmt = $this->db->prepare("
                        INSERT INTO donation_photos (donation_id, file_path) 
                        VALUES (?, ?)
                    ");
                    $stmt->execute([$donationId, $filePath]);
                } catch (Exception $e) {
                    error_log("Error saving donation photo: " . $e->getMessage());
                }
            }
        }
    }

    /**
     * Save a snap donation
     * @param array $data
     */
    public function saveSnapDonation($data)
    {
        try {
            $stmt = $this->db->prepare("
                INSERT INTO snap_donors (donor_name, amount, created_at) 
                VALUES (?, ?, NOW())
            ");
            $stmt->execute([
                $data['donor_name'],
                $data['amount']
            ]);
        } catch (Exception $e) {
            error_log("Error saving snap donation: " . $e->getMessage());
            throw new Exception("Unable to save snap donation. Please try again.");
        }
    }

    /**
     * Get the last inserted snap donation ID
     * @return int Snap Donation ID
     */
    public function getLastSnapId()
    {
        return $this->db->lastInsertId();
    }
}
