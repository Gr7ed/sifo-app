<?php
session_start();

function checkSession()
{
    if (!isset($_SESSION['user_role'])) {
        header('Location: login.php');
        exit();
    }
}

function checkRole($role)
{
    if ($_SESSION['user_role'] != $role) {
        header('Location: access_denied.php');
        exit();
    }
}
?>