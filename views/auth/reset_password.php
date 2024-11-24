<?php
include_once __DIR__ . '/../../config/config.php';
include_once __DIR__ . '/../layouts/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    global $db;
    $email = $_POST['email'] ?? null;

    if ($email) {
        // Check if the email exists
        $stmt = $db->prepare("SELECT user_id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user) {
            // Generate a reset token
            $resetToken = bin2hex(random_bytes(32));
            $resetExpiration = date('Y-m-d H:i:s', strtotime('+1 hour'));

            // Store token and expiration in the database
            $stmt = $db->prepare("UPDATE users SET reset_token = ?, reset_expiration = ? WHERE email = ?");
            $stmt->execute([$resetToken, $resetExpiration, $email]);

            // Save the reset link to a local file
            $resetLink = "http://localhost/sifo-app/views/auth/reset_password_form.php?token=$resetToken";
            $filePath = __DIR__ . '/../../assets/reset_links.txt';
            file_put_contents($filePath, "Reset link for $email: $resetLink\n", FILE_APPEND);

            echo "<p>A password reset link has been generated and saved locally. Please check the <code>assets/reset_links.txt</code> file.</p>";
        } else {
            echo "<p>Email not found in the system.</p>";
        }
    } else {
        echo "<p>Please provide a valid email address.</p>";
    }
}
?>

<h1><?php echo translate('reset_password'); ?></h1>
<form method="POST">
    <label for="email"><?php echo translate('reg_email'); ?></label>
    <input type="email" name="email" id="email" required>
    <button type="submit"><?php echo translate('send_reset_link'); ?></button>
</form>

<?php include __DIR__ . '/../layouts/footer.php'; ?>