<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];

    $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ?');
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        $reset_token = bin2hex(random_bytes(16));
        $reset_expiration = date('Y-m-d H:i:s', strtotime('+1 hour'));

        $stmt = $pdo->prepare('UPDATE users SET reset_token = ?, reset_expiration = ? WHERE email = ?');
        $stmt->execute([$reset_token, $reset_expiration, $email]);

        $reset_link = "https://yourwebsite.com/reset_password.php?token=$reset_token";
        // Send the reset link via email (simplified for example purposes)
        mail($email, "Password Reset Request", "Click on this link to reset your password: $reset_link");

        echo "Password reset link has been sent to your email.";
    } else {
        echo "No user found with that email address.";
    }
}
?>