<?php
include __DIR__ . '/../../includes/session.php';

// Redirect if the user is not an Individual Donor
if ($_SESSION['user_type'] !== 'donor') {
    header("Location: /sifo-app/views/auth/login.php");
    exit();
}

// Include header
include __DIR__ . '/../layouts/header.php';
?>

<!-- Dashboard Content -->
<div class="dashboard-container">
    <header class="dashboard-header">
        <h1>Welcome, <?= htmlspecialchars($_SESSION['username']); ?>!</h1>
        <p>Your Individual Donor Dashboard</p>
        <a class="logout-btn" href="/sifo-app/controllers/AuthController.php?action=logout">Logout</a>
    </header>

    <section class="donation-actions">
        <h2>Actions</h2>
        <ul>
            <li><a href="/sifo-app/views/donations/donate.php">Make a Donation</a></li>
            <li><a href="/sifo-app/views/donations/donation_history.php">View Donation History</a></li>
        </ul>
    </section>

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
                <?php
                // Example placeholder data
                $recentDonations = [
                    ['date' => '2024-11-15', 'item' => 'Clothes', 'recipient' => 'Charity A', 'status' => 'Delivered'],
                    ['date' => '2024-11-10', 'item' => 'Food', 'recipient' => 'Charity B', 'status' => 'Pending'],
                ];

                foreach ($recentDonations as $donation) {
                    echo "<tr>
                            <td>{$donation['date']}</td>
                            <td>{$donation['item']}</td>
                            <td>{$donation['recipient']}</td>
                            <td>{$donation['status']}</td>
                          </tr>";
                }
                ?>
            </tbody>
        </table>
    </section>
</div>

<!-- Include Footer -->
<?php include __DIR__ . '/../layouts/footer.php'; ?>

<style>
    /* Basic styles for the dashboard */
    .dashboard-container {
        font-family: Arial, sans-serif;
        padding: 20px;
    }

    .dashboard-header {
        background: #f4f4f4;
        padding: 10px;
        margin-bottom: 20px;
        border-radius: 5px;
        text-align: center;
    }

    .logout-btn {
        display: inline-block;
        margin-top: 10px;
        padding: 5px 10px;
        background-color: #e74c3c;
        color: white;
        text-decoration: none;
        border-radius: 5px;
    }

    .logout-btn:hover {
        background-color: #c0392b;
    }

    .donation-actions ul {
        list-style: none;
        padding: 0;
    }

    .donation-actions li {
        margin-bottom: 10px;
    }

    .recent-donations table {
        width: 100%;
        border-collapse: collapse;
    }

    .recent-donations th,
    .recent-donations td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: left;
    }

    .recent-donations th {
        background-color: #f4f4f4;
    }
</style>