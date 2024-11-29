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
            padding: 12px;
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
    </style>

    <main>
        <h1><?php echo translate('set-new-password'); ?></h1>
        <form method="POST">
            <input type="hidden" name="token" value="<?= htmlspecialchars($token); ?>">
            <label for="password"><?php echo translate('new-password-reset'); ?>:</label>
            <input type="password" name="password" id="password" required
                placeholder="<?php echo translate('enter-new-password'); ?>">
            <button type="submit"><?php echo translate('reset-password'); ?></button>
        </form>
    </main>
    <?php
} else {
    echo "<p>Invalid request.</p>";
}

include __DIR__ . '/../layouts/footer.php';
?>