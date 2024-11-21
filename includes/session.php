<?php
session_start();

if (isset($_GET['lang'])) {
    $_SESSION['lang'] = $_GET['lang'];
}

// Default to English if no session is set
$lang = $_SESSION['lang'] ?? 'en';
// Redirect to login if not authenticated
if (!isset($_SESSION['user_id'])) {
    header("Location: /views/auth/login.php");
    exit();
}

// Optionally include user-specific logic, e.g., user type
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
$user_type = $_SESSION['user_type'];
?>