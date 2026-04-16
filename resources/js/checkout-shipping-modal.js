const shippingModal = document.getElementById('shippingOptionsModal');
const shippingOverlay = document.getElementById('shippingOptionsOverlay');
const shippingPanel = document.getElementById('shippingOptionsPanel');
const openShippingModalButton = document.getElementById('openShippingOptionsModal');
const closeShippingModalButton = document.getElementById('closeShippingOptionsModal');

if (shippingModal && shippingOverlay && shippingPanel && openShippingModalButton && closeShippingModalButton) {
    const openShippingModal = () => {
        shippingModal.classList.remove('hidden');
        requestAnimationFrame(() => {
            shippingOverlay.classList.remove('opacity-0');
            shippingPanel.classList.remove('translate-y-10');
            shippingPanel.classList.add('sm:-translate-y-1/2');
        });
    };

    const closeShippingModal = () => {
        shippingOverlay.classList.add('opacity-0');
        shippingPanel.classList.add('translate-y-10');
        shippingPanel.classList.remove('sm:-translate-y-1/2');

        setTimeout(() => {
            shippingModal.classList.add('hidden');
        }, 250);
    };

    openShippingModalButton.addEventListener('click', openShippingModal);
    closeShippingModalButton.addEventListener('click', closeShippingModal);
    shippingOverlay.addEventListener('click', closeShippingModal);

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && !shippingModal.classList.contains('hidden')) {
            closeShippingModal();
        }
    });
}
