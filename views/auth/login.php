<?php
include __DIR__ . '/../layouts/header.php'; // Include header

// Ensure the session is started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// If already logged in, redirect to the appropriate dashboard
if (isset($_SESSION['user_id'])) {
    if ($_SESSION['user_type'] === 'charity') {
        header("Location: /sifo-app/views/dashboard/charity_dashboard.php");
    } else {
        header("Location: /sifo-app/views/dashboard/donor_dashboard.php");
    }
    exit();
}

?>

<!-- Include inline CSS styles -->
<style>
    /* Styles from Login.html */
    /* Include your CSS styles here */

    /* Layout Containers */
    html,
    body {
        height: 100%;
        display: flex;
        flex-direction: column;
        font-family: "Alexandria", sans-serif;
    }

    /* Header Section */
    .header {
        position: relative;
        background-color: #faf7f0;
        padding: 20px;
        height: 98px;
        display: flex;
        text-align: center;
        align-items: center;
        justify-content: space-between;
    }

    .header .site-title {
        display: flex;
        text-align: center;
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

    /* Corn Image Styling */
    .corn-image {
        position: absolute;
        bottom: 0;
        right: 0;
        height: 100px;
        width: auto;
        object-fit: contain;
    }

    /* Navigation Bar */
    .verse {
        background-color: #4a4947;
        color: #faf7f0;
        font-weight: 300;
        font-size: 15px;
        padding: 20px;
        height: 33px;
        display: flex;
        align-items: center;
        text-align: center;
    }

    /* Main Content */
    .main-content {
        flex: 1;
        background-color: #d8d2c2;
        font-family: "Alexandria", sans-serif;
        padding: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Login Form */
    .login-form {
        background-color: #faf7f0;
        width: 400px;
        padding: 15px;
        height: 100%;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        text-align: center;
    }

    .login-form h1 {
        font-size: 36px;
        color: #4a4947;
        margin-bottom: 40px;
    }

    .login-form label {
        font-size: 16px;
        color: #4a4947;
        display: block;
        text-align: left;
        margin-bottom: 5px;
    }

    .login-form input[type="text"],
    .login-form input[type="password"] {
        width: 100%;
        padding: 10px;
        margin-bottom: 30px;
        border: 0.5px solid #4a4947;
        border-radius: 8px;
        font-size: 14px;
    }

    .login-form a {
        font-size: 14px;
        color: #4a4947;
        text-decoration: none;
        margin-bottom: 20px;
        display: inline-block;
    }

    .login-form .ForgetPassword a:hover {
        text-decoration: underline;
    }

    .login-form .ForgetPassword {
        font-size: 14px;
        color: #4a4947;
    }

    .login-form .ForgetPassword a {
        color: #4a4947;
        text-decoration: none;
    }

    .login-form .ForgetPassword a:hover {
        text-decoration: underline;
    }

    .login-form button {
        width: 100%;
        padding: 10px;
        margin-top: 20px;
        font-size: 24px;
        background-color: #fccd2a;
        border: none;
        border-radius: 8px;
        color: #4a4947;
        font-weight: 800;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .login-form button:hover {
        background-color: #e0b021;
    }

    .login-form .SignUp {
        font-size: 14px;
        color: #4a4947;
        margin-top: 30px;
    }

    .login-form .SignUp a {
        color: #4a4947;
        text-decoration: none;
    }

    .login-form .SignUp a:hover {
        text-decoration: underline;
    }

    .back-home-link {
        position: absolute;
        bottom: 40px;
        right: 20px;
        color: #4a4947;
        font-family: "Alexandria", sans-serif;
        font-weight: bold;
        text-decoration: none;
    }

    .back-home-link:hover {
        color: #fccd2a;
    }
</style>

<!-- Main Content -->

<main class="main-content">
    <div class="login-form">
        <h1><?php echo translate('login'); ?></h1>
        <form method="POST" action="/sifo-app/controllers/AuthController.php?action=login">
            <label for="identifier"><?php echo translate('email-username'); ?>:</label>
            <input type="text" id="identifier" name="identifier"
                placeholder='<?php echo translate('enter-email-username'); ?>' required />

            <label for="password"><?php echo translate('password'); ?>:</label>
            <input type="password" id="password" name="password"
                placeholder='<?php echo translate('enter_password'); ?>' required />

            <div class="ForgetPassword">
                <a href="/sifo-app/views/auth/reset_password.php"><?php echo translate('forgot_password'); ?></a>
            </div>

            <button type="submit"><?php echo translate('login'); ?></button>

            <div class="SignUp">
                <a href="/sifo-app/views/auth/register.php"><?php echo translate('no_account'); ?></a>
            </div>
        </form>
    </div>
</main>

<?php
include __DIR__ . '/../layouts/footer.php'; // Include footer
?>