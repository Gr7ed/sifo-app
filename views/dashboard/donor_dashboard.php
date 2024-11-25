<?php
include __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../config/config.php';

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

// Fetch recent donations for the logged-in donor
$donationsStmt = $db->prepare("
    SELECT 
        donations.created_at AS date,
        donations.description AS item,
        charities.charity_name AS recipient,
        donations.status
    FROM donations
    LEFT JOIN charities ON donations.forwarded_to = charities.user_id
    WHERE donations.donor_id = ?
    ORDER BY donations.created_at DESC
    LIMIT 5
");
$donationsStmt->execute([$userId]);
$recentDonations = $donationsStmt->fetchAll(PDO::FETCH_ASSOC);

// Include header
include __DIR__ . '/../layouts/header.php';
?>

<style>
    /* General Styling */
    body {
        font-family: "Alexandria", sans-serif;
        background-color: #faf7f0;
        margin: 0;
        padding: 0;
    }

    .dashboard-container {
        max-width: 1200px;
        margin: 20px auto;
        padding: 20px;
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    h1,
    h2 {
        color: #4a4947;
    }

    /* Dashboard Header */
    .dashboard-header {
        text-align: center;
        margin-bottom: 20px;
    }

    .dashboard-header h1 {
        font-size: 2em;
        margin-bottom: 10px;
        color: #4a4947;
    }

    .dashboard-header p {
        font-size: 1.2em;
        color: #d8d2c2;
    }

    .logout-btn {
        background-color: #4a4947;
        color: #faf7f0;
        border: none;
        padding: 10px 20px;
        font-size: 1em;
        border-radius: 5px;
        cursor: pointer;
        margin-top: 10px;
    }

    .logout-btn:hover {
        background-color: #fccd2a;
    }

    /* Donation Actions */
    .donation-actions ul {
        list-style: none;
        padding: 0;
        margin: 20px 0;
        display: flex;
        gap: 10px;
        justify-content: center;
    }

    .donation-actions ul li a {
        display: inline-block;
        background-color: #4a4947;
        color: #faf7f0;
        text-decoration: none;
        padding: 10px 20px;
        border-radius: 5px;
        font-size: 1em;
    }

    .donation-actions ul li a:hover {
        background-color: #fccd2a;
    }

    /* Recent Donations */
    .recent-donations {
        margin-top: 20px;
    }

    .recent-donations table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }

    .recent-donations table thead {
        background-color: #4a4947;
        color: #faf7f0;
    }

    .recent-donations table th,
    .recent-donations table td {
        padding: 10px;
        text-align: left;
        border: 1px solid #d8d2c2;
    }

    .recent-donations table tbody tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    .recent-donations table tbody tr:hover {
        background-color: #e0b021;
    }

    .recent-donations h2 {
        margin-bottom: 10px;
    }
</style>

<div class="dashboard-container">
    <!-- Dashboard Header -->
    <header class="dashboard-header">
        <h1>Welcome, <?= htmlspecialchars($donor['first_name'] ?? 'Donor'); ?>!</h1>

        <a class="logout-btn"
            href="/sifo-app/controllers/AuthController.php?action=logout"><?php echo translate('logout'); ?></a>
    </header>

    <!-- Donation Actions -->
    <section class="donation-actions">
        <h2>Actions</h2>
        <ul>
            <li><a href="/sifo-app/views/donations/donate.php"><?php echo translate('make_donation'); ?></a></li>
            <li><a href="/sifo-app/views/donations/donation_history.php">View Donation History</a></li>
        </ul>
    </section>

    <!-- Recent Donations -->
    <section class="recent-donations">
        <h2>Recent Donations</h2>
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Item</th>
                    <th>Recipient</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($recentDonations)): ?>
                    <?php foreach ($recentDonations as $donation): ?>
                        <tr>
                            <td><?= htmlspecialchars($donation['date']); ?></td>
                            <td><?= htmlspecialchars($donation['item']); ?></td>
                            <td><?= htmlspecialchars($donation['recipient'] ?? 'Not Assigned'); ?></td>
                            <td><?= htmlspecialchars($donation['status']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4">No recent donations found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </section>
</div>

<!-- Include Footer -->
<?php include __DIR__ . '/../layouts/footer.php'; ?>