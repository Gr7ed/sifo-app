<?php
include 'config.php';
include 'includes/header.php';
?>
<main>
    <h1><?php echo translate('login'); ?></h1>
    <form action="process_login.php" method="POST">
        <label for="email"><?php echo translate('email'); ?>:</label>
        <input type="email" name="email" id="email" required>

        <label for="password"><?php echo translate('password'); ?>:</label>
        <input type="password" name="password" id="password" required>

        <button type="submit"><?php echo translate('login'); ?></button>
    </form>
</main>
<?php include 'includes/footer.php'; ?>