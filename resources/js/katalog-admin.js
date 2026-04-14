const addProductBtn = document.getElementById('addProductBtn');
const addProductModal = document.getElementById('addProductModal');
const addModalOverlay = document.getElementById('addModalOverlay');
const addProductModalContent = document.getElementById('addProductModalContent');
const closeAddProductModal = document.getElementById('closeAddProductModal');
const cancelAddProductModal = document.getElementById('cancelAddProductModal');

const updateProductModal = document.getElementById('updateProductModal');
const updateModalOverlay = document.getElementById('updateModalOverlay');
const updateProductModalContent = document.getElementById('updateProductModalContent');
const closeUpdateProductModal = document.getElementById('closeUpdateProductModal');
const cancelUpdateProductModal = document.getElementById('cancelUpdateProductModal');
const updateProductForm = document.getElementById('updateProductForm');
const updateButtons = document.querySelectorAll('.updateProductBtn');
const hapusButtons = document.querySelectorAll('.hapusProductBtn');

const updateNama = document.getElementById('update_nama_produk');
const updateHarga = document.getElementById('update_harga');
const updateStok = document.getElementById('update_stok');
const updateDeskripsi = document.getElementById('update_deskripsi');
const hapusProductModal = document.getElementById('hapusProductModal');
const hapusModalOverlay = document.getElementById('hapusModalOverlay');
const hapusProductModalContent = document.getElementById('hapusProductModalContent');
const hapusProductForm = document.getElementById('hapusProductForm');
const hapusProductMessage = document.getElementById('hapusProductMessage');
const cancelHapusProductModal = document.getElementById('cancelHapusProductModal');
const peringatanProdukModal = document.getElementById('peringatanProdukModal');
const peringatanProdukOverlay = document.getElementById('peringatanProdukOverlay');
const peringatanProdukModalContent = document.getElementById('peringatanProdukModalContent');
const closePeringatanProdukModal = document.getElementById('closePeringatanProdukModal');
const cariProdukInput = document.getElementById('cariProdukInput');
const produkCards = document.querySelectorAll('.productCard');
const hasilPencarianKosong = document.getElementById('hasilPencarianKosong');

produkCards.forEach((card) => {
    const minusButton = card.querySelector('.quantityMinusBtn');
    const plusButton = card.querySelector('.quantityPlusBtn');
    const quantityValue = card.querySelector('.quantityValue');

    if (!minusButton || !plusButton || !quantityValue) {
        return;
    }

    const stok = Number(card.dataset.stok || 0);

    function updateQuantity(nextValue) {
        const quantity = Math.min(Math.max(nextValue, stok > 0 ? 1 : 0), stok);

        quantityValue.textContent = String(quantity);
        minusButton.disabled = quantity <= 1;
        plusButton.disabled = quantity >= stok || stok < 1;
    }

    updateQuantity(Number(quantityValue.textContent || 1));

    plusButton.addEventListener('click', () => {
        updateQuantity(Number(quantityValue.textContent || 1) + 1);
    });

    minusButton.addEventListener('click', () => {
        updateQuantity(Number(quantityValue.textContent || 1) - 1);
    });
});

if (cariProdukInput && produkCards.length) {
    cariProdukInput.addEventListener('input', function () {
        const keyword = this.value.trim().toLowerCase();
        let jumlahTampil = 0;

        produkCards.forEach((card) => {
            const searchableText = card.dataset.search || '';
            const cocok = searchableText.includes(keyword);

            card.classList.toggle('hidden', !cocok);

            if (cocok) {
                jumlahTampil += 1;
            }
        });

        if (hasilPencarianKosong) {
            hasilPencarianKosong.classList.toggle('hidden', jumlahTampil !== 0 || keyword === '');
        }
    });
}

if (addProductBtn && addProductModal && updateProductModal) {
    function openModal(modal, overlay, content) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');

        requestAnimationFrame(() => {
            overlay.classList.remove('opacity-0');
            overlay.classList.add('opacity-100');

            content.classList.remove('opacity-0', 'scale-95');
            content.classList.add('opacity-100', 'scale-100');
        });
    }

    function closeModal(modal, overlay, content) {
        overlay.classList.remove('opacity-100');
        overlay.classList.add('opacity-0');

        content.classList.remove('opacity-100', 'scale-100');
        content.classList.add('opacity-0', 'scale-95');

        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }, 300);
    }

    function openAddModal() {
        openModal(addProductModal, addModalOverlay, addProductModalContent);
    }

    function closeAddModal() {
        closeModal(addProductModal, addModalOverlay, addProductModalContent);
    }

    function openUpdateModal() {
        openModal(updateProductModal, updateModalOverlay, updateProductModalContent);
    }

    function closeUpdateModal() {
        closeModal(updateProductModal, updateModalOverlay, updateProductModalContent);
    }

    function openHapusModal() {
        openModal(hapusProductModal, hapusModalOverlay, hapusProductModalContent);
    }

    function closeHapusModal() {
        closeModal(hapusProductModal, hapusModalOverlay, hapusProductModalContent);
    }

    function openPeringatanModal() {
        openModal(peringatanProdukModal, peringatanProdukOverlay, peringatanProdukModalContent);
    }

    function closePeringatanModal() {
        closeModal(peringatanProdukModal, peringatanProdukOverlay, peringatanProdukModalContent);
    }

    addProductBtn.addEventListener('click', openAddModal);
    closeAddProductModal?.addEventListener('click', closeAddModal);
    cancelAddProductModal?.addEventListener('click', closeAddModal);

    addProductModal.addEventListener('click', (e) => {
        if (e.target === addProductModal || e.target === addModalOverlay) {
            closeAddModal();
        }
    });

    updateButtons.forEach((button) => {
        button.addEventListener('click', function () {
            const id = this.dataset.id;

            updateProductForm.action = `/admin/katalog/update/${id}`;
            updateNama.value = this.dataset.nama || '';
            updateHarga.value = this.dataset.harga || '';
            updateStok.value = this.dataset.stok || '';
            updateDeskripsi.value = this.dataset.deskripsi || '';

            openUpdateModal();
        });
    });

    closeUpdateProductModal?.addEventListener('click', closeUpdateModal);
    cancelUpdateProductModal?.addEventListener('click', closeUpdateModal);

    updateProductModal.addEventListener('click', (e) => {
        if (e.target === updateProductModal || e.target === updateModalOverlay) {
            closeUpdateModal();
        }
    });

    hapusButtons.forEach((button) => {
        button.addEventListener('click', function () {
            const id = this.dataset.id;
            const nama = this.dataset.nama || 'produk ini';

            hapusProductForm.action = `/admin/katalog/hapus/${id}`;
            hapusProductMessage.textContent = `Apakah kamu yakin ingin menghapus produk "${nama}"?`;

            openHapusModal();
        });
    });

    cancelHapusProductModal?.addEventListener('click', closeHapusModal);

    hapusProductModal?.addEventListener('click', (e) => {
        if (e.target === hapusProductModal || e.target === hapusModalOverlay) {
            closeHapusModal();
        }
    });

    closePeringatanProdukModal?.addEventListener('click', closePeringatanModal);

    peringatanProdukModal?.addEventListener('click', (e) => {
        if (e.target === peringatanProdukModal || e.target === peringatanProdukOverlay) {
            closePeringatanModal();
        }
    });

    if (peringatanProdukModal?.dataset.popupPeringatan === 'true') {
        openPeringatanModal();
    }
}
