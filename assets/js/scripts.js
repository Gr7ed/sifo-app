// Handle dynamic fields in the registration form
function toggleCharityFields() {
    const userType = document.getElementById('user_type')?.value; // Safely access the element
    const charityFields = document.getElementById('charityFields');
    if (charityFields) {
        charityFields.style.display = (userType === 'charity') ? 'block' : 'none';
    }
}

function updateDistricts() {
    const city = document.getElementById('city')?.value; // Safely access the element
    const district = document.getElementById('district');

    if (!district) return; // Exit if district element is not found

    const districtsByCity = {
        "Riyadh": ["Olaya", "Al-Nakheel", "King Fahd"],
        "Jeddah": ["Al-Rawdah", "Al-Andalus", "Al-Balad"],
        "Dammam": ["Al-Nakheel", "Al-Morouj", "Al-Faisaliah"],
        "Mecca": ["Al-Haram", "Al-Aziziyah", "Al-Naseem"],
        "Medina": ["Quba", "Al-Ula", "Al-Masjid Al-Nabawi"]
    };

    // Clear previous options
    district.innerHTML = '<option value="">Select a district</option>';

    // Populate districts based on the selected city
    if (districtsByCity[city]) {
        districtsByCity[city].forEach((dist) => {
            const option = document.createElement('option');
            option.value = dist;
            option.textContent = dist;
            district.appendChild(option);
        });
    }
}

// Function to toggle donation fields based on type
function toggleDonationFields() {
    const type = document.getElementById('type').value;
    const foodFields = document.getElementById('foodFields');
    const nonFoodFields = document.getElementById('nonFoodFields');

    // Reset required attributes
    document.querySelectorAll('#foodFields input, #foodFields textarea').forEach(el => el.required = false);
    document.querySelectorAll('#nonFoodFields input, #nonFoodFields textarea').forEach(el => el.required = false);

    // Show/hide fields based on the selected type
    if (type === 'Food') {
        foodFields.style.display = 'block';
        nonFoodFields.style.display = 'none';

        // Set required attributes for visible fields
        document.querySelectorAll('#foodFields input, #foodFields textarea').forEach(el => el.required = true);
    } else if (type === 'Non-Food') {
        foodFields.style.display = 'none';
        nonFoodFields.style.display = 'block';

        // Set required attributes for visible fields
        document.querySelectorAll('#nonFoodFields input, #nonFoodFields textarea').forEach(el => el.required = true);
    } else {
        foodFields.style.display = 'none';
        nonFoodFields.style.display = 'none';
    }
}

// document.addEventListener('DOMContentLoaded', toggleDonationFields);

// Initialize scripts on page load
document.addEventListener('DOMContentLoaded', () => {
    // Initialize charity and donation fields
    toggleCharityFields();
    toggleDonationFields();

    // Add event listeners for dynamic updates
    document.getElementById('user_type')?.addEventListener('change', toggleCharityFields);
    document.getElementById('city')?.addEventListener('change', updateDistricts);
    document.getElementById('type')?.addEventListener('change', toggleDonationFields);
});
