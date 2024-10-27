<?php
include 'includes/session.php';
checkSession();
include 'includes/header.php';
?>
<main>
    <h1><?php echo translate('make_donation'); ?></h1>
    <form action="submit_donation.php" method="POST">
        <label for="donation_type"><?php echo translate('donation_type'); ?>:</label>
        <select name="donation_type" id="donation_type" onchange="showFields()">
            <option value=""><?php echo translate('select_donation_type'); ?></option>
            <option value="Food"><?php echo translate('food'); ?></option>
            <option value="Non-Food"><?php echo translate('non_food'); ?></option>
            <option value="Snap Donation"><?php echo translate('snap_donation'); ?></option>
        </select>

        <div id="foodFields" style="display:none;">
            <label for="food_description"><?php echo translate('food_description'); ?>:</label>
            <textarea name="food_description" id="food_description"></textarea>
        </div>

        <div id="nonFoodFields" style="display:none;">
            <label for="non_food_description"><?php echo translate('non_food_description'); ?>:</label>
            <textarea name="non_food_description" id="non_food_description"></textarea>

            <label for="non_food_condition"><?php echo translate('non_food_condition'); ?>:</label>
            <input type="text" name="non_food_condition"
                [_{{{CITATION{{{_1{](https://github.com/Denvey1van1Loenen/Healthone/tree/6a73454688ada715f765c2d9481c9475d169e8a9/apps%2Fhealthone%2Fhtdocs%2FModules%2FDatabase.php)[_{{{CITATION{{{_2{](https://github.com/frankyhung93/BookStackMailer/tree/e566f0332c4f01159206728a7f8028f05485f333/connect_db.php)[_{{{CITATION{{{_3{](https://github.com/ncfcdaniel/GroupDesignProject/tree/c3ff97d0d35b9bcf1f52800de8b3bc3b06e9fc08/Final%20Product%2FAdmin%2FAdminPermissions%2FSupplier%2FDeleteSupplier.php)