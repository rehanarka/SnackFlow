document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('deletePengeluaranModal');
    const overlay = document.getElementById('deletePengeluaranOverlay');
    const closeButton = document.getElementById('closeDeletePengeluaranModal');
    const cancelButton = document.getElementById('cancelDeletePengeluaranModal');
    const form = document.getElementById('deletePengeluaranForm');
    const deleteButtons = document.querySelectorAll('.deletePengeluaranBtn');
    const addModal = document.getElementById('addPengeluaranModal');
    const addOverlay = document.getElementById('addPengeluaranOverlay');
    const openAddButton = document.getElementById('openAddPengeluaranModal');
    const closeAddButton = document.getElementById('closeAddPengeluaranModal');
    const cancelAddButton = document.getElementById('cancelAddPengeluaranModal');
    const editModal = document.getElementById('editPengeluaranModal');
    const editOverlay = document.getElementById('editPengeluaranOverlay');
    const closeEditButton = document.getElementById('closeEditPengeluaranModal');
    const cancelEditButton = document.getElementById('cancelEditPengeluaranModal');
    const editForm = document.getElementById('editPengeluaranForm');
    const editNama = document.getElementById('editNamaPengeluaran');
    const editTanggal = document.getElementById('editTanggalPengeluaran');
    const editNominal = document.getElementById('editNominalPengeluaran');
    const editButtons = document.querySelectorAll('.editPengeluaranBtn');

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

    if (addModal && openAddButton) {
        const openAddModal = () => {
            addModal.classList.remove('hidden');
            addModal.classList.add('flex');
        };

        const closeAddModal = () => {
            addModal.classList.add('hidden');
            addModal.classList.remove('flex');
        };

        openAddButton.addEventListener('click', openAddModal);
        addOverlay?.addEventListener('click', closeAddModal);
        closeAddButton?.addEventListener('click', closeAddModal);
        cancelAddButton?.addEventListener('click', closeAddModal);
    }

    if (!editModal || !editForm || !editNama || !editTanggal || !editNominal) {
        return;
    }

    const openEditModal = () => {
        editModal.classList.remove('hidden');
        editModal.classList.add('flex');
    };

    const closeEditModal = () => {
        editModal.classList.add('hidden');
        editModal.classList.remove('flex');
    };

    editButtons.forEach((button) => {
        button.addEventListener('click', () => {
            editForm.action = button.dataset.updateUrl || '#';
            editNama.value = button.dataset.nama || '';
            editTanggal.value = button.dataset.tanggal || '';
            editNominal.value = button.dataset.nominal || '';
            openEditModal();
        });
    });

    editOverlay?.addEventListener('click', closeEditModal);
    closeEditButton?.addEventListener('click', closeEditModal);
    cancelEditButton?.addEventListener('click', closeEditModal);
});
