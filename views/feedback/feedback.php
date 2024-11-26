<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../config/config.php';
include_once __DIR__ . '/../layouts/header.php';

$userLoggedIn = isset($_SESSION['user_id']);
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
        max-width: 600px;
        margin: 40px auto;
        background-color: #faf7f0;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    h1 {
        text-align: center;
        color: #4a4947;
        margin-bottom: 20px;
    }

    p {
        text-align: center;
        color: #4a4947;
        margin-bottom: 20px;
    }

    form {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    label {
        display: block;
        color: #4a4947;
        margin-bottom: 5px;
        font-weight: 500;
    }

    input,
    textarea {
        width: 100%;
        padding: 10px;
        border: 1px solid #d8d2c2;
        border-radius: 4px;
    }

    textarea {
        height: 120px;
        resize: none;
    }

    button {
        padding: 10px;
        background-color: #4a4947;
        color: #faf7f0;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 16px;
        transition: background-color 0.3s ease;
    }

    button:hover {
        background-color: #fccd2a;
    }
</style>

<main>
    <h1><?php echo translate('feedback'); ?></h1>
    <p><?php echo translate('feedback_intro'); ?></p>

    <form method="POST" action="/sifo-app/controllers/FeedbackController.php?action=submit">
        <!-- Name -->
        <div>
            <label for="name"><?php echo translate('name'); ?>:</label>
            <input type="text" id="name" name="name" required placeholder="Enter your name"
                value="<?= $userLoggedIn ? htmlspecialchars($_SESSION['username']) : ''; ?>">
        </div>

        <!-- Email -->
        <div>
            <label for="email"><?php echo translate('email'); ?>:</label>
            <input type="email" id="email" name="email" required placeholder="Enter your email">
        </div>

        <!-- Subject -->
        <div>
            <label for="subject"><?php echo translate('subject'); ?>:</label>
            <input type="text" id="subject" name="subject" required placeholder="Subject">
        </div>

        <!-- Feedback Message -->
        <div>
            <label for="message"><?php echo translate('message'); ?>:</label>
            <textarea id="message" name="message" required placeholder="Enter your feedback"></textarea>
        </div>

        <!-- Submit Button -->
        <button type="submit"><?php echo translate('submit_feedback'); ?></button>
    </form>
</main>

<?php include_once __DIR__ . '/../layouts/footer.php'; ?>