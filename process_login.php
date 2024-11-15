<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start(); // Start a session if not already active
}

include 'config.php';

// Your login processing logic
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    if ($email && $password) {
        $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ?');
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['user_role'] = $user['user_type'];

            // Redirect based on user role
            switch ($user['user_type']) {
                case 'Individual':
                    header('Location: individual_dashboard.php');
                    break;
                case 'Organization':
                    header('Location: organization_dashboard.php');
                    break;
                case 'Charity':
                    header('Location: charity_dashboard.php');
                    break;
                case 'Admin':
                    header('Location: admin_dashboard.php');
                    break;
                default:
                    header('Location: login.php');
                    break;
            }
            exit();
        } else {
            $_SESSION['error'] = "Invalid login credentials!";
            header('Location: login.php');
            exit();
        }
    } else {
        $_SESSION['error'] = "Please fill in all required fields!";
        header('Location: login.php');
        exit();
    }
}
?>