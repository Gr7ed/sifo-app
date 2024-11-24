// Handle dynamic fields in the registration form
function toggleCharityFields() {
    const userType = document.getElementById('user_type')?.value; // Safely access the element
    const charityFields = document.getElementById('charityFields');
    if (charityFields) {
        charityFields.style.display = (userType === 'charity') ? 'block' : 'none';
    }
}

// Function to toggle donation fields based on type
function toggleDonationFields() {
    const typeElement = document.getElementById('type');
    if (!typeElement) return; // Exit if the type element is not found

    const type = typeElement.value;
    const foodFields = document.getElementById('foodFields');
    const nonFoodFields = document.getElementById('nonFoodFields');

    // Reset required attributes
    document.querySelectorAll('#foodFields input, #foodFields textarea').forEach(el => el.required = false);
    document.querySelectorAll('#nonFoodFields input, #nonFoodFields textarea').forEach(el => el.required = false);

    // Show/hide fields based on the selected type
    if (type === 'Food') {
        if (foodFields) foodFields.style.display = 'block';
        if (nonFoodFields) nonFoodFields.style.display = 'none';

        // Set required attributes for visible fields
        document.querySelectorAll('#foodFields input, #foodFields textarea').forEach(el => el.required = true);
    } else if (type === 'Non-Food') {
        if (foodFields) foodFields.style.display = 'none';
        if (nonFoodFields) nonFoodFields.style.display = 'block';

        // Set required attributes for visible fields
        document.querySelectorAll('#nonFoodFields input, #nonFoodFields textarea').forEach(el => el.required = true);
    } else {
        if (foodFields) foodFields.style.display = 'none';
        if (nonFoodFields) nonFoodFields.style.display = 'none';
    }
}


// Initialize scripts on page load
document.addEventListener('DOMContentLoaded', () => {
    // Initialize charity and donation fields
    toggleCharityFields();
    toggleDonationFields();

    // Add event listeners for dynamic updates
    document.getElementById('user_type')?.addEventListener('change', toggleCharityFields);
    document.getElementById('type')?.addEventListener('change', toggleDonationFields);

    const statsSection = document.querySelector('#donation-stats');

    if (statsSection) {
        fetch('/api/get-donation-stats.php')
            .then(response => response.json())
            .then(data => {
                statsSection.innerHTML = `
                    <p>Total Donations: ${data.totalDonations}</p>
                    <p>Total Donors: ${data.totalDonors}</p>
                `;
            })
            .catch(error => console.error('Error fetching stats:', error));
    }

    const registerForm = document.querySelector('#register-form');
    if (registerForm) {
        registerForm.addEventListener('submit', (e) => {
            const username = document.querySelector('#username').value.trim();
            const email = document.querySelector('#email').value.trim();
            const password = document.querySelector('#password').value.trim();
            
            if (!username || !email || !password) {
                e.preventDefault();
                alert('All fields are required.');
            }
        });
    }
});

