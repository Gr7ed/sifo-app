<?php
include __DIR__ . '/../../config/config.php';
include __DIR__ . '/../layouts/header.php';
?>

<h1><?php echo translate('sign_up'); ?></h1>

<form id="register-form" method="POST" action="/sifo-app/controllers/AuthController.php?action=register">
    <!-- Username -->
    <label for="username"><?php echo translate('user_name'); ?></label>
    <input type="text" name="username" id="username" required placeholder=<?php echo translate('enter_your_username'); ?>>

    <!-- First Name -->
    <label for="first_name"><?php echo translate('first_name'); ?></label>
    <input type="text" name="first_name" id="first_name" required placeholder="Enter your first name">

    <!-- Last Name -->
    <label for="last_name"><?php echo translate('last_name'); ?></label>
    <input type="text" name="last_name" id="last_name" required placeholder="Enter your last name">

    <!-- Phone -->
    <label for="phone"><?php echo translate('phone_num'); ?></label>
    <input type="text" name="phone" id="phone" required placeholder="Enter your phone number">

    <!-- City -->
    <label for="city"><?php echo translate('city'); ?>:</label>
    <select name="city" class="city" required>
        <option value=""><?php echo translate('select_city'); ?></option>
        <option value="Riyadh"><?php echo translate('riyadh'); ?></option>
        <option value="Jeddah"><?php echo translate('jeddah'); ?></option>
        <option value="Dammam"><?php echo translate('dammam'); ?></option>
        <option value="Mecca"><?php echo translate('mecca'); ?></option>
        <option value="Medina"><?php echo translate('medina'); ?></option>
    </select>

    <!-- District -->
    <label for="district"><?php echo translate('district'); ?>:</label>
    <input type="text" name="district" class="district" required placeholder="Enter your district">


    <!-- Email -->
    <label for="email"><?php echo translate('email'); ?></label>
    <input type="email" name="email" id="email" required placeholder="Enter your email">

    <!-- Password -->
    <label for="password"><?php echo translate('password'); ?></label>
    <input type="password" name="password" id="password" required placeholder="Enter your password">

    <!-- User Type -->
    <label for="user_type"><?php echo translate('user_type'); ?></label>
    <select name="user_type" id="user_type" required onchange="toggleCharityFields()">
        <option value=""><?php echo translate('select_user_type'); ?></option>
        <option value="donor"><?php echo translate('donor'); ?></option>
        <option value="charity"><?php echo translate('charity'); ?></option>
    </select>

    <!-- Charity Fields -->
    <div id="charityFields" style="display: none;">
        <label for="charity_registration_number">Charity Registration Number:</label>
        <input type="text" name="charity_registration_number" id="charity_registration_number">
        <label for="charity_name">Charity Name:</label>
        <input type="text" name="charity_name" id="charity_name">
        <label for="accepted_types">Types of Donations Accepted:</label>
        <input type="checkbox" id="food" name="accepted_types[]" value="Food"> Food
        <input type="checkbox" id="non_food" name="accepted_types[]" value="Non-Food"> Non-Food
    </div>

    <button type="submit"><?php echo translate('sign_up'); ?></button>
</form>

<?php include __DIR__ . '/../layouts/footer.php'; ?>