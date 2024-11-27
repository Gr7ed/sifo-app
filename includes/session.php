<?php
session_start();

if (isset($_GET['lang'])) {
    $_SESSION['lang'] = $_GET['lang'];
}


// Redirect to login if not authenticated
if (!isset($_SESSION['user_id'])) {
    header("Location: /sifo-app/views/auth/login.php");
    exit();
}

// Optionally include user-specific logic, e.g., user type
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
$user_type = $_SESSION['user_type'];
?>