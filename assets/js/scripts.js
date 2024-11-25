
function toggleFields() {
    const userType = document.getElementById('user_type')?.value; // Safely access the element
    const commonFields = document.getElementById('commonFields');
    const donorFields = document.getElementById('donorFields');
    const charityFields = document.getElementById('charityFields');

    if (userType === 'donor') {
        commonFields.style.display = 'block';
        donorFields.style.display = 'block';
        charityFields.style.display = 'none';
    } else if (userType === 'charity') {
        commonFields.style.display = 'block';
        donorFields.style.display = 'none';
        charityFields.style.display = 'block';
    } else {
        commonFields.style.display = 'none';
        donorFields.style.display = 'none';
        charityFields.style.display = 'none';
    }
}



// Function to toggle donation fields based on type
function toggleDonationFields() {
    const typeElement = document.getElementById('type');
    if (!typeElement) return; // Exit if the type element is not found

    const type = typeElement.value;
    const foodFields = document.getElementById('foodFields');
    const nonFoodFields = document.getElementById('nonFoodFields');

    // Reset required attributes for inputs in both field sets
    document.querySelectorAll('#foodFields input, #foodFields textarea').forEach(el => el.required = false);
    document.querySelectorAll('#nonFoodFields input, #nonFoodFields textarea').forEach(el => el.required = false);

    // Toggle field visibility and update required attributes
    if (type === 'Food') {
        if (foodFields) foodFields.style.display = 'block';
        if (nonFoodFields) nonFoodFields.style.display = 'none';

        document.querySelectorAll('#foodFields input, #foodFields textarea').forEach(el => el.required = true);
    } else if (type === 'Non-Food') {
        if (foodFields) foodFields.style.display = 'none';
        if (nonFoodFields) nonFoodFields.style.display = 'block';

        document.querySelectorAll('#nonFoodFields input, #nonFoodFields textarea').forEach(el => el.required = true);
    } else {
        if (foodFields) foodFields.style.display = 'none';
        if (nonFoodFields) nonFoodFields.style.display = 'none';
    }
}



// Form validation for registration
function validateRegistrationForm(event) {
    const username = document.querySelector('#username')?.value.trim();
    const email = document.querySelector('#email')?.value.trim();
    const password = document.querySelector('#password')?.value.trim();
    const confirmPassword = document.getElementById('confirm_password')?.value;

    if (!username || !email || !password) {
        event.preventDefault();
        alert('All fields are required.');
        return;
    }

    if (password !== confirmPassword) {
        event.preventDefault();
        alert('Passwords do not match. Please try again.');
    }
}

// Initialize scripts on page load
document.addEventListener('DOMContentLoaded', () => {
    // Toggle fields on page load
    toggleFields();
    toggleDonationFields();

    // Event listeners for dynamic updates
// Initialize on page load

    document.getElementById('user_type')?.addEventListener('change', toggleFields);
    document.getElementById('type')?.addEventListener('change', toggleDonationFields);

    // Load donation statistics (if applicable)
    loadDonationStats();

});
