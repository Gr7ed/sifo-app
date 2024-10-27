function showFields() {
    const donationType = document.getElementById('donation_type').value;
    document.getElementById('foodFields').style.display = donationType === 'Food' ? 'block' : 'none';
    document.getElementById('nonFoodFields').style.display = donationType === 'Non-Food' ? 'block' : 'none';
}
function showUserFields() {
    const userType = document.getElementById('user_type').value;
    document.getElementById('individualFields').style.display = userType === 'Individual' ? 'block' : 'none';
    document.getElementById('organizationFields').style.display = userType === 'Organization' ? 'block' : 'none';
    document.getElementById('charityFields').style.display = userType === 'Charity' ? 'block' : 'none';
}

