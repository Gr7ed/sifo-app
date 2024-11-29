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

?>