<?php
require_once __DIR__ . '/../../config/config.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'charity') {
    header("Location: /sifo-app/views/auth/login.php");
    exit();
}

// Fetch the charity's data
$userId = $_SESSION['user_id'];
$stmt = $db->prepare("SELECT * FROM users WHERE user_id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch();

$stmt = $db->prepare("SELECT * FROM charities WHERE user_id = ?");
$stmt->execute([$userId]);
$charity = $stmt->fetch();

if (!$user || !$charity || !is_array($user)) {
    die("Error: Charity data could not be loaded. Please try again.");
}
?>

<?php include_once __DIR__ . '/../layouts/header.php'; ?>

<style>
    .account-container {
        padding: 20px;
        max-width: 700px;
        margin: 0 auto;
        background-color: #faf7f0;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        font-family: 'Alexandria', sans-serif;
    }

    h1 {
        text-align: center;
        color: #4a4947;
        margin-bottom: 20px;
    }

    form {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    label {
        color: #4a4947;
        font-weight: bold;
        margin-bottom: 5px;
    }

    input,
    select {
        width: 100%;
        padding: 10px;
        border: 1px solid #d8d2c2;
        border-radius: 4px;
        font-size: 14px;
    }

    button {
        padding: 10px;
        background-color: #4a4947;
        color: #faf7f0;
        border: none;
        border-radius: 4px;
        font-size: 16px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    button:hover {
        background-color: #fccd2a;
    }
</style>

<div class="account-container">
    <h1><?php echo translate('manage-charity'); ?></h1>
    <form id="charity-form" method="POST" action="/sifo-app/controllers/AccountController.php?action=update">
        <!-- Charity Name -->
        <div>
            <label for="charity_name"><?php echo translate('charity_name'); ?>:</label>
            <input type="text" id="charity_name" name="charity_name"
                value="<?= htmlspecialchars($charity['charity_name'] ?? ''); ?>">
        </div>

        <!-- Charity Registration Number -->
        <div>
            <label for="charity_registration_number"><?php echo translate('charity_reg'); ?>:</label>
            <input type="text" id="charity_registration_number" name="charity_registration_number"
                value="<?= htmlspecialchars($charity['charity_registration_number'] ?? ''); ?>">
        </div>

        <!-- Email -->
        <div>
            <label for="email"><?php echo translate('email'); ?>:</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email'] ?? ''); ?>">
            <small id="email-error" style="color: red; display: none;"><?php echo translate('invalid-email'); ?></small>
        </div>

        <!-- Phone -->
        <div>
            <label for="phone"><?php echo translate('phone_num'); ?>:</label>
            <input type="text" id="phone" name="phone" value="<?= htmlspecialchars($user['phone'] ?? ''); ?>">
        </div>

        <!-- City -->
        <div>
            <label for="city"><?php echo translate('city'); ?>:</label>
            <select id="city" name="city">
                <option value="Riyadh" <?= (isset($user['city']) && $user['city'] === 'Riyadh') ? 'selected' : ''; ?>>
                    <?php echo translate('riyadh'); ?>
                </option>
                <option value="Jeddah" <?= (isset($user['city']) && $user['city'] === 'Jeddah') ? 'selected' : ''; ?>>
                    <?php echo translate('jeddah'); ?>
                </option>
                <option value="Dammam" <?= (isset($user['city']) && $user['city'] === 'Dammam') ? 'selected' : ''; ?>>
                    <?php echo translate('dammam'); ?>
                </option>
                <option value="Mecca" <?= (isset($user['city']) && $user['city'] === 'Mecca') ? 'selected' : ''; ?>>
                    <?php echo translate('mecca'); ?>
                </option>
                <option value="Medina" <?= (isset($user['city']) && $user['city'] === 'Medina') ? 'selected' : ''; ?>>
                    <?php echo translate('medina'); ?>
                </option>
            </select>
        </div>

        <!-- District -->
        <div>
            <label for="district"><?php echo translate('district'); ?>:</label>
            <input type="text" id="district" name="district" value="<?= htmlspecialchars($user['district'] ?? ''); ?>">
        </div>

        <!-- Password -->
        <div>
            <label for="password"><?php echo translate('new-password'); ?>:</label>
            <input type="password" id="password" name="password">
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="confirm_password"><?php echo translate('conf_password'); ?>:</label>
            <input type="password" id="confirm_password" name="confirm_password">
            <small id="password-error"
                style="color: red; display: none;"><?php echo translate('password-mismatch'); ?></small>
        </div>

        <button type="submit"><?php echo translate('update-info'); ?></button>
    </form>
</div>

<script>
    document.getElementById('charity-form').addEventListener('submit', function (e) {
        // Validate email format
        const email = document.getElementById('email').value;
        const emailError = document.getElementById('email-error');
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (email && !emailPattern.test(email)) {
            emailError.style.display = 'block';
            e.preventDefault(); // Prevent form submission
        } else {
            emailError.style.display = 'none';
        }

        // Validate password match
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('confirm_password').value;
        const passwordError = document.getElementById('password-error');

        if (password && password !== confirmPassword) {
            passwordError.style.display = 'block';
            e.preventDefault(); // Prevent form submission
        } else {
            passwordError.style.display = 'none';
        }
    });
</script>


<?php include_once __DIR__ . '/../layouts/footer.php'; ?>