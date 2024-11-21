<?php
require_once __DIR__ . '/../../config/config.php'; // Include config 
require_once __DIR__ . '/../layouts/header.php';

?>

<h1><?php echo translate('login'); ?></h1>
<form method="POST" action="/sifo-app/controllers/AuthController.php?action=login">
    <label for="email"><?= translate('email'); ?>:</label>
    <input type="email" name="email" id="email" required>

    <label for="password"><?= translate('password'); ?>:</label>
    <input type="password" name="password" id="password" required>

    <button type="submit"><?= translate('login'); ?></button>
</form>

<p><a href="/sifo-app/views/auth/reset_password.php">Forgot Password?</a></p>

<?php include __DIR__ . '/../layouts/footer.php'; ?>