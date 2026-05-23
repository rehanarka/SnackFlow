document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.ratingStars').forEach((group) => {
        const inputs = Array.from(group.querySelectorAll('input[type="radio"]'));
        const labels = Array.from(group.querySelectorAll('label[data-rating-value]'));

        const paintStars = (value) => {
            labels.forEach((label) => {
                const ratingValue = Number(label.dataset.ratingValue || 0);
                label.classList.toggle('text-amber-400', ratingValue <= value);
                label.classList.toggle('text-slate-200', ratingValue > value);
            });
        };

        const selectedInput = inputs.find((input) => input.checked);
        paintStars(Number(selectedInput?.value || 0));

        inputs.forEach((input) => {
            input.addEventListener('change', () => {
                paintStars(Number(input.value || 0));
            });
        });

        labels.forEach((label) => {
            label.addEventListener('mouseenter', () => {
                paintStars(Number(label.dataset.ratingValue || 0));
            });

            label.addEventListener('mouseleave', () => {
                const checkedInput = inputs.find((input) => input.checked);
                paintStars(Number(checkedInput?.value || 0));
            });
        });
    });
});
