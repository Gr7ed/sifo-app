<?php
include __DIR__ . '/../layouts/header.php';
?>

<h1>Snap Donate</h1>
<form method="POST" action="/sifo-app/controllers/DonationController.php?action=snap" enctype="multipart/form-data">
    <label for="donor_name">Your Name:</label>
    <input type="text" name="donor_name" id="donor_name" placeholder="Enter your name" required>

    <label for="amount">Donation Amount:</label>
    <input type="number" name="amount" id="amount" required>

    <button type="submit">Donate</button>
</form>

<?php include __DIR__ . '/../layouts/footer.php'; ?>