<?php

class UserModel
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function isUsernameExists($username)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
        $stmt->execute([$username]);
        return $stmt->fetchColumn() > 0; // Returns true if the username exists
    }

    public function isEmailExists($email)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetchColumn() > 0; // Returns true if the email exists
    }

    public function isCharityRegistrationNumberExists($registrationNumber)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM charities WHERE charity_registration_number = ?");
        $stmt->execute([$registrationNumber]);
        return $stmt->fetchColumn() > 0; // Returns true if the registration number exists
    }


    /**
     * Find a user by identifier
     * @param string $identifier
     * 
     */
    public function findUserByIdentifier($identifier)
    {
        $stmt = $this->db->prepare("
            SELECT * 
            FROM users 
            WHERE email = ? OR username = ?
        ");
        $stmt->execute([$identifier, $identifier]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
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
            INSERT INTO donors (user_id, first_name, last_name, phone, city, district)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $userId,
            $userData['first_name'],
            $userData['last_name'],
            $userData['phone'],
            $userData['city'],
            $userData['district']
        ]);
    }



    /**
     * Save additional information for Charity
     * @param int $userId
     * @param string $registrationNumber
     */
    public function saveCharityDetails($userId, $registrationNumber, $charityName, $acceptedTypes, $city, $district)
    {
        $stmt = $this->db->prepare("
            INSERT INTO charities (user_id, charity_registration_number, charity_name, accepted_types, city, district)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([$userId, $registrationNumber, $charityName, implode(',', $acceptedTypes), $city, $district]);
    }
}
