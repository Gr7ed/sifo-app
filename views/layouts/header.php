<?php
include __DIR__ . '/../../config/config.php';

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check login status
$isLoggedIn = isset($_SESSION['user_id']);
$username = $isLoggedIn ? $_SESSION['username'] : 'Guest';

// Get user type from session
$userType = $_SESSION['user_type'] ?? null;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Alexandria:wght@100..900&family=Alumni+Sans+Pinstripe:ital@0;1&display=swap" />
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>SIFO - Save It For Others</title>
    <link rel="stylesheet" href="/sifo-app/assets/css/styles.css">
    <script src="/sifo-app/assets/js/scripts.js" defer></script>
</head>

<body>
    <header>
        <nav>
            <a href="/sifo-app/views"><?php echo translate('home'); ?></a>

            <?php if ($isLoggedIn): ?>
                <!-- Show links based on user type -->
                <?php if ($userType === 'donor'): ?>
                    <a href="/sifo-app/views/donations/donate.php"><?php echo translate('donations'); ?></a>
                <?php elseif ($userType === 'charity'): ?>
                    <a href="/sifo-app/views/campaigns/view_campaigns.php"><?php echo translate('campaigns'); ?></a>
                <?php endif; ?>

                <!-- Common links for logged-in users -->
                <a href="/sifo-app/views/users/account.php"><?php echo translate('account'); ?></a>
                <a href="/sifo-app/controllers/AuthController.php?action=logout"><?php echo translate('logout'); ?></a>
            <?php else: ?>
                <!-- Links for guests -->
                <a href="/sifo-app/views/auth/register.php"><?php echo translate('sign_up'); ?></a>
                <a href="/sifo-app/views/auth/login.php"><?php echo translate('login'); ?></a>
            <?php endif; ?>

            <a href="/sifo-app/views/feedback/feedback.php"><?php echo translate('feedback'); ?></a>
        </nav>

        <div class="language-switcher">
            <a href="/sifo-app/controllers/change_language.php?lang=en" <?= ($_SESSION['lang'] ?? 'ar') === 'en' ? 'style="pointer-events: none; opacity: 0.6;"' : ''; ?>>En</a> |
            <a href="/sifo-app/controllers/change_language.php?lang=ar" <?= ($_SESSION['lang'] ?? 'ar') === 'ar' ? 'style="pointer-events: none; opacity: 0.6;"' : ''; ?>>عربي</a>
        </div>
    </header>