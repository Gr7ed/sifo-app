<?php
include __DIR__ . '/../../config/config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$isLoggedIn = isset($_SESSION['user_id']);
$userType = $isLoggedIn ? ($_SESSION['user_type'] ?? 'guest') : 'guest';
$username = $isLoggedIn ? $_SESSION['username'] : 'Guest';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Alexandria:wght@100..900&family=Alumni+Sans+Pinstripe:ital@0;1&display=swap"
        rel="stylesheet" />
    <title>SIFO - Save It For Others</title>
    <style>
        /* General Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: "Alexandria", sans-serif;
            background-color: #faf7f0;
            color: #4a4947;
        }

        /* Header Section */
        .header {
            position: relative;
            background-color: #faf7f0;
            padding: 20px;
            height: 98px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            text-align: center;
        }

        .header .site-title {
            display: flex;
            align-items: center;
            gap: 30px;
            font-family: "Alumni Sans Pinstripe", sans-serif;
        }

        .header .site-part {
            color: #4a4947;
            font-size: 36px;
            font-weight: 1000;
            letter-spacing: 25px;
        }

        .header .site-abbr {
            color: #4a4947;
            font-size: 125px;
            font-weight: 500;
            line-height: 0.8;
        }

        .corn-image {
            position: absolute;
            bottom: 0;
            right: 0;
            height: 100px;
            width: auto;
            object-fit: contain;
        }

        /* Navbar */
        nav {
            background-color: #4a4947;
            color: #faf7f0;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        nav ul {
            list-style: none;
            display: flex;
            gap: 15px;
        }

        nav ul.left-links {
            flex: 1;
        }

        nav ul.right-links {
            justify-content: flex-end;
        }

        nav ul li {
            display: inline;
        }

        nav ul li a {
            color: #faf7f0;
            text-decoration: none;
            font-size: 16px;
            transition: color 0.3s;
        }

        nav ul li a:hover {
            color: #fccd2a;
        }

        /* Language Switcher */
        .language-switcher {
            text-align: center;
            margin: 10px 0;
        }

        .language-switcher a {
            color: #4a4947;
            text-decoration: none;
            font-size: 14px;
            padding: 5px 10px;
            transition: background-color 0.3s;
        }

        .language-switcher a:hover {
            background-color: #d8d2c2;
            border-radius: 4px;
        }

        /* Footer Styles */
        .footer {
            background-color: #4a4947;
            color: #faf7f0;
            padding: 20px;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
            font-family: 'Alexandria', sans-serif;
            box-shadow: 0 -2px 5px rgba(0, 0, 0, 0.1);
        }

        .footer-links,
        .footer-copyright,
        .footer-social {
            font-size: 14px;
        }

        .footer-links a,
        .footer-social a {
            color: #faf7f0;
            text-decoration: none;
            margin-right: 10px;
            transition: color 0.3s ease;
        }

        .footer-links a:hover,
        .footer-social a:hover {
            color: #fccd2a;
        }

        .footer-copyright {
            color: #d8d2c2;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="header">
        <div class="site-title">
            <span class="site-part">Save It</span>
            <span class="site-abbr">SIFO</span>
            <span class="site-part">For Others</span>
        </div>
        <img src="/sifo-app/assets/img/Corn.png" alt="Corn Image" class="corn-image" />
    </div>

    <!-- Navigation Bar -->
    <nav>
        <ul class="left-links">
            <li><a href="/sifo-app/views"><?php echo translate('home'); ?></a></li>
            <?php if ($isLoggedIn): ?>
                <?php if ($userType === 'donor'): ?>
                    <li><a href="/sifo-app/views/donations/donate.php"><?php echo translate('donations'); ?></a></li>
                <?php elseif ($userType === 'charity'): ?>
                    <li><a href="/sifo-app/views/campaigns/view_campaigns.php"><?php echo translate('campaigns'); ?></a></li>
                    <li><a href="/sifo-app/views/donations/charity_donations.php"><?php echo translate('donations'); ?></a></li>
                <?php endif; ?>
                <li><a
                        href="/sifo-app/views/users/<?php echo strtolower($userType); ?>_account.php"><?php echo translate('account'); ?></a>
                </li>
            <?php endif; ?>
            <li><a href="/sifo-app/views/feedback/feedback.php"><?php echo translate('feedback'); ?></a></li>
        </ul>
        <ul class="right-links">
            <?php if (!$isLoggedIn): ?>
                <li><a href="/sifo-app/views/auth/register.php"><?php echo translate('sign_up'); ?></a></li>
                <li><a href="/sifo-app/views/auth/login.php"><?php echo translate('login'); ?></a></li>
            <?php else: ?>
                <li><a href="/sifo-app/controllers/AuthController.php?action=logout"><?php echo translate('logout'); ?></a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>

    <div class="language-switcher">
        <a href="/sifo-app/controllers/change_language.php?lang=en" <?= ($_SESSION['lang'] ?? 'ar') === 'en' ? 'style="pointer-events: none; opacity: 0.6;"' : ''; ?>>En</a> |
        <a href="/sifo-app/controllers/change_language.php?lang=ar" <?= ($_SESSION['lang'] ?? 'ar') === 'ar' ? 'style="pointer-events: none; opacity: 0.6;"' : ''; ?>>عربي</a>
    </div>
</body>

</html>