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
        // Fetch the campaign details and ensure it exists
        $stmt = $this->db->prepare("
        SELECT target_amount, COALESCE(collected_amount, 0) AS collected_amount 
        FROM campaigns 
        WHERE campaign_id = ?
    ");
        $stmt->execute([$campaignId]);
        $campaign = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$campaign) {
            throw new Exception("Campaign not found.");
        }

        // Check if the contribution exceeds the target amount
        $remainingAmount = $campaign['target_amount'] - $campaign['collected_amount'];
        if ($amount > $remainingAmount) {
            throw new Exception("The contribution amount exceeds the remaining target amount.");
        }

        // Insert the contribution
        $stmt = $this->db->prepare("
        INSERT INTO contributions (campaign_id, donor_id, amount, contribution_date)
        VALUES (?, ?, ?, NOW())
    ");
        $result = $stmt->execute([$campaignId, $donorId, $amount]);
        if (!$result) {
            throw new Exception("Failed to insert contribution.");
        }
        // Get the last inserted ID
        $contributionId = $this->db->lastInsertId();
        if (!$contributionId) {
            throw new Exception("Failed to retrieve the contribution ID.");
        }
        // Update the campaign's collected amount
        $stmt = $this->db->prepare("
        UPDATE campaigns 
        SET collected_amount = collected_amount + ? 
        WHERE campaign_id = ?
    ");
        $stmt->execute([$amount, $campaignId]);

        // Return the contribution ID for further use
        return $contributionId;
    }



    /**
     * Retrieve a limited number of available campaigns for contribution
     * @param int $limit Number of campaigns to fetch
     * @return array
     */
    public function getAvailableCampaigns($limit = 5)
    {
        $stmt = $this->db->prepare("
        SELECT 
            campaigns.campaign_id, 
            campaigns.title, 
            campaigns.description, 
            campaigns.target_amount, 
            COALESCE(SUM(contributions.amount), 0) AS collected_amount
        FROM campaigns
        LEFT JOIN contributions ON campaigns.campaign_id = contributions.campaign_id
        WHERE campaigns.end_date >= NOW() 
        GROUP BY campaigns.campaign_id, campaigns.title, campaigns.description, campaigns.target_amount
        ORDER BY campaigns.start_date DESC
        LIMIT ?
    ");
        $stmt->execute([$limit]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getAllCampaigns()
    {
        $stmt = $this->db->prepare("
        SELECT 
            campaigns.campaign_id, 
            campaigns.title, 
            campaigns.description, 
            campaigns.target_amount, 
            COALESCE(SUM(contributions.amount), 0) AS collected_amount,
            charities.charity_name
        FROM campaigns
        LEFT JOIN contributions ON campaigns.campaign_id = contributions.campaign_id
        LEFT JOIN charities ON campaigns.charity_id = charities.user_id
        WHERE campaigns.end_date >= NOW() 
        GROUP BY 
            campaigns.campaign_id, 
            campaigns.title, 
            campaigns.description, 
            campaigns.target_amount, 
            charities.charity_name
        ORDER BY campaigns.start_date DESC
    ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


}
