<?php
function forwardDonation($donation_id, $donation_type)
{
    global $pdo;

    // Fetch eligible charities based on donation_type
    $stmt = $pdo->prepare('SELECT charity_id FROM charity_preferences WHERE donation_type = ?');
    $stmt->execute([$donation_type]);
    $eligible_charities = $stmt->fetchAll();

    if (count($eligible_charities) > 0) {
        foreach ($eligible_charities as $charity) {
            // Logic to forward donation to eligible charities
            // For example, send notification or update the database to associate the donation with the charity
            echo "Donation $donation_id forwarded to charity " . $charity['charity_id'] . "<br>";
        }
    } else {
        echo "No eligible charities found for donation $donation_id.";
    }
}
?>