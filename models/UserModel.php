<?php

class UserModel
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * Find a user by email
     * @param string $email
     * @return array|null
     */
    public function findUserByEmail($email)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    /**
     * Find a user by ID
     * @param int $userId
     * @return array|null
     */
    public function findUserById($userId)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE user_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetch();
    }

    /**
     * Save a new user
     * @param array $userData
     * @return int User ID
     */
    public function saveUser($userData)
    {
        $stmt = $this->db->prepare("
        INSERT INTO users (username, email, password, first_name, last_name, phone, user_type, city, district)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
        $stmt->execute([
            $userData['username'],
            $userData['email'],
            $userData['password'],
            $userData['first_name'],
            $userData['last_name'],
            $userData['phone'],
            $userData['user_type'],
            $userData['city'],
            $userData['district']
        ]);

        return $this->db->lastInsertId();
    }

    /**
     * Save additional information for  Donors
     * @param int $userId
     * @param string $name
     */
    public function saveDonorDetails($userId, $userData)
    {
        $stmt = $this->db->prepare("
            INSERT INTO donors (user_id, first_name, last_name, phone)
            VALUES (?, ?, ?, ?)
        ");
        $stmt->execute([
            $userId,
            $userData['first_name'],
            $userData['last_name'],
            $userData['phone']
        ]);
    }



    /**
     * Save additional information for Charity
     * @param int $userId
     * @param string $registrationNumber
     */
    public function saveCharityDetails($userId, $registrationNumber, $acceptedTypes)
    {
        $stmt = $this->db->prepare("
            INSERT INTO charities (user_id, charity_registration_number, accepted_types)
            VALUES (?, ?, ?)
        ");
        $stmt->execute([$userId, $registrationNumber, implode(',', $acceptedTypes)]);
    }
}
