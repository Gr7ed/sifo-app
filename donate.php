<?php
include 'config.php';
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
        </select>

        <div id="foodFields" style="display:none;">
            <label for="food_description"><?php echo translate('food_description'); ?>:</label>
            <textarea name="food_description" id="food_description"></textarea>
        </div>

        <div id="nonFoodFields" style="display:none;">
            <label for="non_food_description"><?php echo translate('non_food_description'); ?>:</label>
            <textarea name="non_food_description" id="non_food_description"></textarea>

            <label for="non_food_condition"><?php echo translate('non_food_condition'); ?>:</label>
            <input type="text" name="non_food_condition" id="non_food_condition">
        </div>

        <button type="submit"><?php echo translate('submit'); ?></button>
    </form>
</main>
<script src="js/scripts.js"></script>
<?php include 'includes/footer.php'; ?>