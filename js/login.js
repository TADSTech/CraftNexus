document.addEventListener('DOMContentLoaded', () => {
    const loginForm = document.querySelector('.login-form');
    const errorContainer = document.createElement('div');
    errorContainer.className = 'error-message';
    loginForm.prepend(errorContainer);

    loginForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        errorContainer.textContent = '';

        const formData = new FormData(loginForm);
        try {
            const response = await fetch('../backend/auth.php', {
                method: 'POST',
                body: formData
            });

            const data = await response.json();

            if (data.success) {
                // Redirect based on user type
                if (data.user_type === 'artisan') {
                    window.location.href = 'artisan.html';
                } else if (data.user_type === 'non_artisan') {
                    window.location.href = 'project.html';
                }
            } else {
                errorContainer.textContent = data.error || 'An error occurred. Please try again.';
                errorContainer.style.display = 'block';
            }
        } catch (error) {
            errorContainer.textContent = 'Network error. Please check your connection.';
            errorContainer.style.display = 'block';
        }
    });
});