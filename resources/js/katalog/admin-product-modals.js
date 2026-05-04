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
    const triggerConfirmUpdateButton = document.getElementById('triggerConfirmUpdateProductModal');
    const updateForm = document.getElementById('updateProductForm');
    const updateButtons = document.querySelectorAll('.updateProductBtn');
    const updateProductIdEdit = document.getElementById('update_produk_id_edit');

    const updateNama = document.getElementById('update_nama_produk');
    const updateHarga = document.getElementById('update_harga');
    const updateStok = document.getElementById('update_stok');
    const updateBerat = document.getElementById('update_berat');
    const updateDeskripsi = document.getElementById('update_deskripsi');
    const updateFoto = document.getElementById('update_foto_produk');
    const updatePreviewImage = document.getElementById('updateProductPreviewImage');
    const updatePreviewPlaceholder = document.getElementById('updateProductPreviewPlaceholder');
    const updateDetailNama = document.getElementById('updateDetailNama');
    const updateDetailHarga = document.getElementById('updateDetailHarga');
    const updateDetailStok = document.getElementById('updateDetailStok');
    const updateDetailBerat = document.getElementById('updateDetailBerat');
    const updateDetailDeskripsi = document.getElementById('updateDetailDeskripsi');

    const confirmUpdateModal = document.getElementById('confirmUpdateProductModal');
    const confirmUpdateOverlay = document.getElementById('confirmUpdateProductOverlay');
    const confirmUpdatePanel = document.getElementById('confirmUpdateProductPanel');
    const closeConfirmUpdateButton = document.getElementById('closeConfirmUpdateProductModal');
    const cancelConfirmUpdateButton = document.getElementById('cancelConfirmUpdateProductModal');
    const confirmUpdateForm = confirmUpdateModal?.querySelector('form');

    const feedbackModal = document.getElementById('updateProductFeedbackModal');
    const feedbackOverlay = document.getElementById('updateProductFeedbackOverlay');
    const feedbackPanel = document.getElementById('updateProductFeedbackPanel');
    const feedbackMessage = document.getElementById('updateProductFeedbackMessage');
    const closeFeedbackButton = document.getElementById('closeUpdateProductFeedbackModal');
    const ackFeedbackButton = document.getElementById('ackUpdateProductFeedbackModal');
    const updateModalState = document.getElementById('updateProductModalState');

    const deleteModal = document.getElementById('hapusProductModal');
    const deleteOverlay = document.getElementById('hapusModalOverlay');
    const deleteContent = document.getElementById('hapusProductModalContent');
    const deleteForm = document.getElementById('hapusProductForm');
    const deleteButtons = document.querySelectorAll('.hapusProductBtn');
    const closeDeleteButton = document.getElementById('closeHapusProductModal');
    const cancelDeleteButton = document.getElementById('cancelHapusProductModal');
    const triggerConfirmDeleteButton = document.getElementById('triggerConfirmDeleteProductModal');
    const deletePreviewImage = document.getElementById('hapusProductPreviewImage');
    const deletePreviewPlaceholder = document.getElementById('hapusProductPreviewPlaceholder');
    const deleteDetailNama = document.getElementById('hapusDetailNama');
    const deleteDetailHarga = document.getElementById('hapusDetailHarga');
    const deleteDetailStok = document.getElementById('hapusDetailStok');
    const deleteDetailDeskripsi = document.getElementById('hapusDetailDeskripsi');

    const confirmDeleteModal = document.getElementById('confirmDeleteProductModal');
    const confirmDeleteOverlay = document.getElementById('confirmDeleteProductOverlay');
    const confirmDeletePanel = document.getElementById('confirmDeleteProductPanel');
    const closeConfirmDeleteButton = document.getElementById('closeConfirmDeleteProductModal');
    const cancelConfirmDeleteButton = document.getElementById('cancelConfirmDeleteProductModal');

    const deleteFeedbackModal = document.getElementById('deleteProductFeedbackModal');
    const deleteFeedbackOverlay = document.getElementById('deleteProductFeedbackOverlay');
    const deleteFeedbackPanel = document.getElementById('deleteProductFeedbackPanel');
    const deleteFeedbackMessage = document.getElementById('deleteProductFeedbackMessage');
    const closeDeleteFeedbackButton = document.getElementById('closeDeleteProductFeedbackModal');
    const ackDeleteFeedbackButton = document.getElementById('ackDeleteProductFeedbackModal');
    const deleteModalState = document.getElementById('deleteProductModalState');

    const warningModal = document.getElementById('peringatanProdukModal');
    const warningOverlay = document.getElementById('peringatanProdukOverlay');
    const warningContent = document.getElementById('peringatanProdukModalContent');
    const dismissWarningButton = document.getElementById('ackPeringatanProdukModal');
    const closeWarningButton = document.getElementById('closePeringatanProdukModal');

    if (!addButton || !addModal || !updateModal) {
        return;
    }

    const openAddModal = () => openAnimatedModal(addModal, addOverlay, addContent);
    const closeAddModal = () => closeAnimatedModal(addModal, addOverlay, addContent);
    const openUpdateModal = () => openAnimatedModal(updateModal, updateOverlay, updateContent);
    const closeUpdateModal = () => closeAnimatedModal(updateModal, updateOverlay, updateContent);
    const openConfirmUpdateModal = () => openAnimatedModal(confirmUpdateModal, confirmUpdateOverlay, confirmUpdatePanel);
    const closeConfirmUpdateModal = () => closeAnimatedModal(confirmUpdateModal, confirmUpdateOverlay, confirmUpdatePanel);
    const openFeedbackModal = () => openAnimatedModal(feedbackModal, feedbackOverlay, feedbackPanel);
    const closeFeedbackModal = (callback) => closeAnimatedModal(feedbackModal, feedbackOverlay, feedbackPanel, callback);
    const openDeleteModal = () => openAnimatedModal(deleteModal, deleteOverlay, deleteContent);
    const closeDeleteModal = () => closeAnimatedModal(deleteModal, deleteOverlay, deleteContent);
    const openConfirmDeleteModal = () => openAnimatedModal(confirmDeleteModal, confirmDeleteOverlay, confirmDeletePanel);
    const closeConfirmDeleteModal = () => closeAnimatedModal(confirmDeleteModal, confirmDeleteOverlay, confirmDeletePanel);
    const openDeleteFeedbackModal = () => openAnimatedModal(deleteFeedbackModal, deleteFeedbackOverlay, deleteFeedbackPanel);
    const closeDeleteFeedbackModal = () => closeAnimatedModal(deleteFeedbackModal, deleteFeedbackOverlay, deleteFeedbackPanel);
    const openWarningModal = () => openAnimatedModal(warningModal, warningOverlay, warningContent);
    const closeWarningModal = () => closeAnimatedModal(warningModal, warningOverlay, warningContent);

    const formatRupiah = (value) => `Rp ${new Intl.NumberFormat('id-ID').format(Number(value || 0))}`;

    const setPreviewImage = (src) => {
        if (!updatePreviewImage || !updatePreviewPlaceholder) {
            return;
        }

        if (src) {
            updatePreviewImage.src = src;
            updatePreviewImage.classList.remove('hidden');
            updatePreviewPlaceholder.classList.add('hidden');
            return;
        }

        updatePreviewImage.removeAttribute('src');
        updatePreviewImage.classList.add('hidden');
        updatePreviewPlaceholder.classList.remove('hidden');
    };

    const fillUpdateSummary = ({ nama, harga, stok, berat, deskripsi, foto }) => {
        updateDetailNama.textContent = nama || '-';
        updateDetailHarga.textContent = formatRupiah(harga);
        updateDetailStok.textContent = stok ? `${stok} pcs` : '-';
        updateDetailBerat.textContent = berat ? `${berat} gram` : '-';
        updateDetailDeskripsi.textContent = deskripsi || 'Belum ada deskripsi produk.';
        setPreviewImage(foto);
    };

    const showFeedback = (message) => {
        if (!feedbackMessage) {
            return;
        }

        feedbackMessage.textContent = message;
        openFeedbackModal();
    };

    const setDeletePreviewImage = (src) => {
        if (!deletePreviewImage || !deletePreviewPlaceholder) {
            return;
        }

        if (src) {
            deletePreviewImage.src = src;
            deletePreviewImage.classList.remove('hidden');
            deletePreviewPlaceholder.classList.add('hidden');
            return;
        }

        deletePreviewImage.removeAttribute('src');
        deletePreviewImage.classList.add('hidden');
        deletePreviewPlaceholder.classList.remove('hidden');
    };

    const fillDeleteSummary = ({ nama, harga, stok, deskripsi, foto }) => {
        if (deleteDetailNama) deleteDetailNama.textContent = nama || '-';
        if (deleteDetailHarga) deleteDetailHarga.textContent = formatRupiah(harga);
        if (deleteDetailStok) deleteDetailStok.textContent = stok ? `${stok} pcs` : '-';
        if (deleteDetailDeskripsi) deleteDetailDeskripsi.textContent = deskripsi || 'Belum ada deskripsi produk.';
        setDeletePreviewImage(foto);
    };

    const showDeleteFeedback = (message) => {
        if (!deleteFeedbackMessage) {
            return;
        }

        deleteFeedbackMessage.textContent = message;
        openDeleteFeedbackModal();
    };

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
            const nama = button.dataset.nama || '';
            const harga = button.dataset.harga || '';
            const stok = button.dataset.stok || '';
            const berat = button.dataset.berat || '';
            const deskripsi = button.dataset.deskripsi || '';
            const foto = button.dataset.foto || '';

            updateForm.action = `/admin/katalog/update/${id}`;
            updateNama.value = nama;
            updateHarga.value = harga;
            updateStok.value = stok;
            updateBerat.value = berat;
            updateDeskripsi.value = deskripsi;
            if (updateFoto) {
                updateFoto.value = '';
            }
            if (updateProductIdEdit) {
                updateProductIdEdit.value = id;
            }

            fillUpdateSummary({ nama, harga, stok, berat, deskripsi, foto });

            openUpdateModal();
        });
    });

    closeUpdateButton?.addEventListener('click', closeUpdateModal);
    cancelUpdateButton?.addEventListener('click', () => {
        closeUpdateModal();
        window.setTimeout(() => {
            showFeedback('Perubahan dibatalkan.');
        }, 180);
    });

    triggerConfirmUpdateButton?.addEventListener('click', () => {
        openConfirmUpdateModal();
    });

    updateModal.addEventListener('click', (event) => {
        if (event.target === updateModal || event.target === updateOverlay) {
            closeUpdateModal();
        }
    });

    confirmUpdateForm?.addEventListener('submit', (event) => {
        event.preventDefault();
        updateForm.requestSubmit();
    });

    closeConfirmUpdateButton?.addEventListener('click', closeConfirmUpdateModal);
    cancelConfirmUpdateButton?.addEventListener('click', () => {
        closeConfirmUpdateModal();
        window.setTimeout(() => {
            showFeedback('Perubahan dibatalkan.');
        }, 180);
    });

    confirmUpdateModal?.addEventListener('click', (event) => {
        if (event.target === confirmUpdateModal || event.target === confirmUpdateOverlay) {
            closeConfirmUpdateModal();
        }
    });

    closeFeedbackButton?.addEventListener('click', () => closeFeedbackModal());
    ackFeedbackButton?.addEventListener('click', () => closeFeedbackModal());

    feedbackModal?.addEventListener('click', (event) => {
        if (event.target === feedbackModal || event.target === feedbackOverlay) {
            closeFeedbackModal();
        }
    });

    deleteButtons.forEach((button) => {
        button.addEventListener('click', () => {
            const id = button.dataset.id;
            const nama = button.dataset.nama || 'produk ini';
            const harga = button.dataset.harga || '';
            const stok = button.dataset.stok || '';
            const deskripsi = button.dataset.deskripsi || '';
            const foto = button.dataset.foto || '';

            deleteForm.action = `/admin/katalog/hapus/${id}`;
            fillDeleteSummary({ nama, harga, stok, deskripsi, foto });
            openConfirmDeleteModal()();
        });
    });

    closeDeleteButton?.addEventListener('click', closeDeleteModal);
    cancelDeleteButton?.addEventListener('click', closeDeleteModal);

    triggerConfirmDeleteButton?.addEventListener('click', () => {
        openConfirmDeleteModal();
    });

    deleteModal?.addEventListener('click', (event) => {
        if (event.target === deleteModal || event.target === deleteOverlay) {
            closeDeleteModal();
        }
    });

    closeConfirmDeleteButton?.addEventListener('click', closeConfirmDeleteModal);
    cancelConfirmDeleteButton?.addEventListener('click', () => {
        closeConfirmDeleteModal();
        window.setTimeout(() => {
            openDeleteModal();
        }, 180);
    });

    confirmDeleteModal?.addEventListener('click', (event) => {
        if (event.target === confirmDeleteModal || event.target === confirmDeleteOverlay) {
            closeConfirmDeleteModal();
        }
    });

    closeDeleteFeedbackButton?.addEventListener('click', closeDeleteFeedbackModal);
    ackDeleteFeedbackButton?.addEventListener('click', closeDeleteFeedbackModal);

    deleteFeedbackModal?.addEventListener('click', (event) => {
        if (event.target === deleteFeedbackModal || event.target === deleteFeedbackOverlay) {
            closeDeleteFeedbackModal();
        }
    });

    dismissWarningButton?.addEventListener('click', closeWarningModal);
    closeWarningButton?.addEventListener('click', closeWarningModal);

    warningModal?.addEventListener('click', (event) => {
        if (event.target === warningModal || event.target === warningOverlay) {
            closeWarningModal();
        }
    });

    if (warningModal?.dataset.popupPeringatan === 'true') {
        openWarningModal();
    }

    if (deleteModalState?.dataset.successMessage) {
        showDeleteFeedback('Data berhasil dihapus.');
    }

    if (updateModalState) {
        const successMessage = updateModalState.dataset.successMessage;
        const errorMessage = updateModalState.dataset.errorMessage;
        const hasErrors = updateModalState.dataset.hasErrors === 'true';
        const editId = updateModalState.dataset.editId;
        const oldNama = updateModalState.dataset.oldNama || '';
        const oldHarga = updateModalState.dataset.oldHarga || '';
        const oldStok = updateModalState.dataset.oldStok || '';
        const oldBerat = updateModalState.dataset.oldBerat || '';
        const oldDeskripsi = updateModalState.dataset.oldDeskripsi || '';

        if (successMessage) {
            showFeedback('Data berhasil diedit.');
        } else if (hasErrors && errorMessage) {
            if (editId) {
                updateForm.action = `/admin/katalog/update/${editId}`;
                updateNama.value = oldNama;
                updateHarga.value = oldHarga;
                updateStok.value = oldStok;
                updateBerat.value = oldBerat;
                updateDeskripsi.value = oldDeskripsi;
                if (updateProductIdEdit) {
                    updateProductIdEdit.value = editId;
                }

                fillUpdateSummary({
                    nama: oldNama,
                    harga: oldHarga,
                    stok: oldStok,
                    berat: oldBerat,
                    deskripsi: oldDeskripsi,
                    foto: '',
                });
            }

            showFeedback(errorMessage);

            ackFeedbackButton?.addEventListener('click', () => {
                if (!updateModal.classList.contains('hidden')) {
                    return;
                }

                openUpdateModal();
            }, { once: true });

            closeFeedbackButton?.addEventListener('click', () => {
                if (!updateModal.classList.contains('hidden')) {
                    return;
                }

                openUpdateModal();
            }, { once: true });
        }
    }
}
