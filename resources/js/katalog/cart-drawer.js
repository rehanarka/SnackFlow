export function initCartDrawer() {
    const openButton = document.getElementById('openCartButton');
    const modal = document.getElementById('cartModal');
    const overlay = document.getElementById('cartModalOverlay');
    const panel = document.getElementById('cartModalPanel');
    const closeButton = document.getElementById('closeCartModal');

    if (!openButton || !modal || !overlay || !panel) {
        return;
    }

    const openDrawer = () => {
        modal.classList.remove('hidden');

        requestAnimationFrame(() => {
            overlay.classList.remove('opacity-0');
            overlay.classList.add('opacity-100');
            panel.classList.remove('translate-x-full');
            panel.classList.add('translate-x-0');
        });
    };

    const closeDrawer = () => {
        overlay.classList.remove('opacity-100');
        overlay.classList.add('opacity-0');
        panel.classList.remove('translate-x-0');
        panel.classList.add('translate-x-full');

        window.setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    };

    openButton.addEventListener('click', openDrawer);
    closeButton?.addEventListener('click', closeDrawer);

    modal.addEventListener('click', (event) => {
        if (event.target === modal || event.target === overlay) {
            closeDrawer();
        }
    });
}
