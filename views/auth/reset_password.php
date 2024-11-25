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

        } else {
            echo "<p>Email not found in the system.</p>";
        }
    } else {
        echo "<p>Please provide a valid email address.</p>";
    }
}
?>

<style>
    main {
        padding: 20px;
        max-width: 500px;
        margin: 0 auto;
        background-color: #faf7f0;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        font-family: 'Alexandria', sans-serif;
    }

    h1 {
        text-align: center;
        margin-bottom: 20px;
        color: #4a4947;
    }

    form {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    label {
        font-size: 14px;
        color: #4a4947;
        font-weight: bold;
    }

    input {
        padding: 10px;
        font-size: 14px;
        border: 1px solid #d8d2c2;
        border-radius: 5px;
        width: 100%;
        background-color: #fff;
    }

    button {
        margin-top: 20px;
        padding: 12px;
        background-color: #4a4947;
        color: #faf7f0;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
        transition: background-color 0.3s ease;
    }

    button:hover {
        background-color: #fccd2a;
    }

    .info-message {
        margin-top: 15px;
        color: #4a4947;
        font-size: 14px;
        line-height: 1.6;
    }

    .info-message code {
        background-color: #e0e0e0;
        padding: 3px 5px;
        border-radius: 3px;
        font-size: 90%;
    }
</style>

<main>
    <h1><?php echo translate('reset_password'); ?></h1>
    <form method="POST">
        <label for="email"><?php echo translate('reg_email'); ?></label>
        <input type="email" name="email" id="email" required placeholder="<?php echo translate('enter_email'); ?>">
        <button type="submit"><?php echo translate('send_reset_link'); ?></button>
    </form>
    <?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
        <div class="info-message">
            <?php if (isset($resetLink)): ?>
                <p><code><>A password reset link has been generated and saved locally. Please check the <code>assets/reset_links.txt</code>
                    file.</code></p>
            <?php else: ?>
                <p>Email not found in the system.</p>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</main>

<?php include __DIR__ . '/../layouts/footer.php'; ?>