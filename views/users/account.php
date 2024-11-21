<?php
session_start();
require_once __DIR__ . '/../../config/config.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: /sifo-app/views/auth/login.php");
    exit();
}

// Fetch the user data
$userId = $_SESSION['user_id'];
$stmt = $db->prepare("SELECT * FROM users WHERE user_id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch();

// Check if the user exists
if (!$user || !is_array($user)) {
    die("Error: User data could not be loaded. Please try again.");
}
?>

<?php include_once __DIR__ . '/../layouts/header.php'; ?>

<h1>Manage Your Account</h1>

<form method="POST" action="/sifo-app/controllers/AccountController.php?action=update">
    <!-- First Name -->
    <label for="first_name">First Name:</label>
    <input type="text" id="first_name" name="first_name" value="<?= htmlspecialchars($user['first_name'] ?? ''); ?>"
        required>

    <!-- Last Name -->
    <label for="last_name">Last Name:</label>
    <input type="text" id="last_name" name="last_name" value="<?= htmlspecialchars($user['last_name'] ?? ''); ?>"
        required>

    <!-- Email -->
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email'] ?? ''); ?>" required>

    <!-- Phone -->
    <label for="phone">Phone:</label>
    <input type="text" id="phone" name="phone" value="<?= htmlspecialchars($user['phone'] ?? ''); ?>" required>


    <!-- City -->
    <label for="city"><?= translate('city') ?? 'City'; ?>:</label>
    <select id="city" name="city" onchange="updateDistricts()" required>
        <option value="Riyadh" <?= ($user['city'] === 'Riyadh') ? 'selected' : ''; ?>>
            <?= translate('riyadh') ?? 'Riyadh'; ?>
        </option>
        <option value="Jeddah" <?= ($user['city'] === 'Jeddah') ? 'selected' : ''; ?>>
            <?= translate('jeddah') ?? 'Jeddah'; ?>
        </option>
        <option value="Dammam" <?= ($user['city'] === 'Dammam') ? 'selected' : ''; ?>>
            <?= translate('dammam') ?? 'Dammam'; ?>
        </option>
        <option value="Mecca" <?= ($user['city'] === 'Mecca') ? 'selected' : ''; ?>><?= translate('mecca') ?? 'Mecca'; ?>
        </option>
        <option value="Medina" <?= ($user['city'] === 'Medina') ? 'selected' : ''; ?>>
            <?= translate('medina') ?? 'Medina'; ?>
        </option>
    </select>

    <!-- District -->
    <label for="district"><?= translate('district') ?? 'District'; ?>:</label>
    <select id="district" name="district" required>
        <option value="<?= htmlspecialchars($user['district'] ?? ''); ?>" selected>
            <?= htmlspecialchars($user['district'] ?? 'Select a district'); ?>
        </option>
    </select>

    <!-- Password -->
    <label for="password">New Password (leave blank to keep current password):</label>
    <input type="password" id="password" name="password">

    <button type="submit">Update Information</button>
</form>

<?php include_once __DIR__ . '/../layouts/footer.php'; ?>