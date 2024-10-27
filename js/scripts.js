function showFields() {
    const donationType = document.getElementById('donation_type').value;
    document.getElementById('foodFields').style.display = donationType === 'Food' ? 'block' : 'none';
    document.getElementById('nonFoodFields').style.display = donationType === 'Non-Food' ? 'block' : 'none';
    document.getElementById('snapDonationFields').style.display = donationType === 'Snap Donation' ? 'block' : 'none';
}
