document.addEventListener('DOMContentLoaded', () => {
    const userName = document.getElementById('user-name');
    const userEmail = document.getElementById('user-email');
    const userVerified = document.getElementById('user-verified');
    const errorContainer = document.createElement('div');
    errorContainer.className = 'error-message';
    document.querySelector('.profile-section').prepend(errorContainer);

    // Fetch user profile
    async function loadProfile() {
        try {
            const response = await fetch('../backend/profile.php');
            const data = await response.json();

            if (data.success) {
                userName.textContent = data.user.name;
                userEmail.textContent = data.user.email;
                userVerified.textContent = data.user.is_verified_non_artisan ? 'Yes' : 'No';
            } else {
                errorContainer.textContent = data.error || 'Failed to load profile.';
                errorContainer.style.display = 'block';
                if (data.error === 'Unauthorized access') {
                    window.location.href = 'login.html';
                }
            }
        } catch (error) {
            errorContainer.textContent = 'Network error. Please check your connection.';
            errorContainer.style.display = 'block';
        }
    }

    // Edit profile placeholder
    document.getElementById('edit-profile').addEventListener('click', () => {
        alert('Edit profile functionality coming soon!');
    });

    // Initial load
    loadProfile();
});