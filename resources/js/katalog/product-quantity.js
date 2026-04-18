import { produkCards } from './helpers';

export function initProductQuantityControls() {
    produkCards.forEach((card) => {
        const minusButton = card.querySelector('.quantityMinusBtn');
        const plusButton = card.querySelector('.quantityPlusBtn');
        const quantityValue = card.querySelector('.quantityValue');

        if (!minusButton || !plusButton || !quantityValue) {
            return;
        }

        const stok = Number(card.dataset.stok || 0);

        const updateQuantity = (nextValue) => {
            const quantity = Math.min(Math.max(nextValue, stok > 0 ? 1 : 0), stok);
            const quantityInputs = card.querySelectorAll('.quantityInput');

            quantityValue.textContent = String(quantity);
            quantityInputs.forEach((quantityInput) => {
                quantityInput.value = String(quantity);
            });

            minusButton.disabled = quantity <= 1;
            plusButton.disabled = quantity >= stok || stok < 1;
        };

        updateQuantity(Number(quantityValue.textContent || 1));
        plusButton.addEventListener('click', () => updateQuantity(Number(quantityValue.textContent || 1) + 1));
        minusButton.addEventListener('click', () => updateQuantity(Number(quantityValue.textContent || 1) - 1));
    });
}
