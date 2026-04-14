const logoutTriggerButtons = document.querySelectorAll('.logoutTriggerBtn');
const logoutModal = document.getElementById('logoutModal');
const logoutModalOverlay = document.getElementById('logoutModalOverlay');
const logoutModalContent = document.getElementById('logoutModalContent');
const cancelLogoutModal = document.getElementById('cancelLogoutModal');
const closeLogoutModal = document.getElementById('closeLogoutModal');

if (logoutTriggerButtons.length && logoutModal && logoutModalOverlay && logoutModalContent) {
    function openLogoutModal() {
        logoutModal.classList.remove('hidden');
        logoutModal.classList.add('flex');

        requestAnimationFrame(() => {
            logoutModalOverlay.classList.remove('opacity-0');
            logoutModalOverlay.classList.add('opacity-100');

            logoutModalContent.classList.remove('opacity-0', 'scale-95');
            logoutModalContent.classList.add('opacity-100', 'scale-100');
        });
    }

    function closeLogoutConfirmationModal() {
        logoutModalOverlay.classList.remove('opacity-100');
        logoutModalOverlay.classList.add('opacity-0');

        logoutModalContent.classList.remove('opacity-100', 'scale-100');
        logoutModalContent.classList.add('opacity-0', 'scale-95');

        window.setTimeout(() => {
            logoutModal.classList.add('hidden');
            logoutModal.classList.remove('flex');
        }, 300);
    }

    logoutTriggerButtons.forEach((button) => {
        button.addEventListener('click', openLogoutModal);
    });

    cancelLogoutModal?.addEventListener('click', closeLogoutConfirmationModal);
    closeLogoutModal?.addEventListener('click', closeLogoutConfirmationModal);

    logoutModal.addEventListener('click', (event) => {
        if (event.target === logoutModal || event.target === logoutModalOverlay) {
            closeLogoutConfirmationModal();
        }
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && !logoutModal.classList.contains('hidden')) {
            closeLogoutConfirmationModal();
        }
    });
}
