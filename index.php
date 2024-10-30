<?php
include 'config.php';
include 'includes/header.php';
?>
<main>
    <h1><?php echo translate(key: 'welcome'); ?></h1>
    <p><?php echo translate('intro_text'); ?></p>

    <h2><?php echo translate('features'); ?></h2>
    <ul>
        <li><a href="register.php"><?php echo translate('register'); ?></a></li>
        <li><a href="login.php"><?php echo translate('login'); ?></a></li>
        <li><a href="donate.php"><?php echo translate('make_donation'); ?></a></li>
        <li><a href="snap_donate.php"><?php echo translate('snap_donation'); ?></a></li>
    </ul>
</main>
<?php include 'includes/footer.php'; ?>