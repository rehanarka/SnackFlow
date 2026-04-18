import { buildFallbackImage, closeAnimatedModal, openAnimatedModal, produkCards } from './helpers';

export function initProductDetailModal() {
    const modal = document.getElementById('productDetailModal');
    const overlay = document.getElementById('productDetailOverlay');
    const panel = document.getElementById('productDetailPanel');
    const closeButton = document.getElementById('closeProductDetailModal');
    const image = document.getElementById('productDetailImage');
    const name = document.getElementById('productDetailName');
    const price = document.getElementById('productDetailPrice');
    const stock = document.getElementById('productDetailStock');
    const weight = document.getElementById('productDetailWeight');
    const description = document.getElementById('productDetailDescription');

    if (!modal || !overlay || !panel || !closeButton || !image || !name || !price || !stock || !weight || !description) {
        return;
    }

    const openModal = (card) => {
        image.src = card.dataset.foto || buildFallbackImage();
        name.textContent = card.dataset.nama || 'Produk';
        price.textContent = `Rp ${card.dataset.harga || '0'}`;
        stock.textContent = `${card.dataset.stok || '0'} item`;
        weight.textContent = `${card.dataset.berat || '0'} gram`;
        description.textContent = card.dataset.deskripsi || 'Belum ada deskripsi produk.';

        openAnimatedModal(modal, overlay, panel);
    };

    const closeModal = () => closeAnimatedModal(modal, overlay, panel);

    produkCards.forEach((card) => {
        card.addEventListener('click', (event) => {
            if (event.target.closest('button, form, a, input')) {
                return;
            }

            openModal(card);
        });
    });

    closeButton.addEventListener('click', closeModal);
    modal.addEventListener('click', (event) => {
        if (event.target === modal || event.target === overlay) {
            closeModal();
        }
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && !modal.classList.contains('hidden')) {
            closeModal();
        }
    });
}
