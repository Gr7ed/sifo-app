<?php
include __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../config/config.php';
include __DIR__ . '/../layouts/header.php';
?>

<style>
    /* General Styles */
    body {
        font-family: "Alexandria", sans-serif;
        background-color: #faf7f0;
        margin: 0;
        padding: 0;
    }

    h1,
    h2 {
        color: #4a4947;
        margin-bottom: 20px;
    }

    form {
        max-width: 800px;
        margin: 20px auto;
        background-color: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    label {
        display: block;
        font-weight: bold;
        margin-bottom: 8px;
        color: #4a4947;
    }

    input,
    select,
    textarea,
    button {
        width: 100%;
        padding: 10px;
        margin-bottom: 20px;
        border: 1px solid #d8d2c2;
        border-radius: 5px;
        font-size: 14px;
    }

    input[type="file"] {
        padding: 5px;
    }

    button {
        background-color: #4a4947;
        color: #faf7f0;
        border: none;
        cursor: pointer;
        font-size: 16px;
        font-weight: bold;
        text-transform: uppercase;
    }

    button:hover {
        background-color: #fccd2a;
    }

    textarea {
        height: 100px;
    }

    .hidden {
        display: none;
    }

    /* Section Styles */
    #foodFields,
    #nonFoodFields {
        background-color: #d8d2c2;
        padding: 20px;
        border-radius: 5px;
        margin-bottom: 20px;
    }

    .form-group {
        margin-bottom: 20px;
    }
</style>

<h1>Make a Donation</h1>

<form method="POST" action="/sifo-app/controllers/DonationController.php?action=donate" enctype="multipart/form-data">
    <!-- Donation Type -->
    <div class="form-group">
        <label for="type">Donation Type:</label>
        <select name="type" id="type" onchange="toggleDonationFields()" required>
            <option value="">Select a type</option>
            <option value="Food">Food</option>
            <option value="Non-Food">Non-Food</option>
        </select>
    </div>

    <!-- Food Donation Fields -->
    <div id="foodFields" class="hidden">
        <h2>Food Donation Details</h2>
        <div class="form-group">
            <label for="description_food">Description:</label>
            <textarea name="description_food" id="description_food" placeholder="Enter food details..."></textarea>
        </div>
        <div class="form-group">
            <label for="donate_condition_food">Condition or Expiry Date:</label>
            <input type="text" name="donate_condition_food" id="donate_condition_food"
                placeholder="Text or Date: dd/mm/yyyy">
        </div>
        <div class="form-group">
            <label for="city_food"><?php echo translate('city'); ?>:</label>
            <select name="city_food" id="city_food">
                <option value=""><?php echo translate('select_city'); ?></option>
                <option value="Riyadh"><?php echo translate('riyadh'); ?></option>
                <option value="Jeddah"><?php echo translate('jeddah'); ?></option>
                <option value="Dammam"><?php echo translate('dammam'); ?></option>
                <option value="Mecca"><?php echo translate('mecca'); ?></option>
                <option value="Medina"><?php echo translate('medina'); ?></option>
            </select>
        </div>
        <div class="form-group">
            <label for="district_food"><?php echo translate('district'); ?>:</label>
            <input type="text" name="district_food" id="district_food" placeholder="Enter your district">
        </div>
        <div class="form-group">
            <label for="pickup_date_time_food">Pick Up Date & Time:</label>
            <input type="datetime-local" name="pickup_date_time_food" id="pickup_date_time_food">
        </div>
        <div class="form-group">
            <label for="amount_food">Amount:</label>
            <input type="number" name="amount_food" id="amount_food" placeholder="Enter quantity or amount">
        </div>
    </div>

    <!-- Non-Food Donation Fields -->
    <div id="nonFoodFields" class="hidden">
        <h2>Non-Food Donation Details</h2>
        <div class="form-group">
            <label for="description_nonfood">Description:</label>
            <textarea name="description_nonfood" id="description_nonfood"
                placeholder="Enter non-food details..."></textarea>
        </div>
        <div class="form-group">
            <label for="donate_condition_nonfood">Condition:</label>
            <input type="text" name="donate_condition_nonfood" id="donate_condition_nonfood"
                placeholder="Enter condition of items">
        </div>
        <div class="form-group">
            <label for="city_nonfood"><?php echo translate('city'); ?>:</label>
            <select name="city_nonfood" id="city_nonfood">
                <option value=""><?php echo translate('select_city'); ?></option>
                <option value="Riyadh"><?php echo translate('riyadh'); ?></option>
                <option value="Jeddah"><?php echo translate('jeddah'); ?></option>
                <option value="Dammam"><?php echo translate('dammam'); ?></option>
                <option value="Mecca"><?php echo translate('mecca'); ?></option>
                <option value="Medina"><?php echo translate('medina'); ?></option>
            </select>
        </div>
        <div class="form-group">
            <label for="district_nonfood"><?php echo translate('district'); ?>:</label>
            <input type="text" name="district_nonfood" id="district_nonfood" placeholder="Enter your district">
        </div>
        <div class="form-group">
            <label for="pickup_date_time_nonfood">Pick Up Date & Time:</label>
            <input type="datetime-local" name="pickup_date_time_nonfood" id="pickup_date_time_nonfood">
        </div>
        <div class="form-group">
            <label for="amount_nonfood">Amount:</label>
            <input type="number" name="amount_nonfood" id="amount_nonfood" placeholder="Enter quantity or amount">
        </div>
        <div class="form-group">
            <label for="photos">Upload Photos:</label>
            <input type="file" name="photos[]" id="photos" accept="image/*" multiple>
        </div>
    </div>

    <button type="submit">Donate</button>
</form>
<script>
    function toggleDonationFields() {
        const typeElement = document.getElementById('type');
        if (!typeElement) return; // Exit if the type element is not found

        const type = typeElement.value;
        const foodFields = document.getElementById('foodFields');
        const nonFoodFields = document.getElementById('nonFoodFields');

        // Reset required attributes for inputs in both field sets
        document.querySelectorAll('#foodFields input, #foodFields textarea').forEach(el => el.required = false);
        document.querySelectorAll('#nonFoodFields input, #nonFoodFields textarea').forEach(el => el.required = false);

        // Toggle field visibility and update required attributes
        if (type === 'Food') {
            if (foodFields) foodFields.style.display = 'block';
            if (nonFoodFields) nonFoodFields.style.display = 'none';

            document.querySelectorAll('#foodFields input, #foodFields textarea').forEach(el => el.required = true);
        } else if (type === 'Non-Food') {
            if (foodFields) foodFields.style.display = 'none';
            if (nonFoodFields) nonFoodFields.style.display = 'block';

            document.querySelectorAll('#nonFoodFields input, #nonFoodFields textarea').forEach(el => el.required = true);
        } else {
            if (foodFields) foodFields.style.display = 'none';
            if (nonFoodFields) nonFoodFields.style.display = 'none';
        }
    }
    document.getElementById('type')?.addEventListener('change', toggleDonationFields);
</script>


<?php include __DIR__ . '/../layouts/footer.php'; ?>