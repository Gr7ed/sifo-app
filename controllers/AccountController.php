<?php
require_once '../config/config.php';

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'update') {
        session_start();

        if (!isset($_SESSION['user_id'])) {
            header("Location: /sifo-app/views/auth/login.php");
            exit();
        }

        $userId = $_SESSION['user_id'];

        // Get and sanitize form data
        $firstName = htmlspecialchars(trim($_POST['first_name']));
        $lastName = htmlspecialchars(trim($_POST['last_name']));
        $email = htmlspecialchars(trim($_POST['email']));
        $phone = htmlspecialchars(trim($_POST['phone']));
        $city = htmlspecialchars(trim($_POST['city']));
        $district = htmlspecialchars(trim($_POST['district'])); // Assuming "district" is in the form
        $password = $_POST['password'] ?? '';

        // Validate required fields
        if (empty($firstName) || empty($lastName) || empty($email) || empty($phone) || empty($city) || empty($district)) {
            die("All fields except password are required.");
        }

        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            die("Invalid email format.");
        }

        // Validate phone (allow only numbers and optional '+' at the start)
        if (!preg_match('/^\+?[0-9]{10,15}$/', $phone)) {
            die("Invalid phone number format.");
        }

        // Prepare the update query
        $updateQuery = "UPDATE users SET first_name = ?, last_name = ?, email = ?, phone = ?, city = ?, district = ?";
        $params = [$firstName, $lastName, $email, $phone, $city, $district];

        // Include password if provided
        if (!empty($password)) {
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            $updateQuery .= ", password = ?";
            $params[] = $hashedPassword;
        }

        $updateQuery .= " WHERE user_id = ?";
        $params[] = $userId;

        // Execute the query
        $stmt = $db->prepare($updateQuery);
        $stmt->execute($params);

        // Redirect with success message
        header("Location: /sifo-app/views/users/account.php?success=1");
        exit();
    } else {
        header("Location: /sifo-app/views/auth/login.php");
        exit();
    }
} catch (Exception $e) {
    // Log the error for debugging
    error_log("Error updating user account: " . $e->getMessage());

    // Display a generic error message
    die("An error occurred while updating your account. Please try again later.");
}
