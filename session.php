<?php
session_start(); // Start or resume the session

// Function to check if the user is logged in
function checkSession()
{
    if (!isset($_SESSION['user_role'])) {
        header('Location: login.php'); // Redirect to the login page if not logged in
        exit();
    }
}

// Function to check the user's role and ensure they have access to the page
function checkRole($role)
{
    if ($_SESSION['user_role'] != $role) {
        header('Location: access_denied.php'); // Redirect to the access denied page if role does not match
        exit();
    }
}
?>