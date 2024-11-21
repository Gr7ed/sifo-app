<?php
include __DIR__ . '/../../config/config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


$isLoggedIn = isset($_SESSION['user_id']);
$username = $isLoggedIn ? $_SESSION['username'] : 'Guest';

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIFO - Save It For Others</title>
    <link rel="stylesheet" href="/assets/css/styles.css">
    <script src="/sifo-app/assets/js/scripts.js" defer></script>
</head>

<body>
    <header>

        <nav>
            <a href="/sifo-app/views">Home</a>
            <a href="/sifo-app/views/auth/register.php">Register</a>
            <a href="/sifo-app/views/auth/login.php">Login</a>
            <a href="/sifo-app/views/users/account.php">My Account</a>
            <a href="/sifo-app/views/feedback/feedback.php">Feedback</a>
        </nav>

        <div class="language-switcher">
            <a href="/sifo-app/controllers/change_language.php?lang=en" <?= ($_SESSION['lang'] ?? 'ar') === 'en' ? 'style="pointer-events: none; opacity: 0.6;"' : ''; ?>>En</a> |
            <a href="/sifo-app/controllers/change_language.php?lang=ar" <?= ($_SESSION['lang'] ?? 'ar') === 'ar' ? 'style="pointer-events: none; opacity: 0.6;"' : ''; ?>>عربي</a>
        </div>

    </header>