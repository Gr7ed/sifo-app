<?php
include __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../layouts/header.php';

// Ensure the user is a charity
if ($_SESSION['user_type'] !== 'charity') {
    die("Access denied. Only charities can create campaigns.");
}
?>

<h1>Create a Campaign</h1>

<form method="POST" action="/sifo-app/controllers/CampaignController.php?action=create">
    <label for="title">Campaign Title:</label>
    <input type="text" name="title" id="title" required>

    <label for="description">Description:</label>
    <textarea name="description" id="description" required></textarea>

    <label for="target_amount">Target Amount:</label>
    <input type="number" name="target_amount" id="target_amount" step="0.01" required>

    <label for="start_date">Start Date:</label>
    <input type="datetime-local" name="start_date" id="start_date" required>

    <label for="end_date">End Date:</label>
    <input type="datetime-local" name="end_date" id="end_date" required>

    <button type="submit">Create Campaign</button>
</form>

<?php include __DIR__ . '/../layouts/footer.php'; ?>