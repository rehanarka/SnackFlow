const profileToggle = document.getElementById('profileDropdownToggle');
const profileDropdown = document.getElementById('profileDropdownMenu');

if (profileToggle && profileDropdown) {
    function openDropdown() {
        profileDropdown.classList.remove('hidden', 'pointer-events-none', 'translate-y-2', 'opacity-0');
        profileDropdown.classList.add('translate-y-0', 'opacity-100');
        profileToggle.setAttribute('aria-expanded', 'true');
    }

    function closeDropdown() {
        profileDropdown.classList.add('pointer-events-none', 'translate-y-2', 'opacity-0');
        profileDropdown.classList.remove('translate-y-0', 'opacity-100');
        profileToggle.setAttribute('aria-expanded', 'false');

        window.setTimeout(() => {
            if (profileToggle.getAttribute('aria-expanded') === 'false') {
                profileDropdown.classList.add('hidden');
            }
        }, 200);
    }

    function toggleDropdown() {
        if (profileToggle.getAttribute('aria-expanded') === 'true') {
            closeDropdown();
            return;
        }

        openDropdown();
    }

    profileToggle.addEventListener('click', (event) => {
        event.stopPropagation();
        toggleDropdown();
    });

    profileDropdown.addEventListener('click', (event) => {
        event.stopPropagation();
    });

    document.addEventListener('click', () => {
        if (profileToggle.getAttribute('aria-expanded') === 'true') {
            closeDropdown();
        }
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            closeDropdown();
        }
    });
}
