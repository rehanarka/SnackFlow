import { closeAnimatedModal, openAnimatedModal } from './helpers';

export function initAdminProductModals() {
    const addButton = document.getElementById('addProductBtn');
    const addModal = document.getElementById('addProductModal');
    const addOverlay = document.getElementById('addModalOverlay');
    const addContent = document.getElementById('addProductModalContent');
    const closeAddButton = document.getElementById('closeAddProductModal');
    const cancelAddButton = document.getElementById('cancelAddProductModal');

    const updateModal = document.getElementById('updateProductModal');
    const updateOverlay = document.getElementById('updateModalOverlay');
    const updateContent = document.getElementById('updateProductModalContent');
    const closeUpdateButton = document.getElementById('closeUpdateProductModal');
    const cancelUpdateButton = document.getElementById('cancelUpdateProductModal');
    const updateForm = document.getElementById('updateProductForm');
    const updateButtons = document.querySelectorAll('.updateProductBtn');

    const updateNama = document.getElementById('update_nama_produk');
    const updateHarga = document.getElementById('update_harga');
    const updateStok = document.getElementById('update_stok');
    const updateBerat = document.getElementById('update_berat');
    const updateDeskripsi = document.getElementById('update_deskripsi');

    const deleteModal = document.getElementById('hapusProductModal');
    const deleteOverlay = document.getElementById('hapusModalOverlay');
    const deleteContent = document.getElementById('hapusProductModalContent');
    const deleteForm = document.getElementById('hapusProductForm');
    const deleteMessage = document.getElementById('hapusProductMessage');
    const deleteButtons = document.querySelectorAll('.hapusProductBtn');
    const cancelDeleteButton = document.getElementById('cancelHapusProductModal');

    const warningModal = document.getElementById('peringatanProdukModal');
    const warningOverlay = document.getElementById('peringatanProdukOverlay');
    const warningContent = document.getElementById('peringatanProdukModalContent');
    const closeWarningButton = document.getElementById('closePeringatanProdukModal');

    if (!addButton || !addModal || !updateModal) {
        return;
    }

    const openAddModal = () => openAnimatedModal(addModal, addOverlay, addContent);
    const closeAddModal = () => closeAnimatedModal(addModal, addOverlay, addContent);
    const openUpdateModal = () => openAnimatedModal(updateModal, updateOverlay, updateContent);
    const closeUpdateModal = () => closeAnimatedModal(updateModal, updateOverlay, updateContent);
    const openDeleteModal = () => openAnimatedModal(deleteModal, deleteOverlay, deleteContent);
    const closeDeleteModal = () => closeAnimatedModal(deleteModal, deleteOverlay, deleteContent);
    const openWarningModal = () => openAnimatedModal(warningModal, warningOverlay, warningContent);
    const closeWarningModal = () => closeAnimatedModal(warningModal, warningOverlay, warningContent);

    addButton.addEventListener('click', openAddModal);
    closeAddButton?.addEventListener('click', closeAddModal);
    cancelAddButton?.addEventListener('click', closeAddModal);

    addModal.addEventListener('click', (event) => {
        if (event.target === addModal || event.target === addOverlay) {
            closeAddModal();
        }
    });

    updateButtons.forEach((button) => {
        button.addEventListener('click', () => {
            const id = button.dataset.id;

            updateForm.action = `/admin/katalog/update/${id}`;
            updateNama.value = button.dataset.nama || '';
            updateHarga.value = button.dataset.harga || '';
            updateStok.value = button.dataset.stok || '';
            updateBerat.value = button.dataset.berat || '';
            updateDeskripsi.value = button.dataset.deskripsi || '';

            openUpdateModal();
        });
    });

    closeUpdateButton?.addEventListener('click', closeUpdateModal);
    cancelUpdateButton?.addEventListener('click', closeUpdateModal);

    updateModal.addEventListener('click', (event) => {
        if (event.target === updateModal || event.target === updateOverlay) {
            closeUpdateModal();
        }
    });

    deleteButtons.forEach((button) => {
        button.addEventListener('click', () => {
            const id = button.dataset.id;
            const nama = button.dataset.nama || 'produk ini';

            deleteForm.action = `/admin/katalog/hapus/${id}`;
            deleteMessage.textContent = `Apakah kamu yakin ingin menghapus produk "${nama}"?`;
            openDeleteModal();
        });
    });

    cancelDeleteButton?.addEventListener('click', closeDeleteModal);

    deleteModal?.addEventListener('click', (event) => {
        if (event.target === deleteModal || event.target === deleteOverlay) {
            closeDeleteModal();
        }
    });

    closeWarningButton?.addEventListener('click', closeWarningModal);

    warningModal?.addEventListener('click', (event) => {
        if (event.target === warningModal || event.target === warningOverlay) {
            closeWarningModal();
        }
    });

    if (warningModal?.dataset.popupPeringatan === 'true') {
        openWarningModal();
    }
}
