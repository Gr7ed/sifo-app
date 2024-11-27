<?php
require_once '../config/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'update') {
    try {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            header("Location: /sifo-app/views/auth/login.php");
            exit();
        }

        $userId = $_SESSION['user_id'];

        // Get and sanitize form data
        $firstName = htmlspecialchars(trim($_POST['first_name'] ?? ''));
        $lastName = htmlspecialchars(trim($_POST['last_name'] ?? ''));
        $email = htmlspecialchars(trim($_POST['email'] ?? ''));
        $phone = htmlspecialchars(trim($_POST['phone'] ?? ''));
        $gender = htmlspecialchars(trim($_POST['gender'] ?? ''));
        $city = htmlspecialchars(trim($_POST['city'] ?? ''));
        $district = htmlspecialchars(trim($_POST['district'] ?? ''));
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        $charityName = htmlspecialchars(trim($_POST['charity_name'] ?? ''));
        $charityRegNumber = htmlspecialchars(trim($_POST['charity_registration_number'] ?? ''));

        // Validate password and confirm password match
        if (!empty($password) && $password !== $confirmPassword) {
            die("Passwords do not match. Please ensure the password and confirm password fields are the same.");
        }

        // Validate email format
        if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            die("Invalid email format.");
        }

        // Check if the email already exists for another user
        if (!empty($email)) {
            $checkEmailQuery = "SELECT user_id FROM users WHERE email = ? AND user_id != ?";
            $stmt = $db->prepare($checkEmailQuery);
            $stmt->execute([$email, $userId]);
            if ($stmt->fetchColumn()) {
                die("The email address '{$email}' is already in use. Please use another email.");
            }
        }

        // Validate charity registration number if provided
        if (!empty($charityRegNumber)) {
            $checkRegNumberQuery = "SELECT user_id FROM charities WHERE charity_registration_number = ? AND user_id != ?";
            $stmt = $db->prepare($checkRegNumberQuery);
            $stmt->execute([$charityRegNumber, $userId]);
            if ($stmt->fetchColumn()) {
                die("The charity registration number '{$charityRegNumber}' is already in use. Please use another.");
            }
        }

        // Validate phone format
        if (!empty($phone) && !preg_match('/^\+?[0-9]{10,15}$/', $phone)) {
            die("Invalid phone number format.");
        }

        // Prepare to update the `users` table
        $updateUserQuery = "UPDATE users SET";
        $userParams = [];

        if (!empty($firstName)) {
            $updateUserQuery .= " first_name = ?,";
            $userParams[] = $firstName;
        }

        if (!empty($lastName)) {
            $updateUserQuery .= " last_name = ?,";
            $userParams[] = $lastName;
        }

        if (!empty($email)) {
            $updateUserQuery .= " email = ?,";
            $userParams[] = $email;
        }

        if (!empty($phone)) {
            $updateUserQuery .= " phone = ?,";
            $userParams[] = $phone;
        }

        if (!empty($city)) {
            $updateUserQuery .= " city = ?,";
            $userParams[] = $city;
        }

        if (!empty($district)) {
            $updateUserQuery .= " district = ?,";
            $userParams[] = $district;
        }

        if (!empty($password)) {
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            $updateUserQuery .= " password = ?,";
            $userParams[] = $hashedPassword;
        }

        $updateUserQuery = rtrim($updateUserQuery, ',') . " WHERE user_id = ?";
        $userParams[] = $userId;

        $stmt = $db->prepare($updateUserQuery);
        $stmt->execute($userParams);

        // Fetch the user type
        $user = $db->prepare("SELECT user_type FROM users WHERE user_id = ?");
        $user->execute([$userId]);
        $userType = $user->fetchColumn();

        // Update the `donors` table if the user is a donor
        if ($userType === 'donor') {
            $updateDonorQuery = "UPDATE donors SET";
            $donorParams = [];

            if (!empty($firstName)) {
                $updateDonorQuery .= " first_name = ?,";
                $donorParams[] = $firstName;
            }

            if (!empty($lastName)) {
                $updateDonorQuery .= " last_name = ?,";
                $donorParams[] = $lastName;
            }

            if (!empty($phone)) {
                $updateDonorQuery .= " phone = ?,";
                $donorParams[] = $phone;
            }

            if (!empty($city)) {
                $updateDonorQuery .= " city = ?,";
                $donorParams[] = $city;
            }

            if (!empty($district)) {
                $updateDonorQuery .= " district = ?,";
                $donorParams[] = $district;
            }

            if (!empty($gender)) {
                $updateDonorQuery .= " gender = ?,";
                $donorParams[] = $gender;
            }

            $updateDonorQuery = rtrim($updateDonorQuery, ',') . " WHERE user_id = ?";
            $donorParams[] = $userId;

            $stmt = $db->prepare($updateDonorQuery);
            $stmt->execute($donorParams);
        }

        // Update the `charities` table if the user is a charity
        if ($userType === 'charity') {
            $updateCharityQuery = "UPDATE charities SET";
            $charityParams = [];

            if (!empty($city)) {
                $updateCharityQuery .= " city = ?,";
                $charityParams[] = $city;
            }

            if (!empty($district)) {
                $updateCharityQuery .= " district = ?,";
                $charityParams[] = $district;
            }

            if (!empty($phone)) {
                $updateCharityQuery .= " phone = ?,";
                $charityParams[] = $phone;
            }

            if (!empty($charityName)) {
                $updateCharityQuery .= " charity_name = ?,";
                $charityParams[] = $charityName;
            }

            if (!empty($charityRegNumber)) {
                $updateCharityQuery .= " charity_registration_number = ?,";
                $charityParams[] = $charityRegNumber;
            }

            $updateCharityQuery = rtrim($updateCharityQuery, ',') . " WHERE user_id = ?";
            $charityParams[] = $userId;

            $stmt = $db->prepare($updateCharityQuery);
            $stmt->execute($charityParams);
        }

        header("Location: /sifo-app/views/users/update_success.php");
        exit();
    } catch (Exception $e) {
        error_log("Error updating user account: " . $e->getMessage());
        die("An error occurred while updating your account. Please try again later.");
    }
} else {
    header("Location: /sifo-app/views/auth/login.php");
    exit();
}
