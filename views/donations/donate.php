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
        text-align: center;
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

<h1><?php echo translate('make_donation'); ?></h1>

<form method="POST" action="/sifo-app/controllers/DonationController.php?action=donate" enctype="multipart/form-data">
    <!-- Donation Type -->
    <div class="form-group">
        <label for="type"><?php echo translate('donation_type'); ?>:</label>
        <select name="type" id="type" onchange="toggleDonationFields()" required>
            <option value=""><?php echo translate('select-donation-type'); ?></option>
            <option value="Food"><?php echo translate('food'); ?></option>
            <option value="Non-Food"><?php echo translate('nonfood'); ?></option>
        </select>
    </div>

    <!-- Food Donation Fields -->
    <div id="foodFields" class="hidden">
        <h2><?php echo translate('food_details'); ?></h2>
        <div class="form-group">
            <label for="description_food"><?php echo translate('food_description'); ?>:</label>
            <textarea name="description_food" id="description_food"
                placeholder="<?php echo translate('enter-food-details'); ?>"></textarea>
        </div>
        <div class="form-group">
            <label for="donate_condition_food"><?php echo translate('food_condition'); ?>:</label>
            <input type="text" name="donate_condition_food" id="donate_condition_food"
                placeholder="<?php echo translate('text-date'); ?>">
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
            <input type="text" name="district_food" id="district_food"
                placeholder="<?php echo translate('enter_district'); ?>">
        </div>
        <div class="form-group">
            <label for="pickup_date_time_food"><?php echo translate('pickup_date_time'); ?>:</label>
            <input type="datetime-local" name="pickup_date_time_food" id="pickup_date_time_food">
        </div>
        <div class="form-group">
            <label for="amount_food"><?php echo translate('num_amount'); ?>:</label>
            <input type="number" name="amount_food" id="amount_food"
                placeholder="<?php echo translate('enter-quantity-amount'); ?>">
        </div>
    </div>

    <!-- Non-Food Donation Fields -->
    <div id="nonFoodFields" class="hidden">
        <h2><?php echo translate('nonfood_details'); ?></h2>
        <div class="form-group">
            <label for="description_nonfood"><?php echo translate('nonfood_description'); ?>:</label>
            <textarea name="description_nonfood" id="description_nonfood"
                placeholder="<?php echo translate('enter-nonfood-details'); ?>"></textarea>
        </div>
        <div class="form-group">
            <label for="donate_condition_nonfood"><?php echo translate('nonfood_condition'); ?>:</label>
            <input type="text" name="donate_condition_nonfood" id="donate_condition_nonfood"
                placeholder="<?php echo translate('enter-condition'); ?>">
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
            <input type="text" name="district_nonfood" id="district_nonfood"
                placeholder="<?php echo translate('enter_district'); ?>">
        </div>
        <div class="form-group">
            <label for="pickup_date_time_nonfood"><?php echo translate('pickup_date_time'); ?>:</label>
            <input type="datetime-local" name="pickup_date_time_nonfood" id="pickup_date_time_nonfood">
        </div>
        <div class="form-group">
            <label for="amount_nonfood"><?php echo translate('num_amount'); ?>:</label>
            <input type="number" name="amount_nonfood" id="amount_nonfood"
                placeholder="<?php echo translate('enter-quantity-amount'); ?>">
        </div>
        <div class="form-group">
            <label for="photos"><?php echo translate('upload_pic'); ?></label>
            <input type="file" name="photos[]" id="photos" accept="image/*" multiple>
        </div>
    </div>

    <button type="submit"><?php echo translate('donate'); ?></button>
</form>
<script>
    // Toggle donation fields based on selected type
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

    // Validate form fields before submission
    function validateDonationForm(event) {
        let isValid = true;
        const type = document.getElementById('type').value;

        if (!type) {
            alert('Please select a donation type.');
            document.getElementById('type').focus();
            isValid = false;
        }

        // Validate fields based on type
        if (type === 'Food') {
            const amountFood = document.getElementById('amount_food').value;
            if (parseFloat(amountFood) <= 0 || isNaN(amountFood)) {
                alert('Amount must be a positive number.');
                document.getElementById('amount_food').focus();
                isValid = false;
            }
        } else if (type === 'Non-Food') {
            const amountNonFood = document.getElementById('amount_nonfood').value;
            if (parseFloat(amountNonFood) <= 0 || isNaN(amountNonFood)) {
                alert('Amount must be a positive number.');
                document.getElementById('amount_nonfood').focus();
                isValid = false;
            }
        }

        if (!isValid) {
            event.preventDefault();
        }
    }

    // Attach event listeners
    document.getElementById('type')?.addEventListener('change', toggleDonationFields);
    document.querySelector('form').addEventListener('submit', validateDonationForm);

    // Initial field toggle
    document.addEventListener('DOMContentLoaded', toggleDonationFields);
</script>



<?php include __DIR__ . '/../layouts/footer.php'; ?>