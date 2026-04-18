import { produkCards } from './helpers';

export function initProductSearch() {
    const input = document.getElementById('cariProdukInput');
    const emptyState = document.getElementById('hasilPencarianKosong');

    if (!input || !produkCards.length) {
        return;
    }

    input.addEventListener('input', () => {
        const keyword = input.value.trim().toLowerCase();
        let visibleCount = 0;

        produkCards.forEach((card) => {
            const searchableText = card.dataset.search || '';
            const isMatch = searchableText.includes(keyword);

            card.classList.toggle('hidden', !isMatch);
            if (isMatch) {
                visibleCount += 1;
            }
        });

        if (emptyState) {
            emptyState.classList.toggle('hidden', visibleCount !== 0 || keyword === '');
        }
    });
}
