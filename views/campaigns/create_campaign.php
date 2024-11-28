<?php
include __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../layouts/header.php';

// Ensure the user is a charity
if ($_SESSION['user_type'] !== 'charity') {
    die("Access denied. Only charities can create campaigns.");
}
?>

<style>
    main {
        padding: 20px;
        max-width: 600px;
        margin: 0 auto;
        background-color: #faf7f0;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        font-family: 'Alexandria', sans-serif;
    }

    h1 {
        text-align: center;
        margin-bottom: 20px;
        color: #4a4947;
        font-size: 28px;
    }

    form {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    form label {
        font-size: 14px;
        font-weight: bold;
        color: #4a4947;
    }

    form input,
    form textarea,
    form select {
        width: 100%;
        padding: 10px;
        border: 1px solid #d8d2c2;
        border-radius: 5px;
        font-size: 14px;
        background-color: #fff;
    }

    form textarea {
        resize: vertical;
        min-height: 100px;
    }

    form input:focus,
    form textarea:focus {
        outline: none;
        border-color: #fccd2a;
        box-shadow: 0 0 5px rgba(252, 205, 42, 0.5);
    }

    button {
        margin-top: 20px;
        padding: 12px;
        background-color: #4a4947;
        color: #faf7f0;
        border: none;
        border-radius: 5px;
        font-size: 16px;
        font-weight: bold;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    button:hover {
        background-color: #fccd2a;
        color: #4a4947;
    }
</style>

<main>
    <h1><?php echo translate('create-campaign'); ?></h1>

    <form method="POST" action="/sifo-app/controllers/CampaignController.php?action=create">
        <label for="title"><?php echo translate('campaign_name'); ?>:</label>
        <input type="text" name="title" id="title" placeholder="Enter the campaign title" required>

        <label for="description"><?php echo translate('description'); ?>:</label>
        <textarea name="description" id="description" placeholder="Enter the campaign description" required></textarea>

        <label for="target_amount"><?php echo translate('target'); ?>:</label>
        <input type="number" name="target_amount" id="target_amount" step="0.01" placeholder="Enter the target amount"
            required>

        <label for="start_date"><?php echo translate('start_date'); ?>:</label>
        <input type="datetime-local" name="start_date" id="start_date" required>

        <label for="end_date"><?php echo translate('end_date'); ?>:</label>
        <input type="datetime-local" name="end_date" id="end_date" required>

        <button type="submit"><?php echo translate('create-campaign'); ?></button>
    </form>
</main>

<?php include __DIR__ . '/../layouts/footer.php'; ?>