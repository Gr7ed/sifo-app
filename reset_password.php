<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
date_default_timezone_set('Asia/Riyadh'); // Replace 'Your/Timezone' with the correct timezone

include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // Display the appropriate form based on the presence of the token
    if (isset($_GET['token'])) {
        $token = $_GET['token'];
        echo "
        <form action='reset_password.php' method='POST'>
            <input type='hidden' name='token' value='$token'>
            <label for='new_password'>New Password:</label>
            <input type='password' id='new_password' name='new_password' required>
            <button type='submit'>Reset Password</button>
        </form>
        ";
    } else {
        echo "
        <form action='reset_password.php' method='POST'>
            <label for='email'>Enter your email address:</label>
            <input type='email' id='email' name='email' required>
            <button type='submit'>Send Reset Link</button>
        </form>
        ";
    }
} elseif ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Handle password reset based on the presence of the token
    if (isset($_POST['token'])) {
        $reset_token = $_POST['token'];
        $new_password = password_hash($_POST['new_password'], PASSWORD_BCRYPT);

        $stmt = $pdo->prepare('SELECT * FROM users WHERE reset_token = ? AND reset_expiration > NOW()');
        $stmt->execute([$reset_token]);
        $user = $stmt->fetch();

        if ($user) {
            $stmt = $pdo->prepare('UPDATE users SET password = ?, reset_token = NULL, reset_expiration = NULL WHERE reset_token = ?');
            $stmt->execute([$new_password, $reset_token]);

            echo "Your password has been successfully reset.";
        } else {
            echo "Invalid or expired reset token.";
            echo "<br>Debug Info: Token: $reset_token, Current Time: " . date("Y-m-d H:i:s");
            echo "<br>Token in DB: " . print_r($user, true);
        }
    } else {
        // Process the reset link request
        $email = $_POST['email'];
        $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ?');
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user) {
            $token = bin2hex(random_bytes(50));
            $reset_expiration = date("Y-m-d H:i:s", strtotime('+1 hour'));

            $stmt = $pdo->prepare('UPDATE users SET reset_token = ?, reset_expiration = ? WHERE email = ?');
            $stmt->execute([$token, $reset_expiration, $email]);

            // Simulate sending email by writing to a file
            $reset_link = "http://localhost/sifo-app/reset_password.php?token=" . $token;
            $subject = "Password Reset Request";
            $message = "Click the link to reset your password: " . $reset_link;
            $headers = "From: no-reply@sifo.com";

            file_put_contents('emails.txt', "To: $email\nSubject: $subject\n\n$message\n\n$headers\n\n---\n\n", FILE_APPEND);

            echo "A reset link has been simulated. Check the emails.txt file.";
        } else {
            echo "Email address not found.";
        }
    }
}
?>