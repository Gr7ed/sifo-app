<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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
    }
} else if (isset($_GET['token'])) {
    $reset_token = $_GET['token'];
    echo "
    <form action='reset_password.php' method='POST'>
        <input type='hidden' name='token' value='$reset_token'>
        <label for='new_password'>New Password:</label>
        <input type='password' name='new_password' required>
        <button type='submit'>Reset Password</button>
    </form>
    ";
} else {
    echo "Invalid request.";
}
?>