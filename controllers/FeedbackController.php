<?php
require_once '../config/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_GET['action'] === 'submit') {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $db = $GLOBALS['db']; // Use the database connection

    // Get form data
    $userId = $_SESSION['user_id'] ?? null; // Logged-in user's ID or NULL for guests
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $subject = htmlspecialchars(trim($_POST['subject']));
    $message = htmlspecialchars(trim($_POST['message']));

    // Validate required fields
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        die('All fields are required.');
    }

    // Insert feedback into the database
    $stmt = $db->prepare("
        INSERT INTO feedback (user_id, name, email, subject, message) 
        VALUES (?, ?, ?, ?, ?)
    ");
    $stmt->execute([$userId, $name, $email, $subject, $message]);

    // Redirect to a thank-you page or show success message
    header("Location: /sifo-app/views/feedback/thank_you.php");
    exit();
}
