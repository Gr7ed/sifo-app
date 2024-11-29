<?php
include __DIR__ . '/../../config/config.php';
include __DIR__ . '/../layouts/header.php';

// Determine language direction
$language = $_SESSION['lang'] ?? 'ar'; // Default to 'en'
$direction = $language === 'ar' ? 'rtl' : 'ltr';
?>

<style>
    main {
        padding: 20px;
        max-width: 700px;
        margin: 0 auto;
        background-color: #faf7f0;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        font-family: 'Alexandria', sans-serif;
        direction:
            <?= $direction ?>
        ;
        text-align:
            <?= $direction === 'rtl' ? 'right' : 'left' ?>
        ;
    }

    h1 {
        text-align: center;
        margin-bottom: 20px;
        margin-top: 20px;
        color: #4a4947;
    }

    form {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    label {
        display: block;
        color: #4a4947;
        margin-bottom: 5px;
        font-size: 14px;
        font-weight: bold;
    }

    input,
    select {
        flex: 1;
        padding: 10px;
        margin: 8px 0;
        font-size: 14px;
        border: 1px solid #d8d2c2;
        border-radius: 5px;
        background-color: #fff;
    }

    .error-message {
        color: red;
        font-size: 12px;
        margin-top: -10px;
    }

    button {
        margin-top: 20px;
        padding: 15px;
        background-color: #4a4947;
        color: #faf7f0;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 18px;
        transition: background-color 0.3s ease;
    }

    button:hover {
        background-color: #fccd2a;
    }

    .checkbox-group label {
        margin-right: 10px;
    }
</style>

<main>
    <h1><?php echo translate('sign_up'); ?></h1>
    <form id="register-form" method="POST" action="/sifo-app/controllers/AuthController.php?action=register">
        <!-- User Type -->
        <label for="user_type"><?php echo translate('user_type'); ?></label>
        <select name="user_type" id="user_type" required>
            <option value=""><?php echo translate('select_user_type'); ?></option>
            <option value="donor"><?php echo translate('donor'); ?></option>
            <option value="charity"><?php echo translate('charity'); ?></option>
        </select>
        <small id="user_type-error" class="error-message"
            style="display: none;"><?php echo translate('field_required'); ?></small>

        <!-- Donor-Specific Fields -->
        <div id="donorFields" style="display: none;">
            <label for="first_name"><?php echo translate('first_name'); ?></label>
            <input type="text" name="first_name" id="first_name"
                placeholder="<?php echo translate('enter_first_name'); ?>">
            <small id="first_name-error" class="error-message"
                style="display: none;"><?php echo translate('field_required'); ?></small>

            <label for="last_name"><?php echo translate('last_name'); ?></label>
            <input type="text" name="last_name" id="last_name"
                placeholder="<?php echo translate('enter_last_name'); ?>">
            <small id="last_name-error" class="error-message"
                style="display: none;"><?php echo translate('field_required'); ?></small>
        </div>

        <!-- Common Fields -->
        <label for="username"><?php echo translate('username'); ?></label>
        <input type="text" name="username" id="username" placeholder="<?php echo translate('enter_username'); ?>">
        <small id="username-error" class="error-message"
            style="display: none;"><?php echo translate('field_required'); ?></small>

        <label for="email"><?php echo translate('email'); ?></label>
        <input type="email" name="email" id="email" placeholder="<?php echo translate('enter_email'); ?>">
        <small id="email-error" class="error-message"
            style="display: none;"><?php echo translate('email_invalid'); ?></small>

        <label for="password"><?php echo translate('password'); ?></label>
        <input type="password" name="password" id="password" placeholder="<?php echo translate('enter_password'); ?>">
        <small id="password-error" class="error-message"
            style="display: none;"><?php echo translate('password_invalid'); ?></small>

        <label for="confirm_password"><?php echo translate('conf_password'); ?></label>
        <input type="password" name="confirm_password" id="confirm_password"
            placeholder="<?php echo translate('conf_password'); ?>">
        <small id="confirm_password-error" class="error-message"
            style="display: none;"><?php echo translate('password_mismatch'); ?></small>

        <div>
            <label for="city"><?php echo translate('city'); ?></label>
            <select name="city" id="city">
                <option value=""><?php echo translate('select_city'); ?></option>
                <option value="Riyadh"><?php echo translate('riyadh'); ?></option>
                <option value="Jeddah"><?php echo translate('jeddah'); ?></option>
                <option value="Dammam"><?php echo translate('dammam'); ?></option>
                <option value="Mecca"><?php echo translate('mecca'); ?></option>
                <option value="Medina"><?php echo translate('medina'); ?></option>
            </select>
            <small id="city-error" class="error-message"
                style="display: none;"><?php echo translate('field_required'); ?></small>

            <label for="district"><?php echo translate('district'); ?></label>
            <input type="text" name="district" id="district" placeholder="<?php echo translate('enter_district'); ?>">
            <small id="district-error" class="error-message"
                style="display: none;"><?php echo translate('field_required'); ?></small>
        </div>
        <div>
            <label for="phone"><?php echo translate('phone_num'); ?></label>
            <input type="text" name="phone" id="phone" placeholder="<?php echo translate('enter_phone'); ?>">
            <small id="phone-error" class="error-message"
                style="display: none;"><?php echo translate('field_required'); ?></small>
        </div>

        <!-- Charity-Specific Fields -->
        <div id="charityFields" style="display: none;">
            <label for="charity_name"><?php echo translate('charity_name'); ?></label>
            <input type="text" name="charity_name" id="charity_name"
                placeholder="<?php echo translate('enter_charity_name'); ?>">
            <small id="charity_name-error" class="error-message"
                style="display: none;"><?php echo translate('field_required'); ?></small>

            <label for="charity_registration_number"><?php echo translate('charity_reg'); ?></label>
            <input type="text" name="charity_registration_number" id="charity_registration_number"
                placeholder="<?php echo translate('enter_registration_number'); ?>">
            <small id="charity_registration_number-error" class="error-message"
                style="display: none;"><?php echo translate('field_required'); ?></small>

            <label for="accepted_types"><?php echo translate('accepted_types'); ?></label>
            <div class="checkbox-group">
                <label><input type="checkbox" id="food" name="accepted_types[]" value="Food">
                    <?php echo translate('food'); ?></label>
                <label><input type="checkbox" id="non_food" name="accepted_types[]" value="Non-Food">
                    <?php echo translate('nonfood'); ?></label>
            </div>
            <small id="accepted_types-error" class="error-message"
                style="display: none;"><?php echo translate('accepted_types-error'); ?></small>
        </div>

        <button type="submit"><?php echo translate('sign_up'); ?></button>
    </form>
</main>


<script>
    function toggleFields() {
        const userType = document.getElementById("user_type").value;
        const donorFields = document.getElementById("donorFields");
        const charityFields = document.getElementById("charityFields");

        donorFields.style.display = userType === "donor" ? "block" : "none";
        charityFields.style.display = userType === "charity" ? "block" : "none";
    }

    document.getElementById("register-form").addEventListener("submit", function (event) {
        const errorMessages = document.querySelectorAll(".error-message");
        errorMessages.forEach(error => error.style.display = "none");

        let isValid = true;

        // Validate user type
        const userType = document.getElementById("user_type");
        if (!userType.value) {
            document.getElementById("user_type-error").style.display = "block";
            isValid = false;
        }

        // Validate donor fields
        if (userType.value === "donor") {
            const firstName = document.getElementById("first_name");
            const lastName = document.getElementById("last_name");
            if (!firstName.value.trim()) {
                document.getElementById("first_name-error").style.display = "block";
                isValid = false;
            }
            if (!lastName.value.trim()) {
                document.getElementById("last_name-error").style.display = "block";
                isValid = false;
            }
        }

        // Validate charity fields
        if (userType.value === "charity") {
            const charityName = document.getElementById("charity_name");
            const registrationNumber = document.getElementById("charity_registration_number");
            const acceptedTypes = document.querySelectorAll("#charityFields input[type='checkbox']:checked");
            if (!charityName.value.trim()) {
                document.getElementById("charity_name-error").style.display = "block";
                isValid = false;
            }
            if (!registrationNumber.value.trim()) {
                document.getElementById("charity_registration_number-error").style.display = "block";
                isValid = false;
            }
            if (acceptedTypes.length === 0) {
                document.getElementById("accepted_types-error").style.display = "block";
                isValid = false;
            }
        }

        // Validate email
        const email = document.getElementById("email");
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email.value.trim())) {
            document.getElementById("email-error").style.display = "block";
            isValid = false;
        }

        // Validate password
        const password = document.getElementById("password");
        const confirmPassword = document.getElementById("confirm_password");
        if (password.value !== confirmPassword.value) {
            document.getElementById("confirm_password-error").style.display = "block";
            isValid = false;
        }
        const username = document.getElementById("username");
        if (!username.value.trim()) {
            document.getElementById("username-error").style.display = "block";
            isValid = false;
        }
        const phone = document.getElementById("phone");
        if (!username.value.trim()) {
            document.getElementById("phone-error").style.display = "block";
            isValid = false;
        }


        // Prevent form submission
        if (!isValid) {
            event.preventDefault();
        }
    });

    document.getElementById("user_type").addEventListener("change", toggleFields);
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>