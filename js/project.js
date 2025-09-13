document.addEventListener('DOMContentLoaded', () => {
    const projectForm = document.querySelector('.project-form');
    const artisanSelect = document.getElementById('artisan');
    const errorContainer = document.createElement('div');
    errorContainer.className = 'error-message';
    projectForm.prepend(errorContainer);

    // Get artisan_id from URL
    const urlParams = new URLSearchParams(window.location.search);
    const preselectedArtisanId = urlParams.get('artisan_id');

    // Fetch artisans for dropdown
    async function loadArtisans() {
        try {
            const response = await fetch('../backend/artisans.php');
            const data = await response.json();

            if (data.success) {
                data.artisans.forEach(artisan => {
                    const option = document.createElement('option');
                    option.value = artisan.id;
                    option.textContent = artisan.name;
                    if (preselectedArtisanId && artisan.id == preselectedArtisanId) {
                        option.selected = true;
                    }
                    artisanSelect.appendChild(option);
                });
            } else {
                errorContainer.textContent = data.error || 'Failed to load artisans.';
                errorContainer.style.display = 'block';
            }
        } catch (error) {
            errorContainer.textContent = 'Network error. Please check your connection.';
            errorContainer.style.display = 'block';
        }
    }

    // Handle form submission
    projectForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        errorContainer.textContent = '';

        const formData = new FormData(projectForm);
        try {
            const response = await fetch('../backend/project_request.php', {
                method: 'POST',
                body: formData
            });

            const data = await response.json();

            if (data.success) {
                alert('Project request submitted successfully!');
                window.location.href = 'explore_artisan.html';
            } else {
                errorContainer.textContent = data.error || 'Failed to submit project.';
                errorContainer.style.display = 'block';
            }
        } catch (error) {
            errorContainer.textContent = 'Network error. Please check your connection.';
            errorContainer.style.display = 'block';
        }
    });

    // Initial load
    loadArtisans();
});