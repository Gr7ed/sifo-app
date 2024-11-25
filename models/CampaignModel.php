<?php

class CampaignModel
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * Create a new campaign
     * @param array $data
     * @return int Campaign ID
     */
    public function createCampaign($data)
    {
        $stmt = $this->db->prepare("
        INSERT INTO campaigns (charity_id, title, description, target_amount, start_date, end_date)
        VALUES (?, ?, ?, ?, ?, ?)
    ");
        $stmt->execute([
            $data['charity_id'],
            $data['title'],
            $data['description'],
            $data['target_amount'],
            $data['start_date'],
            $data['end_date']
        ]);

        return $this->db->lastInsertId();
    }

    /**
     * Retrieve all campaigns for a specific charity
     * @param int $charityId
     * @return array
     */
    public function getCampaignsByCharity($charityId)
    {
        $stmt = $this->db->prepare("
            SELECT * FROM campaigns WHERE charity_id = ?
        ");
        $stmt->execute([$charityId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get the total number of campaigns for a specific charity
     * @param int $charityId
     * @return int
     */
    public function getTotalCampaigns($charityId)
    {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) as total FROM campaigns WHERE charity_id = ?
        ");
        $stmt->execute([$charityId]);
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    /**
     * Contribute to a campaign by a donor
     * @param int $campaignId
     * @param int $donorId
     * @param float $amount
     * @return bool
     */
    public function contributeToCampaign($campaignId, $donorId, $amount)
    {
        // Ensure campaign exists and is active
        $stmt = $this->db->prepare("
            SELECT target_amount, collected_amount FROM campaigns WHERE campaign_id = ?
        ");
        $stmt->execute([$campaignId]);
        $campaign = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$campaign) {
            throw new Exception("Campaign not found.");
        }

        // Insert contribution
        $stmt = $this->db->prepare("
            INSERT INTO contributions (campaign_id, donor_id, amount, contribution_date)
            VALUES (?, ?, ?, NOW())
        ");
        $stmt->execute([$campaignId, $donorId, $amount]);

        // Update campaign's collected amount
        $stmt = $this->db->prepare("
            UPDATE campaigns SET collected_amount = collected_amount + ? WHERE campaign_id = ?
        ");
        $stmt->execute([$amount, $campaignId]);

        return true;
    }
}

