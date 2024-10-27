<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $user_type = $_POST['user_type'];
    $name = $_POST['name'] ?? null;
    $organization_name = $_POST['organization_name'] ?? null;
    $charity_registration_number = $_POST['charity_registration_number'] ?? null;
    $donation_types = $_POST['donation_types'] ?? [];
    $accepted_conditions = $_POST['accepted_conditions'] ?? null;

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
                $stmt = $pdo->prepare('INSERT INTO charity_preferences (charity_id, donation_type, accepted_conditions) VALUES (?, ?, ?)');
                $stmt->execute([$user_id, $type, $accepted_conditions]);
            }
        }

        $pdo->commit();
        echo "Registration successful!";
    } catch (Exception $e) {
        $pdo->rollBack();
        throw $e;
    }
}
?>