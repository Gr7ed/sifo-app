<?php
// Start the session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include __DIR__ . '/layouts/header.php';
?>

<main>
    <!-- Hero Section -->
    <section class="hero">
        <h1></h1>
        <p>Your platform for making a difference through donations.</p>
        <?php if (!isset($_SESSION['user_id'])): ?>
            <a href="/sifo-app/views/auth/register.php" class="button">Get Started</a>
        <?php else: ?>
            <a href="/sifo-app/views/dashboard/<?= strtolower($_SESSION['user_type']); ?>_dashboard.php" class="button">Go
                to Dashboard</a>
        <?php endif; ?>
    </section>

    <!-- Features Section -->
    <section class="features">
        <h2><?php echo translate(key: 'features'); ?></h2>
        <ul>
            <li>Donate food, clothes, and other items to those in need.</li>
            <li>Track your donations and see the impact you've made.</li>
            <li>Join as an individual donor, organization donor, or charity beneficiary.</li>
        </ul>
    </section>

    <!-- Call-to-Action Section -->
    <section class="call-to-action">
        <?php if (!isset($_SESSION['user_id'])): ?>
            <h3>Join Us Today!</h3>
            <p>Create an account or log in to start making a difference.</p>
            <a href="/sifo-app/views/auth/register.php" class="button"><?php echo translate('sign_up'); ?></a>
            <a href="/sifo-app/views/auth/login.php" class="button"><?php echo translate('login'); ?></a>
        <?php else: ?>
            <h3>Welcome Back, <?= htmlspecialchars($_SESSION['username']); ?>!</h3>
            <p>
                <a href="/sifo-app/controllers/AuthController.php?action=logout"
                    class="button"><?php echo translate('logout'); ?></a>
                <a href="/sifo-app/views/dashboard/<?= strtolower($_SESSION['user_type']); ?>_dashboard.php"
                    class="button"><?php echo translate('dashboard'); ?></a>
            </p>
        <?php endif; ?>
    </section>
</main>

<?php include __DIR__ . '/layouts/footer.php'; ?>