<?php
include 'config.php';
include 'includes/session.php';
include 'forward_donation.php'; // Include the function

checkSession();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $donor_id = $_SESSION['user_id'];
    $snap_amount = $_POST['snap_amount'];

    $stmt = $pdo->prepare('INSERT INTO snap_donations (donor_id, amount) VALUES (?, ?)');
    $stmt->execute([$donor_id, $snap_amount]);

    echo "Snap donation successful!";
    header('Location: individual_dashboard.php'); // Redirect to the appropriate dashboard
    exit();
}
?>