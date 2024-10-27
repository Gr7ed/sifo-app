<?php
include 'includes/session.php';
checkSession();
include 'includes/header.php';
?>
<main>
    <h1><?php echo translate('snap_donation'); ?></h1>
    <form action="submit_snap_donation.php" method="POST">
        <label for="snap_amount"><?php echo translate('amount'); ?> (USD):</label>
        <input type="number" name="snap_amount" id="snap_amount" required>
        <button type="submit"><?php echo translate('submit'); ?></button>
    </form>
</main>
<?php include 'includes/footer.php'; ?>