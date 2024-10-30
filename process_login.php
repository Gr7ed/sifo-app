<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start(); // Start a session if not already active
}

include 'config.php';

// Your login processing logic
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ?');
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['user_role'] = $user['user_type'];

        if ($user['user_type'] == 'Individual') {
            header('Location: individual_dashboard.php');
        } elseif ($user['user_type'] == 'Organization') {
            header('Location: organization_dashboard.php');
        } elseif ($user['user_type'] == 'Charity') {
            header('Location: charity_dashboard.php');
        } elseif ($user['user_type'] == 'Admin') {
            header('Location: admin_dashboard.php');
        }
        exit();
    } else {
        echo "Invalid login credentials!";
    }
}
?>