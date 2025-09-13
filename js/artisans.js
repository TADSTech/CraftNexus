document.addEventListener('DOMContentLoaded', () => {
    const artisanList = document.getElementById('artisan-list');
    const skillFilter = document.getElementById('skill-filter');
    const searchInput = document.getElementById('search');
    const artisanProfile = document.getElementById('artisan-profile');
    const exploreSection = document.querySelector('.explore-section');
    const errorContainer = document.createElement('div');
    errorContainer.className = 'error-message';
    exploreSection.prepend(errorContainer);

    // Fetch and render artisans
    async function loadArtisans() {
        try {
            const response = await fetch('../backend/artisans.php');
            const data = await response.json();

            if (data.success) {
                renderArtisans(data.artisans);
                populateSkillFilter(data.artisans);
            } else {
                errorContainer.textContent = data.error || 'Failed to load artisans.';
                errorContainer.style.display = 'block';
            }
        } catch (error) {
            errorContainer.textContent = 'Network error. Please check your connection.';
            errorContainer.style.display = 'block';
        }
    }

    // Render artisan cards
    function renderArtisans(artisans) {
        artisanList.innerHTML = '';
        artisans.forEach(artisan => {
            const card = document.createElement('div');
            card.className = 'artisan-card';
            card.dataset.id = artisan.id;
            card.innerHTML = `
                <h3>${artisan.name}</h3>
                <p>Skills: ${artisan.skills || 'Not specified'}</p>
            `;
            card.addEventListener('click', () => loadArtisanProfile(artisan.id));
            artisanList.appendChild(card);
        });
    }

    // Populate skill filter dropdown
    function populateSkillFilter(artisans) {
        const skills = new Set();
        artisans.forEach(artisan => {
            if (artisan.skills) {
                artisan.skills.split(',').forEach(skill => skills.add(skill.trim()));
            }
        });
        skills.forEach(skill => {
            const option = document.createElement('option');
            option.value = skill;
            option.textContent = skill;
            skillFilter.appendChild(option);
        });
    }

    // Fetch and display artisan profile
    async function loadArtisanProfile(id) {
        try {
            const response = await fetch(`../backend/artisan.php?id=${id}`);
            const data = await response.json();

            if (data.success) {
                const artisan = data.artisan;
                document.getElementById('artisan-name').textContent = artisan.name;
                document.getElementById('artisan-image').src = artisan.portfolio_image || '../assets/default-image.png';
                document.getElementById('artisan-skills').textContent = artisan.skills || 'Not specified';
                document.getElementById('artisan-joined').textContent = artisan.joined_date;
                document.getElementById('artisan-bio').textContent = artisan.bio || 'No bio available';
                document.getElementById('artisan-phone').textContent = artisan.phone_number || 'Not provided';
                document.getElementById('artisan-verified').textContent = artisan.is_verified_artisan ? 'Yes' : 'No';
                document.getElementById('artisan-sudo-verified').textContent = artisan.sudo_verified_status ? 'Yes' : 'No';
                document.getElementById('artisan-jobs-completed').textContent = artisan.jobs_completed;
                document.getElementById('artisan-jobs-failed').textContent = artisan.jobs_failed;

                // Set up Start a Project button
                const startProjectBtn = document.getElementById('start-project');
                startProjectBtn.onclick = () => {
                    window.location.href = `project.html?artisan_id=${id}`;
                };

                // Show profile, hide list
                artisanProfile.style.display = 'block';
                exploreSection.style.display = 'none';
            } else {
                errorContainer.textContent = data.error || 'Failed to load artisan profile.';
                errorContainer.style.display = 'block';
            }
        } catch (error) {
            errorContainer.textContent = 'Network error. Please check your connection.';
            errorContainer.style.display = 'block';
        }
    }

    // Back to list button
    document.getElementById('back-to-list').addEventListener('click', () => {
        artisanProfile.style.display = 'none';
        exploreSection.style.display = 'block';
        errorContainer.style.display = 'none';
    });

    // Search and filter artisans
    function filterArtisans() {
        const searchTerm = searchInput.value.toLowerCase();
        const selectedSkill = skillFilter.value.toLowerCase();
        const cards = artisanList.querySelectorAll('.artisan-card');

        cards.forEach(card => {
            const name = card.querySelector('h3').textContent.toLowerCase();
            const skills = card.querySelector('p').textContent.toLowerCase();
            const matchesSearch = name.includes(searchTerm) || skills.includes(searchTerm);
            const matchesSkill = !selectedSkill || skills.includes(selectedSkill);
            card.style.display = matchesSearch && matchesSkill ? 'block' : 'none';
        });
    }

    searchInput.addEventListener('input', filterArtisans);
    skillFilter.addEventListener('change', filterArtisans);

    // Initial load
    loadArtisans();
});