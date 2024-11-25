<?php
// Start the session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include __DIR__ . '/layouts/header.php';
?>

<style>
    body {
        font-family: 'Alexandria', sans-serif;
        background-color: #faf7f0;
        color: #4a4947;
        margin: 0;
        padding: 0;
    }

    main {
        padding: 20px;
    }

    h1,
    h3,
    p {
        margin: 0;
    }

    /* Call-to-Action Section */
    .call-to-action {
        text-align: center;
        margin-bottom: 40px;
    }

    .call-to-action h3 {
        color: #4a4947;
    }

    /* Hero Section */
    .hero {
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .features {
        text-align: center;
        max-width: 600px;
        margin-bottom: 30px;
    }

    .features h3 {
        font-size: 24px;
        color: #4a4947;
    }

    .features p {
        font-size: 16px;
        line-height: 1.5;
    }

    .platform-details {
        display: flex;
        justify-content: space-around;
        max-width: 900px;
        width: 100%;
        gap: 20px;
    }

    .donor-section,
    .charity-section {
        flex: 1;
        padding: 20px;
        background-color: #d8d2c2;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        text-align: center;
    }

    .donor-section p,
    .charity-section p {
        font-size: 14px;
        margin-bottom: 15px;
    }

    button {
        background-color: #fccd2a;
        color: #4a4947;
        border: none;
        padding: 10px 20px;
        border-radius: 4px;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    button:hover {
        background-color: #e0b021;
    }

    .button {
        margin-top: 30px;
        display: inline-block;
        padding: 10px 20px;
        background-color: #e0b021;
        color: #faf7f0;
        text-decoration: none;
        border-radius: 4px;
        transition: background-color 0.3s;
    }

    .button:hover {
        background-color: #fccd2a;
    }

    /* Partners Section */
    .partners {
        margin-top: 50px;
        text-align: center;
        background-color: #d8d2c2;
        padding: 30px;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .partners h3 {
        color: #4a4947;
        margin-bottom: 20px;
    }

    .partners img {
        width: 150px;
        height: auto;
        object-fit: contain;
        transition: transform 0.3s;
    }

    .partners img:hover {
        transform: scale(1.1);
    }

    .partners div {
        display: flex;
        justify-content: center;
        gap: 20px;
        align-items: center;
    }
</style>

<main>
    <!-- Call-to-Action Section -->
    <section class="call-to-action">
        <?php if (isset($_SESSION['user_id'])): ?>
            <h3>Welcome Back, <?= htmlspecialchars($_SESSION['username']); ?>!</h3>
        <?php endif; ?>
    </section>

    <!-- Hero Section -->
    <section class="hero">
        <div class="features">
            <h3>Our Platform Aims to</h3>
            <br>
            <p>
                Bridge the gap between abundance and need, enabling the efficient transfer of food and essential items
                to those who require them. By minimizing waste and maximizing impact, we strive to create a more
                sustainable and compassionate society.
            </p>
        </div>
        <br>

        <?php if (!isset($_SESSION['user_id'])): ?>
            <div class="platform-details">
                <!-- Donor Section -->
                <div class="donor-section">
                    <p>
                        <b>As a Donor, Give Donations:</b><br />
                        <br>
                        Your generosity can make a significant impact. Donate food, non-food items, or make a quick monetary
                        contribution through Snap Donate. Your donation, regardless of form, will be used to support those
                        in need.
                    </p>
                    <button onclick="location.href='/sifo-app/views/donations/donate.php';">
                        Donate Now
                    </button>
                    <p style="font-size: 10px; margin-top: 10px;">
                        <span style="color: #000000;"><b>Note</b></span>: You can only donate via Snap Donate if you do not
                        have an account on our website.
                    </p>
                </div>

                <!-- Charity Section -->
                <div class="charity-section">
                    <p>
                        <b>As a Charity, Manage Donations:</b><br />
                        <br>
                        You can efficiently manage donations, create targeted campaigns, and select the specific types
                        of donations your organization needs most. By utilizing our platform, you can effectively distribute
                        aid to those in need.
                    </p>
                    <button onclick="location.href='/sifo-app/views/dashboard/charity_dashboard.php';">
                        Manage Donations
                    </button>
                    <p style="font-size: 10px; margin-top: 10px;">
                        <span style="color: #000000;"><b>Note</b></span>: This account type is specifically designed for
                        charities to manage donations. Direct donations cannot be made through this account.
                    </p>
                </div>
            </div>

        <?php else: ?>
            <a href="/sifo-app/views/dashboard/<?= strtolower($_SESSION['user_type']); ?>_dashboard.php" class="button">
                Go to Dashboard
            </a>
        <?php endif; ?>
    </section>

    <!-- Partners Section -->
    <section class="partners">
        <h3>Our Partners</h3>
        <div>
            <a href="#"><img src="/sifo-app/assets/img/albaik.png" alt="Partner Logo"></a>
            <a href="#"><img src="/sifo-app/assets/img/altazaj.png" alt="Partner Logo"></a>
            <a href="#"><img src="/sifo-app/assets/img/Fake.png" alt="Partner Logo"></a>
        </div>
    </section>
</main>

<?php include __DIR__ . '/layouts/footer.php'; ?>