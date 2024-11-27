<?php
include __DIR__ . '/../../config/config.php';
include __DIR__ . '/../layouts/header.php';

// Determine language direction
$language = $_SESSION['lang'] ?? 'en'; // Default to 'en'
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

    h3 {
        text-align: left;
        margin-bottom: 20px;
        margin-top: 20px;
        color: #4a4947;
    }

    form {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    form div {
        display: flex;
        gap: 15px;
        justify-content: space-between;
        flex-direction:
            <?= $direction === 'rtl' ? 'row-reverse' : 'row' ?>
        ;
    }

    form input,
    form select {
        flex: 1;
        padding: 10px;
        margin: 8px;
        font-size: 14px;
        border: 1px solid #d8d2c2;
        border-radius: 5px;
        background-color: #fff;
    }

    label {
        display: block;
        color: #4a4947;
        padding-top: 14px;
        font-size: 14px;
        font-weight: bold;
    }

    #commonFields,
    #donorFields,
    #charityFields {
        display: none;
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

    .checkbox-group {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        align-items: center;
    }

    .checkbox-group label {
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .full-width {
        flex: 1 1 100%;
    }
</style>

<main>
    <h1><?php echo translate('sign_up'); ?></h1>
    <?php if (!empty($errors)): ?>
        <div style="color: red; margin-bottom: 20px;">
            <?php foreach ($errors as $error): ?>
                <p><?= htmlspecialchars($error) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    <form id="register-form" method="POST" action="/sifo-app/controllers/AuthController.php?action=register">
        <!-- User Type -->
        <label for="user_type"><?php echo translate('user_type'); ?></label>
        <select name="user_type" id="user_type" required onchange="toggleFields()">
            <option value=""><?php echo translate('select_user_type'); ?></option>
            <option value="donor"><?php echo translate('donor'); ?></option>
            <option value="charity"><?php echo translate('charity'); ?></option>
        </select>

        <!-- Donor-Specific Fields -->
        <div id="donorFields">
            <div>
                <div>
                    <label for="first_name"><?php echo translate('first_name'); ?></label>
                    <input type="text" name="first_name" id="first_name"
                        placeholder="<?php echo translate('enter_first_name'); ?>">
                </div>
                <div>
                    <label for="last_name"><?php echo translate('last_name'); ?></label>
                    <input type="text" name="last_name" id="last_name"
                        placeholder="<?php echo translate('enter_last_name'); ?>">
                </div>
            </div>
        </div>

        <!-- Common Fields -->
        <div id="commonFields">
            <div>
                <label for="username"><?php echo translate('username'); ?></label>
                <input type="text" name="username" id="username"
                    placeholder="<?php echo translate('enter_username'); ?>">
            </div>

            <div>
                <label for="email"><?php echo translate('email'); ?></label>
                <input type="email" name="email" id="email" placeholder="<?php echo translate('enter_email'); ?>">
            </div>

            <div>
                <label for="password"><?php echo translate('password'); ?></label>
                <input type="password" name="password" id="password"
                    placeholder="<?php echo translate('enter_password'); ?>">
                <label for="confirm_password"><?php echo translate('conf_password'); ?></label>
                <input type="password" name="confirm_password" id="confirm_password"
                    placeholder="<?php echo translate('confirm_password'); ?>">
            </div>

            <h3><?php echo translate('address'); ?>:</h3>

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
                <label for="district"><?php echo translate('district'); ?></label>
                <input type="text" name="district" id="district"
                    placeholder="<?php echo translate('enter_district'); ?>">
            </div>

            <div>
                <label for="phone"><?php echo translate('phone_num'); ?></label>
                <input type="text" name="phone" id="phone" placeholder="<?php echo translate('enter_phone'); ?>">
            </div>
        </div>


        <!-- Charity-Specific Fields -->
        <div id="charityFields">
            <h3><?php echo translate('charity-details'); ?>:</h3>
            <div>
                <label for="charity_name"><?php echo translate('charity_name'); ?></label>
                <input type="text" name="charity_name" id="charity_name"
                    placeholder="<?php echo translate('enter_charity_name'); ?>">
            </div>
            <div>
                <label for="charity_registration_number"><?php echo translate('charity_reg'); ?></label>
                <input type="text" name="charity_registration_number" id="charity_registration_number"
                    placeholder="<?php echo translate('enter_registration_number'); ?>">
            </div>
            <div>
                <label for="accepted_types"><?php echo translate('accepted_types'); ?>:</label>
                <div class="checkbox-group">
                    <label><input type="checkbox" id="food" name="accepted_types[]" value="Food">
                        <?php echo translate('food'); ?></label>
                    <label><input type="checkbox" id="non_food" name="accepted_types[]" value="Non-Food">
                        <?php echo translate('non_food'); ?></label>
                </div>
            </div>
        </div>

        <button type="submit"><?php echo translate('sign_up'); ?></button>
    </form>
</main>

<script>
    function toggleFields() {
        const userType = document.getElementById('user_type')?.value;
        const commonFields = document.getElementById('commonFields');
        const donorFields = document.getElementById('donorFields');
        const charityFields = document.getElementById('charityFields');

        if (userType === 'donor') {
            commonFields.style.display = 'block';
            donorFields.style.display = 'block';
            charityFields.style.display = 'none';
        } else if (userType === 'charity') {
            commonFields.style.display = 'block';
            donorFields.style.display = 'none';
            charityFields.style.display = 'block';
        } else {
            commonFields.style.display = 'none';
            donorFields.style.display = 'none';
            charityFields.style.display = 'none';
        }
    }

    document.addEventListener('DOMContentLoaded', toggleFields);
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>