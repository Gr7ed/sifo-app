<?php

class AdminModel
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * Add a new admin
     * @param int $userId
     * @return int Admin ID
     */
    public function addAdmin($userId)
    {
        $stmt = $this->db->prepare("INSERT INTO admins (user_id) VALUES (?)");
        $stmt->execute([$userId]);

        return $this->db->lastInsertId();
    }

    /**
     * Check if a user is an admin
     * @param int $userId
     * @return bool
     */
    public function isAdmin($userId)
    {
        $stmt = $this->db->prepare("SELECT * FROM admins WHERE user_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetch() ? true : false;
    }

    /**
     * Get all admins
     * @return array
     */
    public function getAllAdmins()
    {
        $stmt = $this->db->query("SELECT * FROM admins");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
