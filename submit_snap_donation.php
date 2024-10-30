<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $snap_amount = $_POST['snap_amount'];

    // Simulate a payment process
    $transaction_id = uniqid('txn_');

    // Save the donation to the database
    $stmt = $pdo->prepare('INSERT INTO snap_donations (name, email, amount, transaction_id) VALUES (?, ?, ?, ?)');
    $stmt->execute([$name, $email, $snap_amount, $transaction_id]);

    echo "Snap donation successful!";
}
?>