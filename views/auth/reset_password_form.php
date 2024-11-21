<?php
include __DIR__ . '/../../config/config.php';
include __DIR__ . '/../layouts/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    global $db;
    $token = $_POST['token'] ?? null;
    $newPassword = $_POST['password'] ?? null;

    if ($token && $newPassword) {
        // Check the token validity
        $stmt = $db->prepare("SELECT user_id FROM users WHERE reset_token = ? AND reset_expiration > NOW()");
        $stmt->execute([$token]);
        $user = $stmt->fetch();

        if ($user) {
            // Update the password
            $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
            $stmt = $db->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_expiration = NULL WHERE reset_token = ?");
            $stmt->execute([$hashedPassword, $token]);

            echo "<p>Your password has been reset successfully. <a href='/sifo-app/views/auth/login.php'>Login</a></p>";
        } else {
            echo "<p>Invalid or expired token.</p>";
        }
    } else {
        echo "<p>Please provide a valid token and password.</p>";
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['token'])) {
    $token = $_GET['token'];
    ?>
    <h1>Set New Password</h1>
    <form method="POST">
        <input type="hidden" name="token" value="<?= htmlspecialchars($token); ?>">
        <label for="password">New Password:</label>
        <input type="password" name="password" id="password" required>
        <button type="submit">Reset Password</button>
    </form>
    <?php
} else {
    echo "<p>Invalid request.</p>";
}

include __DIR__ . '/../layouts/footer.php';
?>