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
const updateBerat = document.getElementById('update_berat');
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
const openCartButton = document.getElementById('openCartButton');
const cartModal = document.getElementById('cartModal');
const cartModalOverlay = document.getElementById('cartModalOverlay');
const cartModalPanel = document.getElementById('cartModalPanel');
const closeCartModal = document.getElementById('closeCartModal');
const productDetailModal = document.getElementById('productDetailModal');
const productDetailOverlay = document.getElementById('productDetailOverlay');
const productDetailPanel = document.getElementById('productDetailPanel');
const closeProductDetailModal = document.getElementById('closeProductDetailModal');
const productDetailImage = document.getElementById('productDetailImage');
const productDetailName = document.getElementById('productDetailName');
const productDetailPrice = document.getElementById('productDetailPrice');
const productDetailStock = document.getElementById('productDetailStock');
const productDetailWeight = document.getElementById('productDetailWeight');
const productDetailDescription = document.getElementById('productDetailDescription');

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
        const quantityInput = card.querySelector('.quantityInput');

        quantityValue.textContent = String(quantity);
        if (quantityInput) {
            quantityInput.value = String(quantity);
        }
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

if (
    productDetailModal &&
    productDetailOverlay &&
    productDetailPanel &&
    closeProductDetailModal &&
    productDetailImage &&
    productDetailName &&
    productDetailPrice &&
    productDetailStock &&
    productDetailWeight &&
    productDetailDescription
) {
    const openProductDetailModal = (card) => {
        const fallbackImage =
            'data:image/svg+xml;charset=UTF-8,' +
            encodeURIComponent(`
                <svg xmlns="http://www.w3.org/2000/svg" width="800" height="600">
                    <rect width="100%" height="100%" fill="#e2e8f0"/>
                    <text x="50%" y="50%" dominant-baseline="middle" text-anchor="middle" fill="#64748b" font-family="Arial" font-size="28">
                        No Image
                    </text>
                </svg>
            `);

        productDetailImage.src = card.dataset.foto || fallbackImage;
        productDetailName.textContent = card.dataset.nama || 'Produk';
        productDetailPrice.textContent = `Rp ${card.dataset.harga || '0'}`;
        productDetailStock.textContent = `${card.dataset.stok || '0'} item`;
        productDetailWeight.textContent = `${card.dataset.berat || '0'} gram`;
        productDetailDescription.textContent = card.dataset.deskripsi || 'Belum ada deskripsi produk.';

        productDetailModal.classList.remove('hidden');

        requestAnimationFrame(() => {
            productDetailOverlay.classList.remove('opacity-0');
            productDetailOverlay.classList.add('opacity-100');
            productDetailPanel.classList.remove('opacity-0', 'scale-95');
            productDetailPanel.classList.add('opacity-100', 'scale-100');
        });
    };

    const closeProductModal = () => {
        productDetailOverlay.classList.remove('opacity-100');
        productDetailOverlay.classList.add('opacity-0');
        productDetailPanel.classList.remove('opacity-100', 'scale-100');
        productDetailPanel.classList.add('opacity-0', 'scale-95');

        setTimeout(() => {
            productDetailModal.classList.add('hidden');
        }, 300);
    };

    produkCards.forEach((card) => {
        card.addEventListener('click', (event) => {
            if (event.target.closest('button, form, a, input')) {
                return;
            }

            openProductDetailModal(card);
        });
    });

    closeProductDetailModal.addEventListener('click', closeProductModal);
    productDetailModal.addEventListener('click', (event) => {
        if (event.target === productDetailModal || event.target === productDetailOverlay) {
            closeProductModal();
        }
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && !productDetailModal.classList.contains('hidden')) {
            closeProductModal();
        }
    });
}

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

if (openCartButton && cartModal && cartModalOverlay && cartModalPanel) {
    function openCartModal() {
        cartModal.classList.remove('hidden');

        requestAnimationFrame(() => {
            cartModalOverlay.classList.remove('opacity-0');
            cartModalOverlay.classList.add('opacity-100');
            cartModalPanel.classList.remove('translate-x-full');
            cartModalPanel.classList.add('translate-x-0');
        });
    }

    function closeCartDrawer() {
        cartModalOverlay.classList.remove('opacity-100');
        cartModalOverlay.classList.add('opacity-0');
        cartModalPanel.classList.remove('translate-x-0');
        cartModalPanel.classList.add('translate-x-full');

        setTimeout(() => {
            cartModal.classList.add('hidden');
        }, 300);
    }

    openCartButton.addEventListener('click', openCartModal);
    closeCartModal?.addEventListener('click', closeCartDrawer);

    cartModal.addEventListener('click', (event) => {
        if (event.target === cartModal || event.target === cartModalOverlay) {
            closeCartDrawer();
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
            updateBerat.value = this.dataset.berat || '';
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
