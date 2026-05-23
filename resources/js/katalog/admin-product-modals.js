import { closeAnimatedModal, openAnimatedModal } from './helpers';

export function initAdminProductModals() {
    const productCards = document.querySelectorAll('.productCard');
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

    const detailModal = document.getElementById('productDetailModal');
    const detailOverlay = document.getElementById('productDetailOverlay');
    const detailContent = document.getElementById('productDetailContent');
    const closeDetailButton = document.getElementById('closeProductDetailModal');
    const detailPreviewImage = document.getElementById('productDetailPreviewImage');
    const detailPreviewPlaceholder = document.getElementById('productDetailPreviewPlaceholder');
    const detailNama = document.getElementById('productDetailNama');
    const detailHarga = document.getElementById('productDetailHarga');
    const detailStok = document.getElementById('productDetailStok');
    const detailBerat = document.getElementById('productDetailBerat');
    const detailDeskripsi = document.getElementById('productDetailDeskripsi');
    const detailActionReviewButton = document.getElementById('detailActionReviewProduct');
    const detailActionEditButton = document.getElementById('detailActionEditProduct');
    const detailActionDeleteButton = document.getElementById('detailActionDeleteProduct');

    const deleteForm = document.getElementById('hapusProductForm');
    const confirmDeleteModal = document.getElementById('confirmDeleteProductModal');
    const confirmDeleteOverlay = document.getElementById('confirmDeleteProductOverlay');
    const confirmDeletePanel = document.getElementById('confirmDeleteProductPanel');
    const closeConfirmDeleteButton = document.getElementById('closeConfirmDeleteProductModal');
    const cancelConfirmDeleteButton = document.getElementById('cancelConfirmDeleteProductModal');
    const confirmDeleteMessage = document.getElementById('confirmDeleteProductMessage');

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
    const openDetailModal = () => openAnimatedModal(detailModal, detailOverlay, detailContent);
    const closeDetailModal = () => closeAnimatedModal(detailModal, detailOverlay, detailContent);
    const openUpdateModal = () => openAnimatedModal(updateModal, updateOverlay, updateContent);
    const closeUpdateModal = () => closeAnimatedModal(updateModal, updateOverlay, updateContent);
    const openConfirmUpdateModal = () => openAnimatedModal(confirmUpdateModal, confirmUpdateOverlay, confirmUpdatePanel);
    const closeConfirmUpdateModal = () => closeAnimatedModal(confirmUpdateModal, confirmUpdateOverlay, confirmUpdatePanel);
    const openFeedbackModal = () => openAnimatedModal(feedbackModal, feedbackOverlay, feedbackPanel);
    const closeFeedbackModal = (callback) => closeAnimatedModal(feedbackModal, feedbackOverlay, feedbackPanel, callback);
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

    const setDetailPreviewImage = (src) => {
        if (!detailPreviewImage || !detailPreviewPlaceholder) {
            return;
        }

        if (src) {
            detailPreviewImage.src = src;
            detailPreviewImage.classList.remove('hidden');
            detailPreviewPlaceholder.classList.add('hidden');
            return;
        }

        detailPreviewImage.removeAttribute('src');
        detailPreviewImage.classList.add('hidden');
        detailPreviewPlaceholder.classList.remove('hidden');
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

    const fillDetailSummary = ({ nama, harga, stok, berat, deskripsi, foto }) => {
        if (detailNama) detailNama.textContent = nama || '-';
        if (detailHarga) detailHarga.textContent = formatRupiah(harga);
        if (detailStok) detailStok.textContent = stok ? `${stok} pcs` : '-';
        if (detailBerat) detailBerat.textContent = berat ? `${berat} gram` : '-';
        if (detailDeskripsi) detailDeskripsi.textContent = deskripsi || 'Belum ada deskripsi produk.';
        setDetailPreviewImage(foto);
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

    productCards.forEach((card) => {
        card.addEventListener('click', () => {
            const productPayload = {
                id: card.dataset.id,
                nama: card.dataset.nama || '',
                harga: card.dataset.harga || '',
                stok: card.dataset.stok || '',
                berat: card.dataset.berat || '',
                deskripsi: card.dataset.deskripsi || '',
                foto: card.dataset.foto || '',
            };

            fillDetailSummary(productPayload);

            if (detailActionEditButton) {
                detailActionEditButton.dataset.id = productPayload.id;
                detailActionEditButton.dataset.nama = productPayload.nama;
                detailActionEditButton.dataset.harga = productPayload.harga;
                detailActionEditButton.dataset.stok = productPayload.stok;
                detailActionEditButton.dataset.berat = productPayload.berat;
                detailActionEditButton.dataset.deskripsi = productPayload.deskripsi;
                detailActionEditButton.dataset.foto = productPayload.foto;
            }

            if (detailActionReviewButton) {
                detailActionReviewButton.href = `/admin/katalog/${productPayload.id}/review`;
            }

            if (detailActionDeleteButton) {
                detailActionDeleteButton.dataset.id = productPayload.id;
                detailActionDeleteButton.dataset.nama = productPayload.nama;
            }

            openDetailModal();
        });
    });

    closeDetailButton?.addEventListener('click', closeDetailModal);
    detailModal?.addEventListener('click', (event) => {
        if (event.target === detailModal || event.target === detailOverlay) {
            closeDetailModal();
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

    detailActionEditButton?.addEventListener('click', () => {
        closeDetailModal();

        const payloadSource = detailActionEditButton.dataset;
        updateForm.action = `/admin/katalog/update/${payloadSource.id}`;
        updateNama.value = payloadSource.nama || '';
        updateHarga.value = payloadSource.harga || '';
        updateStok.value = payloadSource.stok || '';
        updateBerat.value = payloadSource.berat || '';
        updateDeskripsi.value = payloadSource.deskripsi || '';
        if (updateFoto) {
            updateFoto.value = '';
        }
        if (updateProductIdEdit) {
            updateProductIdEdit.value = payloadSource.id || '';
        }

        fillUpdateSummary({
            nama: payloadSource.nama || '',
            harga: payloadSource.harga || '',
            stok: payloadSource.stok || '',
            berat: payloadSource.berat || '',
            deskripsi: payloadSource.deskripsi || '',
            foto: payloadSource.foto || '',
        });

        window.setTimeout(() => {
            openUpdateModal();
        }, 180);
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

    detailActionDeleteButton?.addEventListener('click', () => {
        const id = detailActionDeleteButton.dataset.id;
        const nama = detailActionDeleteButton.dataset.nama || 'produk ini';

        deleteForm.action = `/admin/katalog/hapus/${id}`;

        if (confirmDeleteMessage) {
            confirmDeleteMessage.textContent = `Yakin ingin menghapus produk "${nama}"?`;
        }

        closeDetailModal();

        window.setTimeout(() => {
            openConfirmDeleteModal();
        }, 180);
    });

    closeConfirmDeleteButton?.addEventListener('click', closeConfirmDeleteModal);
    cancelConfirmDeleteButton?.addEventListener('click', closeConfirmDeleteModal);

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
