<?php
include __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../layouts/header.php';

// Ensure the user is a charity
if ($_SESSION['user_type'] !== 'charity') {
    die("Access denied. Only charities can create campaigns.");
}
?>

<style>
    main {
        padding: 20px;
        max-width: 600px;
        margin: 0 auto;
        background-color: #faf7f0;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        font-family: 'Alexandria', sans-serif;
    }

    h1 {
        text-align: center;
        margin-bottom: 20px;
        color: #4a4947;
        font-size: 28px;
    }

    form {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    form label {
        font-size: 14px;
        font-weight: bold;
        color: #4a4947;
    }

    form input,
    form textarea,
    form select {
        width: 100%;
        padding: 10px;
        border: 1px solid #d8d2c2;
        border-radius: 5px;
        font-size: 14px;
        background-color: #fff;
    }

    form textarea {
        resize: vertical;
        min-height: 100px;
    }

    form input:focus,
    form textarea:focus {
        outline: none;
        border-color: #fccd2a;
        box-shadow: 0 0 5px rgba(252, 205, 42, 0.5);
    }

    button {
        margin-top: 20px;
        padding: 12px;
        background-color: #4a4947;
        color: #faf7f0;
        border: none;
        border-radius: 5px;
        font-size: 16px;
        font-weight: bold;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    button:hover {
        background-color: #fccd2a;
        color: #4a4947;
    }
</style>

<main>
    <h1><?php echo translate('create-campaign'); ?></h1>

    <form id="campaign-form" method="POST" action="/sifo-app/controllers/CampaignController.php?action=create">
        <label for="title"><?php echo translate('campaign_name'); ?>:</label>
        <input type="text" name="title" id="title" placeholder="<?php echo translate('enter-campaign-title'); ?>"
            required>
        <small id="title-error"
            style="color: red; display: none;"><?php echo translate('campaign_name_error'); ?></small>

        <label for="description"><?php echo translate('description'); ?>:</label>
        <textarea name="description" id="description"
            placeholder="<?php echo translate('enter-campaign-description'); ?>" required></textarea>
        <small id="description-error"
            style="color: red; display: none;"><?php echo translate('description_error'); ?></small>

        <div>
            <label for="target_amount"><?php echo translate('target'); ?>:</label>
            <input type="number" name="target_amount" id="target_amount" step="0.01"
                placeholder="<?php echo translate('enter-target'); ?>" required min="0.01">
            <small id="target_amount-error"
                style="color: red; display: none;"><?php echo translate('target_amount_error'); ?></small>
        </div>

        <div>
            <label for="start_date"><?php echo translate('start_date'); ?>:</label>
            <input type="datetime-local" name="start_date" id="start_date" required>
            <small id="start_date-error"
                style="color: red; display: none;"><?php echo translate('start_date_error'); ?></small>
        </div>

        <div>
            <label for="end_date"><?php echo translate('end_date'); ?>:</label>
            <input type="datetime-local" name="end_date" id="end_date" required>
            <small id="end_date-error"
                style="color: red; display: none;"><?php echo translate('end_date_error'); ?></small>
        </div>

        <button type="submit"><?php echo translate('create-campaign'); ?></button>
    </form>

</main>

<script>
    document.getElementById("campaign-form").addEventListener("submit", function (event) {
        let isValid = true;

        // Campaign name validation
        const title = document.getElementById("title");
        const titleError = document.getElementById("title-error");
        if (title.value.trim() === "") {
            titleError.style.display = "block";
            title.style.borderColor = "red";
            isValid = false;
        } else {
            titleError.style.display = "none";
            title.style.borderColor = "";
        }

        // Description validation
        const description = document.getElementById("description");
        const descriptionError = document.getElementById("description-error");
        if (description.value.trim() === "") {
            descriptionError.style.display = "block";
            description.style.borderColor = "red";
            isValid = false;
        } else {
            descriptionError.style.display = "none";
            description.style.borderColor = "";
        }

        // Target amount validation
        const targetAmount = document.getElementById("target_amount");
        const targetAmountError = document.getElementById("target_amount-error");
        if (parseFloat(targetAmount.value) <= 0 || isNaN(targetAmount.value)) {
            targetAmountError.style.display = "block";
            targetAmount.style.borderColor = "red";
            isValid = false;
        } else {
            targetAmountError.style.display = "none";
            targetAmount.style.borderColor = "";
        }

        // Start date validation
        const startDate = document.getElementById("start_date");
        const startDateError = document.getElementById("start_date-error");
        if (startDate.value.trim() === "") {
            startDateError.style.display = "block";
            startDate.style.borderColor = "red";
            isValid = false;
        } else {
            startDateError.style.display = "none";
            startDate.style.borderColor = "";
        }

        // End date validation
        const endDate = document.getElementById("end_date");
        const endDateError = document.getElementById("end_date-error");
        if (endDate.value.trim() === "") {
            endDateError.style.display = "block";
            endDate.style.borderColor = "red";
            isValid = false;
        } else if (new Date(endDate.value) <= new Date(startDate.value)) {
            endDateError.style.display = "block";
            endDate.style.borderColor = "red";
            endDateError.textContent = "<?php echo translate('end_date_after_start_error'); ?>";
            isValid = false;
        } else {
            endDateError.style.display = "none";
            endDate.style.borderColor = "";
        }

        // Prevent form submission if validation fails
        if (!isValid) {
            event.preventDefault();
        }
    });
</script>



<?php include __DIR__ . '/../layouts/footer.php'; ?>