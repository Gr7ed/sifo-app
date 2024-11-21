<?php
require_once '../config/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_GET['action'] === 'submit') {
    session_start();
    $db = $GLOBALS['db']; // Use the database connection

    // Get form data
    $userId = $_SESSION['user_id'] ?? null; // Logged-in user's ID or NULL for guests
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $message = htmlspecialchars(trim($_POST['message']));

    // Validate required fields
    if (empty($name) || empty($email) || empty($message)) {
        die('All fields are required.');
    }

    // Insert feedback into the database
    $stmt = $db->prepare("
        INSERT INTO feedback (user_id, name, email, message) 
        VALUES (?, ?, ?, ?)
    ");
    $stmt->execute([$userId, $name, $email, $message]);

    // Redirect to a thank-you page or show success message
    header("Location: /sifo-app/views/feedback/thank_you.php");
    exit();
}
