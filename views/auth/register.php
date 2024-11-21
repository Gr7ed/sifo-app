<?php
include __DIR__ . '/../../config/config.php';
include __DIR__ . '/../layouts/header.php';
?>

<h1>Register</h1>

<form id="register-form" method="POST" action="/sifo-app/controllers/AuthController.php?action=register">
    <!-- Username -->
    <label for="username">Username:</label>
    <input type="text" name="username" id="username" required placeholder="Enter your username">

    <!-- First Name -->
    <label for="first_name">First Name:</label>
    <input type="text" name="first_name" id="first_name" required placeholder="Enter your first name">

    <!-- Last Name -->
    <label for="last_name">Last Name:</label>
    <input type="text" name="last_name" id="last_name" required placeholder="Enter your last name">

    <!-- Phone -->
    <label for="phone">Phone Number:</label>
    <input type="text" name="phone" id="phone" required placeholder="Enter your phone number">

    <!-- City -->
    <label for="city"><?php echo translate('city'); ?>:</label>
    <select name="city" id="city" required onchange="updateDistricts()">
        <option value="">Select a city</option>
        <option value="Riyadh"><?php echo translate('riyadh'); ?></option>
        <option value="Jeddah"><?php echo translate('jeddah'); ?></option>
        <option value="Dammam"><?php echo translate('dammam'); ?></option>
        <option value="Mecca"><?php echo translate('mecca'); ?></option>
        <option value="Medina"><?php echo translate('medina'); ?></option>
    </select>

    <!-- District -->
    <label for="district"><?php echo translate('district'); ?>:</label>
    <select name="district" id="district" required>
        <option value="">Select a district</option>
    </select>

    <!-- Email -->
    <label for="email">Email:</label>
    <input type="email" name="email" id="email" required placeholder="Enter your email">

    <!-- Password -->
    <label for="password">Password:</label>
    <input type="password" name="password" id="password" required placeholder="Enter your password">

    <!-- User Type -->
    <label for="user_type">User Type:</label>
    <select name="user_type" id="user_type" required onchange="toggleCharityFields()">
        <option value="">Select user type</option>
        <option value="donor">Donor</option>
        <option value="charity">Charity</option>
    </select>

    <!-- Charity Fields -->
    <div id="charityFields" style="display: none;">
        <label for="charity_registration_number">Charity Registration Number:</label>
        <input type="text" name="charity_registration_number" id="charity_registration_number">

        <label for="accepted_types">Types of Donations Accepted:</label>
        <input type="checkbox" id="food" name="accepted_types[]" value="Food"> Food
        <input type="checkbox" id="non_food" name="accepted_types[]" value="Non-Food"> Non-Food
    </div>

    <button type="submit">Register</button>
</form>

<?php include __DIR__ . '/../layouts/footer.php'; ?>