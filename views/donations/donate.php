<?php
include __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../config/config.php';
include __DIR__ . '/../layouts/header.php';
?>

<h1>Make a Donation</h1>

<form method="POST" action="/sifo-app/controllers/DonationController.php?action=donate" enctype="multipart/form-data">
    <label for="type">Donation Type:</label>
    <select name="type" id="type" onchange="toggleDonationFields()" required>
        <option value="Food">Food</option>
        <option value="Non-Food">Non-Food</option>
    </select>

    <!-- Food Donation Fields -->
    <div id="foodFields" style="display: none;">
        <h2>Food Donation Details</h2>
        <label for="description">Description:</label>
        <textarea name="description" id="description" required></textarea>

        <label for="donate_condition">Condition or Expiry Date:</label>
        <input type="date" name="donate_condition" id="donate_condition" required>

        <label for="location">Location (City, District):</label>
        <input type="text" name="location" id="location" required>

        <label for="pickup_date_time">Pick Up Date & Time:</label>
        <input type="datetime-local" name="pickup_date_time" id="pickup_date_time" required>

        <label for="amount">Amount:</label>
        <input type="number" name="amount" id="amount" required>
    </div>

    <!-- Non-Food Donation Fields -->
    <div id="nonFoodFields" style="display: none;">
        <h2>Non-Food Donation Details</h2>
        <label for="description_nonfood">Description:</label>
        <textarea name="description" id="description_nonfood" required></textarea>

        <label for="donate_condition_nonfood">Condition:</label>
        <input type="text" name="donate_condition" id="donate_condition_nonfood" required>

        <label for="location_nonfood">Location (City, District):</label>
        <input type="text" name="location" id="location_nonfood" required>

        <label for="pickup_date_time_nonfood">Pick Up Date & Time:</label>
        <input type="datetime-local" name="pickup_date_time" id="pickup_date_time_nonfood" required>

        <label for="amount_nonfood">Amount:</label>
        <input type="number" name="amount" id="amount_nonfood" required>

        <label for="photos">Upload Photos:</label>
        <input type="file" name="photos[]" id="photos" accept="image/*" multiple>
    </div>

    <button type="submit">Donate</button>
</form>


<?php include __DIR__ . '/../layouts/footer.php'; ?>