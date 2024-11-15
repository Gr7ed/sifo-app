<?php
include 'config.php';
include 'includes/session.php';
include 'forward_donation.php'; // Include the function

checkSession();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $donor_id = $_SESSION['user_id'];
    $donation_type = $_POST['donation_type'];
    $food_description = $_POST['food_description'] ?? null;
    $non_food_description = $_POST['non_food_description'] ?? null;
    $non_food_condition = $_POST['non_food_condition'] ?? null;

    if ($donation_type == 'Food') {
        $stmt = $pdo->prepare('INSERT INTO donations (donor_id, donation_type, item_description) VALUES (?, ?, ?)');
        $stmt->execute([$donor_id, 'Food', $food_description]);
    } elseif ($donation_type == 'Non-Food') {
        $stmt = $pdo->prepare('INSERT INTO donations (donor_id, donation_type, item_description, item_condition) VALUES (?, ?, ?, ?)');
        $stmt->execute([$donor_id, 'Non-Food', $non_food_description, $non_food_condition]);
    }

    $donation_id = $pdo->lastInsertId();
    forwardDonation($donation_id, $donation_type); // Only pass $donation_id and $donation_type
}
?>