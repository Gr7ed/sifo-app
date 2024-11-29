<?php
include __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../models/DonationModel.php';

// Redirect if the user is not an Individual Donor
if ($_SESSION['user_type'] !== 'donor') {
    header("Location: /sifo-app/views/auth/login.php");
    exit();
}

// Fetch donor details
$userId = $_SESSION['user_id'];
$stmt = $db->prepare("SELECT * FROM donors WHERE user_id = ?");
$stmt->execute([$userId]);
$donor = $stmt->fetch();

// Check if donor data exists
if (!$donor) {
    die("Error: Donor data not found.");
}
$donationModel = new DonationModel($db);
// Fetch recent donations for the logged-in donor
$donations = $donationModel->getDonationsByDonor($userId);

// Include header
include __DIR__ . '/../layouts/header.php';
?>
<style>
    /* Donations */
    .donations {
        margin-top: 20px;
        text-align: center;
    }

    .donations table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }

    .donations table thead {
        background-color: #4a4947;
        color: #faf7f0;
    }

    .donations table th,
    .donations table td {
        padding: 10px;
        text-align: left;
        border: 1px solid #d8d2c2;
    }

    .not-assigned {
        text-align: left;
    }

    .donations table tbody tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    .donations table tbody tr:hover {
        background-color: #e0b021;
    }

    .donations h2 {
        margin-bottom: 10px;
    }

    .donate-link {
        text-align: left;
        margin-top: 10px;
    }

    .donate-link a {
        text-decoration: none;
        color: #4a4947;
        font-weight: bold;
        border: 1px solid #4a4947;
        padding: 8px 15px;
        border-radius: 4px;
        transition: all 0.3s ease;
    }

    .donate-link a:hover {
        background-color: #fccd2a;
        color: #faf7f0;
    }
</style>
<br>
<p class="donate-link"><a href="/sifo-app/views/donations/donate.php"><?php echo translate('make_donation'); ?></a></p>
<div>
    <!-- Recent Donations -->
    <section class="donations">
        <h2><?php echo translate('donations'); ?></h2>
        <table>
            <thead>
                <tr>
                    <th><?php echo translate('date'); ?></th>
                    <th><?php echo translate('donation_type'); ?></th>
                    <th><?php echo translate('description'); ?></th>
                    <th><?php echo translate('condition'); ?></th>
                    <th><?php echo translate('city'); ?></th>
                    <th><?php echo translate('district'); ?></th>
                    <th><?php echo translate('pickup_date_time'); ?></th>
                    <th><?php echo translate('num_amount'); ?></th>
                    <th><?php echo translate('recipient'); ?></th>
                    <th><?php echo translate('status'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($donations)): ?>
                    <p class="not-assigned"><?php echo translate('not-assigned'); ?></p>
                    <?php foreach ($donations as $donation): ?>
                        <tr>
                            <td><?= htmlspecialchars($donation['created_at']); ?></td>
                            <td><?= htmlspecialchars(translateStatus($donation['type'])); ?></td>
                            <td><?= htmlspecialchars($donation['description']); ?></td>
                            <td><?= htmlspecialchars($donation['donate_condition']); ?></td>
                            <td><?= htmlspecialchars(translateStatus($donation['city'])); ?></td>
                            <td><?= htmlspecialchars($donation['district']); ?></td>
                            <td><?= htmlspecialchars($donation['pickup_date_time']); ?></td>
                            <td><?= htmlspecialchars($donation['amount']); ?></td>
                            <td><?= htmlspecialchars($donation['recipient'] ?? 'Not Assigned'); ?></td>
                            <td><?= htmlspecialchars(translateStatus($donation['status'])); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4"><?php echo translate('no-donation'); ?></td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </section>
</div>


<?php include __DIR__ . '/../layouts/footer.php'; ?>