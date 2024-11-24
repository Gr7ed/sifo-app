<?php
include __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../config/config.php';
include __DIR__ . '/../layouts/header.php';
?>

<h1>Make a Donation</h1>

<form method="POST" action="/sifo-app/controllers/DonationController.php?action=donate" enctype="multipart/form-data">
    <label for="type">Donation Type:</label>
    <select name="type" id="type" onchange="toggleDonationFields()" required>
        <option value="">Select a type</option>
        <option value="Food">Food</option>
        <option value="Non-Food">Non-Food</option>
    </select>

    <!-- Food Donation Fields -->
    <div id="foodFields" style="display: none;">
        <h2>Food Donation Details</h2>
        <label for="description_food">Description:</label>
        <textarea name="description_food" id="description_food"></textarea>

        <label for="donate_condition_food">Condition or Expiry Date:</label>
        <input type="text" name="donate_condition_food" id="donate_condition_food"
            placeholder="Text or Date: dd/mm/yyyy">

        <!-- City -->
        <label for="city_food"><?php echo translate('city'); ?>:</label>
        <select name="city_food" id="city_food">
            <option value=""><?php echo translate('select_city'); ?></option>
            <option value="Riyadh"><?php echo translate('riyadh'); ?></option>
            <option value="Jeddah"><?php echo translate('jeddah'); ?></option>
            <option value="Dammam"><?php echo translate('dammam'); ?></option>
            <option value="Mecca"><?php echo translate('mecca'); ?></option>
            <option value="Medina"><?php echo translate('medina'); ?></option>
        </select>

        <!-- District -->
        <label for="district_food"><?php echo translate('district'); ?>:</label>
        <input type="text" name="district_food" id="district_food" required placeholder="Enter your district">

        <label for="pickup_date_time_food">Pick Up Date & Time:</label>
        <input type="datetime-local" name="pickup_date_time_food" id="pickup_date_time_food">

        <label for="amount_food">Amount:</label>
        <input type="number" name="amount_food" id="amount_food">
    </div>

    <!-- Non-Food Donation Fields -->
    <div id="nonFoodFields" style="display: none;">
        <h2>Non-Food Donation Details</h2>
        <label for="description_nonfood">Description:</label>
        <textarea name="description_nonfood" id="description_nonfood"></textarea>

        <label for="donate_condition_nonfood">Condition:</label>
        <input type="text" name="donate_condition_nonfood" id="donate_condition_nonfood">

        <!-- City -->
        <label for="city_nonfood"><?php echo translate('city'); ?>:</label>
        <select name="city_nonfood" id="city_nonfood">
            <option value=""><?php echo translate('select_city'); ?></option>
            <option value="Riyadh"><?php echo translate('riyadh'); ?></option>
            <option value="Jeddah"><?php echo translate('jeddah'); ?></option>
            <option value="Dammam"><?php echo translate('dammam'); ?></option>
            <option value="Mecca"><?php echo translate('mecca'); ?></option>
            <option value="Medina"><?php echo translate('medina'); ?></option>
        </select>

        <!-- District -->
        <label for="district_nonfood"><?php echo translate('district'); ?>:</label>
        <input type="text" name="district_nonfood" id="district_nonfood" required placeholder="Enter your district">

        <label for="pickup_date_time_nonfood">Pick Up Date & Time:</label>
        <input type="datetime-local" name="pickup_date_time_nonfood" id="pickup_date_time_nonfood">

        <label for="amount_nonfood">Amount:</label>
        <input type="number" name="amount_nonfood" id="amount_nonfood">

        <label for="photos">Upload Photos:</label>
        <input type="file" name="photos[]" id="photos" accept="image/*" multiple>
    </div>

    <button type="submit">Donate</button>
</form>

<?php include __DIR__ . '/../layouts/footer.php'; ?>