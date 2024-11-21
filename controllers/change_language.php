<?php
session_start();

// Check if a language is set in the request
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['lang'])) {
    $lang = $_GET['lang'];

    // Validate the language input
    if (in_array($lang, ['en', 'ar'])) {
        $_SESSION['lang'] = $lang;
    }

    // Redirect back to the referring page or the homepage
    $referrer = $_SERVER['HTTP_REFERER'] ?? '/sifo-app/views/index.php';
    header("Location: $referrer");
    exit();
}
