document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('deleteArtikelModal');
    const overlay = document.getElementById('deleteArtikelOverlay');
    const closeButton = document.getElementById('closeDeleteArtikelModal');
    const cancelButton = document.getElementById('cancelDeleteArtikelModal');
    const form = document.getElementById('deleteArtikelForm');
    const deleteButtons = document.querySelectorAll('.deleteArtikelBtn');

    if (!modal || !form) {
        return;
    }

    const openModal = () => {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    };

    const closeModal = () => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    };

    deleteButtons.forEach((button) => {
        button.addEventListener('click', () => {
            form.action = button.dataset.deleteUrl || '#';
            openModal();
        });
    });

    overlay?.addEventListener('click', closeModal);
    closeButton?.addEventListener('click', closeModal);
    cancelButton?.addEventListener('click', closeModal);
});
