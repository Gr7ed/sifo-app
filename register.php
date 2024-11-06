<?php
include 'config.php';
include 'includes/header.php';
?>
<main>
    <h1><?php echo translate('register'); ?></h1>
    <form action="process_register.php" method="POST">
        <label for="username"><?php echo translate('username'); ?>:</label>
        <input type="text" name="username" id="username" required>

        <label for="email"><?php echo translate('email'); ?>:</label>
        <input type="email" name="email" id="email" required>

        <label for="password"><?php echo translate('password'); ?>:</label>
        <input type="password" name="password" id="password" required>

        <label for="user_type"><?php echo translate('user_type'); ?>:</label>
        <select name="user_type" id="user_type" onchange="showUserFields()">
            <option value=""><?php echo translate('select_user_type'); ?></option>
            <option value="Individual"><?php echo translate('individual_donor'); ?></option>
            <option value="Organization"><?php echo translate('organization_donor'); ?></option>
            <option value="Charity"><?php echo translate('charity_beneficiary'); ?></option>
        </select>

        <div id="individualFields" style="display:none;">
            <label for="name"><?php echo translate('full_name'); ?>:</label>
            <input type="text" name="name" id="name">
        </div>

        <div id="organizationFields" style="display:none;">
            <label for="organization_name"><?php echo translate('organization_name'); ?>:</label>
            <input type="text" name="organization_name" id="organization_name">
        </div>

        <div id="charityFields" style="display:none;">
            <label for="charity_registration_number"><?php echo translate('charity_registration_number'); ?>:</label>
            <input type="text" name="charity_registration_number" id="charity_registration_number">

            <label><?php echo translate('accepted_donation_types'); ?>:</label>
            <div>
                <input type="checkbox" name="donation_types[]" id="food" value="Food">
                <label for="food"><?php echo translate('food'); ?></label>
            </div>
            <div>
                <input type="checkbox" name="donation_types[]" id="non_food" value="Non-Food">
                <label for="non_food"><?php echo translate('non_food'); ?></label>
            </div>
            <div>
                <input type="checkbox" name="donation_types[]" id="snap_donation" value="Snap Donation">
                <label for="snap_donation"><?php echo translate('snap_donation'); ?></label>
            </div>
        </div>


        <button type="submit"><?php echo translate('register'); ?></button>
    </form>
</main>
<script src="js/scripts.js"></script>
<?php include 'includes/footer.php'; ?>