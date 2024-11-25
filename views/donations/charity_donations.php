<?php
include __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../models/DonationModel.php';

// Ensure the user is logged in and is a charity
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'charity') {
    die("Access denied. Only charities can view this page.");
}

// Fetch charity details
$userId = $_SESSION['user_id'];
$stmt = $db->prepare("SELECT * FROM charities WHERE user_id = ?");
$stmt->execute([$userId]);
$charity = $stmt->fetch();

if (!$charity) {
    die("Error: Charity data not found.");
}

$charityCity = $charity['city'];
$acceptedTypes = explode(',', $charity['accepted_types']); // Assume 'accepted_types' is a comma-separated string

// Initialize donation model
$donationModel = new DonationModel($db);

// Get donations based on status, city, and type
$status = $_GET['status'] ?? 'Available'; // Default to 'Available'
$type = $_GET['type'] ?? ($acceptedTypes[0] ?? 'Food'); // Default to first accepted type
$donations = $donationModel->getDonationsByStatus($status, $charityCity, $type);

include __DIR__ . '/../layouts/header.php';
?>
<style>
    h2 {
        text-align: center;
        color: #4a4947;
    }

    .donation {
        background-color: #d8d2c2;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 15px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .donation p {
        margin: 5px 0;
        color: #4a4947;
    }

    .donation form {
        margin-top: 10px;
    }

    .donation button {
        padding: 8px 12px;
        background-color: #4a4947;
        color: #faf7f0;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .donation button:hover {
        background-color: #fccd2a;
    }
</style>

<div>
    <!-- Donations Section -->
    <h2>Donations</h2>
    <form method="GET" action="">
        <label for="status">Filter by Status:</label>
        <select name="status" id="status" onchange="this.form.submit()">
            <option value="Available" <?= $status === 'Available' ? 'selected' : ''; ?>>Available</option>
            <option value="Pending" <?= $status === 'Pending' ? 'selected' : ''; ?>>Pending</option>
            <option value="Forwarded" <?= $status === 'Forwarded' ? 'selected' : ''; ?>>Forwarded</option>
            <option value="Delivered" <?= $status === 'Delivered' ? 'selected' : ''; ?>>Delivered</option>
        </select>

        <label for="type">Filter by Type:</label>
        <select name="type" id="type" onchange="this.form.submit()">
            <?php foreach ($acceptedTypes as $acceptedType): ?>
                <option value="<?= htmlspecialchars($acceptedType); ?>" <?= $type === $acceptedType ? 'selected' : ''; ?>>
                    <?= htmlspecialchars($acceptedType); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </form>
    <br>
    <?php foreach ($donations as $donation): ?>
        <div class="donation">
            <p><strong>Donation ID:</strong> <?= htmlspecialchars($donation['donation_id']); ?></p>
            <p><strong>Status:</strong> <?= htmlspecialchars($donation['status']); ?></p>
            <p><strong>Description:</strong> <?= htmlspecialchars($donation['description']); ?></p>
            <p><strong>Pick-up Time & Date:</strong> <?= htmlspecialchars($donation['pickup_date_time']); ?></p>
            <p><strong>Donor Name:</strong> <?= htmlspecialchars($donation['donor_name']); ?></p>
            <p><strong>Donor Phone:</strong> <?= htmlspecialchars($donation['donor_phone']); ?></p>
            <p><strong>City:</strong> <?= htmlspecialchars($donation['city']); ?></p>
            <p><strong>District:</strong> <?= htmlspecialchars($donation['district']); ?></p>

            <?php if ($donation['status'] === 'Available'): ?>
                <form method="POST" action="/sifo-app/controllers/DonationController.php?action=update_status">
                    <input type="hidden" name="donation_id" value="<?= htmlspecialchars($donation['donation_id']); ?>">
                    <input type="hidden" name="new_status" value="Pending">
                    <button type="submit">Receive Donation</button>
                </form>
            <?php elseif ($donation['status'] === 'Pending'): ?>
                <form method="POST" action="/sifo-app/controllers/DonationController.php?action=update_status">
                    <input type="hidden" name="donation_id" value="<?= htmlspecialchars($donation['donation_id']); ?>">
                    <input type="hidden" name="new_status" value="Delivered">
                    <button type="submit">Mark as Delivered</button>
                </form>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</div>


<?php include __DIR__ . '/../layouts/footer.php'; ?>