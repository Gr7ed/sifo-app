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
            // Insert the donation
            $stmt = $this->db->prepare("
            INSERT INTO donations (donor_id, type, description, donate_condition, city, district, pickup_date_time, amount, created_at, status) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), 'Available')
        ");

            $stmt->execute([
                $data['donor_id'],
                $data['type'],
                $data['description'],
                $data['donate_condition'],
                $data['city'],
                $data['district'],
                $data['pickup_date_time'],
                $data['amount'],
            ]);

            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            error_log("Error saving donation: " . $e->getMessage());
            throw new Exception("Unable to save donation. Please try again.." . $e->getMessage());
        }
    }

    public function updateDonationStatus($donationId, $newStatus, $charityId = null)
    {
        try {
            if ($newStatus === 'Pending' && $charityId !== null) {
                $stmt = $this->db->prepare("UPDATE donations SET status = ?, forwarded_to = ? WHERE donation_id = ?");
                $stmt->execute([$newStatus, $charityId, $donationId]);
            } else {
                $stmt = $this->db->prepare("UPDATE donations SET status = ? WHERE donation_id = ?");
                $stmt->execute([$newStatus, $donationId]);
            }
        } catch (PDOException $e) {
            error_log("Error updating donation status: " . $e->getMessage());
            throw new Exception("Unable to update donation status. Please try again later.");
        }
    }

    public function getDonationById($donationId)
    {
        try {
            $stmt = $this->db->prepare("
            SELECT 
                donations.*,
                donors.first_name AS donor_name,
                donors.phone AS donor_phone
            FROM 
                donations
            LEFT JOIN 
                donors ON donations.donor_id = donors.user_id
            WHERE 
                donation_id = ?
        ");
            $stmt->execute([$donationId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching donation: " . $e->getMessage());
            throw new Exception("Unable to fetch donation details. Please try again later.");
        }
    }



    public function getDonationsByStatus($status, $city, $type)
    {
        try {
            $stmt = $this->db->prepare("
            SELECT 
                donations.*,
                donors.first_name AS donor_name,
                donors.phone AS donor_phone,
                donors.city AS donor_city,
                donors.district AS donor_district
            FROM 
                donations
            LEFT JOIN 
                donors ON donations.donor_id = donors.user_id
            WHERE 
                donations.status = ? 
                AND donations.city = ? 
                AND donations.type = ?
        ");
            $stmt->execute([$status, $city, $type]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching donations by status: " . $e->getMessage());
            throw new Exception("Unable to retrieve donations. Please try again later.");
        }
    }
    public function getRecentDonationsByCharity($charityId, $limit = 4)
    {
        $stmt = $this->db->prepare("
            SELECT d.donation_id, d.description, d.status, d.pickup_date_time, d.city, d.district, u.username as donor_name, u.phone as donor_phone
            FROM donations d
            INNER JOIN users u ON d.donor_id = u.user_id
            WHERE d.forwarded_to = ?
            ORDER BY d.pickup_date_time DESC
            LIMIT ?
        ");
        $stmt->bindValue(1, $charityId, PDO::PARAM_INT);
        $stmt->bindValue(2, $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getTotalDonationsByCharity($charityId, $status = null)
    {
        $query = "SELECT COUNT(*) as total FROM donations WHERE forwarded_to = ?";
        $params = [$charityId];

        if ($status) {
            $query .= " AND status = ?";
            $params[] = $status;
        }

        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function getRecentDonationsByDonor($userId, $limit)
    {
        $stmt = $this->db->prepare("
        SELECT 
        donations.created_at AS date,
        donations.description AS item,
        charities.charity_name AS recipient,
        donations.status
        FROM donations
        LEFT JOIN charities ON donations.forwarded_to = charities.user_id
        WHERE donations.donor_id = ?
        ORDER BY donations.created_at DESC
        LIMIT ?");
        $stmt->execute([$userId, $limit]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getDonationsByDonor($userId)
    {
        $stmt = $this->db->prepare("
        SELECT 
        donations.*,
        charities.charity_name AS recipient
        FROM donations
        LEFT JOIN charities ON donations.forwarded_to = charities.user_id
        WHERE donations.donor_id = ?
        ORDER BY donations.created_at DESC");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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
            return $this->db->lastInsertId();
        } catch (Exception $e) {
            error_log("Error saving snap donation: " . $e->getMessage());
            throw new Exception("Unable to save snap donation. Please try again.");
        }
    }

}
