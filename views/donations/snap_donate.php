<?php
include __DIR__ . '/../layouts/header.php';
?>

<!-- Styles -->
<style>
    body {
        font-family: "Alexandria", sans-serif;
        background-color: #faf7f0;
        margin: 0;
        padding: 0;
    }

    .snap-donate-container {
        max-width: 600px;
        margin: 50px auto;
        padding: 20px;
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        text-align: center;
        border: 1px solid #d8d2c2;
    }

    .snap-donate-container h1 {
        font-size: 2.5em;
        color: #4a4947;
        margin-bottom: 20px;
    }

    .snap-donate-container form {
        display: flex;
        flex-direction: column;
        gap: 15px;
        align-items: stretch;
    }

    .snap-donate-container label {
        text-align: left;
        font-size: 1em;
        color: #4a4947;
        font-weight: bold;
        margin-bottom: 5px;
    }

    .snap-donate-container input[type="text"],
    .snap-donate-container input[type="number"] {
        padding: 10px;
        font-size: 1em;
        border: 1px solid #d8d2c2;
        border-radius: 5px;
        width: 100%;
        background-color: #f9f9f9;
        box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.1);
    }

    .snap-donate-container button {
        background-color: #4a4947;
        color: #faf7f0;
        border: none;
        padding: 15px;
        font-size: 1.2em;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .snap-donate-container button:hover {
        background-color: #fccd2a;
    }

    .snap-donate-container input:focus {
        border-color: #4a4947;
        outline: none;
        box-shadow: 0 0 5px rgba(74, 73, 71, 0.5);
    }
</style>

<div class="snap-donate-container">
    <h1><?php echo translate('snap_donate'); ?></h1>
    <form method="POST" action="/sifo-app/controllers/DonationController.php?action=snap" enctype="multipart/form-data">
        <label for="donor_name"><?php echo translate('your_name'); ?>:</label>
        <input type="text" name="donor_name" id="donor_name" placeholder="Enter your name" required>

        <label for="amount"><?php echo translate('donation_amount'); ?>:</label>
        <input type="number" name="amount" id="amount" placeholder="Enter amount in SAR" min="1" required>

        <button type="submit"><?php echo translate('donate'); ?></button>
    </form>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>