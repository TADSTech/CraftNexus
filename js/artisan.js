document.addEventListener('DOMContentLoaded', () => {
    const artisanSection = document.querySelector('.artisan-section');
    const editProfileSection = document.getElementById('edit-profile-section');
    const editProfileBtn = document.getElementById('edit-profile-btn');
    const cancelEditBtn = document.getElementById('cancel-edit');
    const editForm = document.querySelector('.edit-profile-form');
    const errorContainer = document.createElement('div');
    errorContainer.className = 'error-message';
    artisanSection.prepend(errorContainer);

    // Fetch and display artisan profile
    async function loadProfile() {
        try {
            const response = await fetch('../backend/artisan_self.php');
            const data = await response.json();

            if (data.success) {
                const artisan = data.artisan;
                document.getElementById('artisan-name').textContent = artisan.name;
                document.getElementById('artisan-email').textContent = artisan.email;
                document.getElementById('artisan-phone').textContent = artisan.phone_number || 'Not provided';
                document.getElementById('artisan-skills').textContent = artisan.skills || 'Not specified';
                document.getElementById('artisan-bio').textContent = artisan.bio || 'No bio available';
                document.getElementById('artisan-image').src = artisan.portfolio_image || '../assets/default-image.png';
                document.getElementById('artisan-joined').textContent = artisan.joined_date;
                document.getElementById('artisan-verified').textContent = artisan.is_verified_artisan ? 'Yes' : 'No';
                document.getElementById('artisan-sudo-verified').textContent = artisan.sudo_verified_status ? 'Yes' : 'No';
                document.getElementById('artisan-jobs-completed').textContent = artisan.jobs_completed;
                document.getElementById('artisan-jobs-failed').textContent = artisan.jobs_failed;

                // Pre-fill edit form
                document.getElementById('name').value = artisan.name;
                document.getElementById('phone').value = artisan.phone_number || '';
                document.getElementById('skills').value = artisan.skills || '';
                document.getElementById('bio').value = artisan.bio || '';
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

    // Toggle edit form
    editProfileBtn.addEventListener('click', () => {
        artisanSection.style.display = 'none';
        editProfileSection.style.display = 'block';
        errorContainer.style.display = 'none';
    });

    cancelEditBtn.addEventListener('click', () => {
        artisanSection.style.display = 'block';
        editProfileSection.style.display = 'none';
        errorContainer.style.display = 'none';
    });

    // Handle form submission
    editForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        errorContainer.textContent = '';

        const formData = new FormData(editForm);
        try {
            const response = await fetch('../backend/edit_profile.php', {
                method: 'POST',
                body: formData
            });

            const data = await response.json();

            if (data.success) {
                alert('Profile updated successfully!');
                artisanSection.style.display = 'block';
                editProfileSection.style.display = 'none';
                loadProfile(); // Refresh profile data
            } else {
                errorContainer.textContent = data.error || 'Failed to update profile.';
                errorContainer.style.display = 'block';
            }
        } catch (error) {
            errorContainer.textContent = 'Network error. Please check your connection.';
            errorContainer.style.display = 'block';
        }
    });

    // Initial load
    loadProfile();
});