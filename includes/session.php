<?php

// Start the session if not already active
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Function to check if the user is logged in
function checkSession()
{
    if (!isset($_SESSION['user_role'])) {
        // Redirect to the login page if not logged in
        header('Location: login.php');
        exit();
    }
}

// Function to check the user's role and ensure they have access to the page
function checkRole($role)
{
    if (!isset($_SESSION['user_role'])) {
        // Redirect to the login page if user_role is not set
        header('Location: login.php');
        exit();
    }

    if ($_SESSION['user_role'] != $role) {
        // Redirect to the access denied page if role does not match
        header('Location: access_denied.php');
        exit();
    }
}

// Function to destroy the session and logout the user
function logout()
{
    session_start();
    session_unset();
    session_destroy();
    header('Location: login.php');
    exit();
}
?>