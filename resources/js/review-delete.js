document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('deleteReviewModal');
    const overlay = document.getElementById('deleteReviewOverlay');
    const closeButton = document.getElementById('closeDeleteReviewModal');
    const cancelButton = document.getElementById('cancelDeleteReviewModal');
    const deleteForm = document.getElementById('deleteReviewForm');
    const message = document.getElementById('deleteReviewMessage');
    const deleteButtons = document.querySelectorAll('.deleteReviewBtn');

    if (!modal || !deleteForm) {
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
            deleteForm.action = button.dataset.deleteUrl || '#';

            if (message) {
                message.textContent = 'Apakah anda yakin ingin menghapus review ini?';
            }

            openModal();
        });
    });

    overlay?.addEventListener('click', closeModal);
    closeButton?.addEventListener('click', closeModal);
    cancelButton?.addEventListener('click', closeModal);
});
