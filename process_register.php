<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start(); // Start a session if not already active
}

include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate and sanitize inputs
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $user_type = filter_input(INPUT_POST, 'user_type', FILTER_SANITIZE_STRING);
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING) ?? null;
    $organization_name = filter_input(INPUT_POST, 'organization_name', FILTER_SANITIZE_STRING) ?? null;
    $charity_registration_number = filter_input(INPUT_POST, 'charity_registration_number', FILTER_SANITIZE_STRING) ?? null;
    $donation_types = $_POST['donation_types'] ?? [];

    if (!$email) {
        $_SESSION['error'] = "Invalid email address.";
        header('Location: register.php');
        exit();
    }

    $pdo->beginTransaction();

    try {
        $stmt = $pdo->prepare('INSERT INTO users (username, email, password, user_type) VALUES (?, ?, ?, ?)');
        $stmt->execute([$username, $email, $password, $user_type]);

        $user_id = $pdo->lastInsertId();

        if ($user_type == 'Individual') {
            $stmt = $pdo->prepare('INSERT INTO individual_donors (user_id, name) VALUES (?, ?)');
            $stmt->execute([$user_id, $name]);
        } elseif ($user_type == 'Organization') {
            $stmt = $pdo->prepare('INSERT INTO organization_donors (user_id, organization_name) VALUES (?, ?)');
            $stmt->execute([$user_id, $organization_name]);
        } elseif ($user_type == 'Charity') {
            $stmt = $pdo->prepare('INSERT INTO charity_beneficiaries (user_id, charity_registration_number) VALUES (?, ?)');
            $stmt->execute([$user_id, $charity_registration_number]);

            foreach ($donation_types as $type) {
                $stmt = $pdo->prepare('INSERT INTO charity_preferences (charity_id, donation_type) VALUES (?, ?)');
                $stmt->execute([$user_id, $type]);
            }
        }

        $pdo->commit();
        $_SESSION['success'] = "Registration successful!";
        header('Location: login.php');
        exit();
    } catch (Exception $e) {
        $pdo->rollBack();
        $_SESSION['error'] = "Registration failed: " . $e->getMessage();
        header('Location: register.php');
        exit();
    }
}
?>