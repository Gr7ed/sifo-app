<?php
include_once __DIR__ . '/../config/config.php';
include_once __DIR__ . '/../layouts/header.php';

session_start();
$userLoggedIn = isset($_SESSION['user_id']);
?>

<h1>Feedback</h1>
<p>We value your feedback! Please let us know your thoughts.</p>

<form method="POST" action="/sifo-app/controllers/FeedbackController.php?action=submit">
    <!-- Name -->
    <label for="name">Name:</label>
    <input type="text" id="name" name="name" required placeholder="Enter your name"
        value="<?= $userLoggedIn ? htmlspecialchars($_SESSION['username']) : ''; ?>">

    <!-- Email -->
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required placeholder="Enter your email">

    <!-- Feedback Message -->
    <label for="message">Message:</label>
    <textarea id="message" name="message" required placeholder="Enter your feedback"></textarea>

    <button type="submit">Submit Feedback</button>
</form>

<?php include_once __DIR__ . '/../layouts/footer.php'; ?>