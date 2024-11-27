<?php
session_start();
require_once __DIR__ . '/../../config/config.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'donor') {
    header("Location: /sifo-app/views/auth/login.php");
    exit();
}

// Fetch the donor's data
$userId = $_SESSION['user_id'];
$stmt = $db->prepare("SELECT * FROM users WHERE user_id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch();

if (!$user || !is_array($user)) {
    die("Error: User data could not be loaded. Please try again.");
}
?>

<?php include_once __DIR__ . '/../layouts/header.php'; ?>

<style>
    .account-container {
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
        margin-bottom: 5px;
        font-weight: bold;
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
    <h1>Manage Your Account</h1>
    <form method="POST" action="/sifo-app/controllers/AccountController.php?action=update">
        <!-- First Name -->
        <div>
            <label for="first_name">First Name:</label>
            <input type="text" id="first_name" name="first_name"
                value="<?= htmlspecialchars($user['first_name'] ?? ''); ?>">
        </div>

        <!-- Last Name -->
        <div>
            <label for="last_name">Last Name:</label>
            <input type="text" id="last_name" name="last_name"
                value="<?= htmlspecialchars($user['last_name'] ?? ''); ?>">
        </div>

        <!-- Email -->
        <div>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email'] ?? ''); ?>">
        </div>

        <!-- Phone -->
        <div>
            <label for="phone">Phone:</label>
            <input type="text" id="phone" name="phone" value="<?= htmlspecialchars($user['phone'] ?? ''); ?>">
        </div>

        <!-- Gender -->
        <div>
            <label for="phone">Gender:</label>
            <select id="gender" name="gender">
                <option value="Male" <?= (isset($user['gender']) && $user['gender'] === 'Male') ? 'selected' : ''; ?>>
                    Male</option>
                <option value="Female" <?= (isset($user['gender']) && $user['gender'] === 'gender') ? 'selected' : ''; ?>>
                    Female</option>
            </select>
        </div>

        <!-- City -->
        <div>
            <label for="city">City:</label>
            <select id="city" name="city">
                <option value="Riyadh" <?= (isset($user['city']) && $user['city'] === 'Riyadh') ? 'selected' : ''; ?>>
                    Riyadh</option>
                <option value="Jeddah" <?= (isset($user['city']) && $user['city'] === 'Jeddah') ? 'selected' : ''; ?>>
                    Jeddah</option>
                <option value="Dammam" <?= (isset($user['city']) && $user['city'] === 'Dammam') ? 'selected' : ''; ?>>
                    Dammam</option>
                <option value="Mecca" <?= (isset($user['city']) && $user['city'] === 'Mecca') ? 'selected' : ''; ?>>Mecca
                </option>
                <option value="Medina" <?= (isset($user['city']) && $user['city'] === 'Medina') ? 'selected' : ''; ?>>
                    Medina</option>
            </select>
        </div>

        <!-- District -->
        <div>
            <label for="district">District:</label>
            <input type="text" id="district" name="district" value="<?= htmlspecialchars($user['district'] ?? ''); ?>">
        </div>

        <!-- Password -->
        <div>
            <label for="password">New Password (leave blank to keep current password):</label>
            <input type="password" id="password" name="password">
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="confirm_password">Confirm Password:</label>
            <input type="password" id="confirm_password" name="confirm_password">
        </div>

        <button type="submit">Update Information</button>
    </form>
</div>

<?php include_once __DIR__ . '/../layouts/footer.php'; ?>