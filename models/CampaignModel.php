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
}
