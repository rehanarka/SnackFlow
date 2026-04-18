export const produkCards = document.querySelectorAll('.productCard');

export function buildFallbackImage() {
    return (
        'data:image/svg+xml;charset=UTF-8,' +
        encodeURIComponent(`
            <svg xmlns="http://www.w3.org/2000/svg" width="800" height="600">
                <rect width="100%" height="100%" fill="#e2e8f0"/>
                <text x="50%" y="50%" dominant-baseline="middle" text-anchor="middle" fill="#64748b" font-family="Arial" font-size="28">
                    No Image
                </text>
            </svg>
        `)
    );
}

export function openAnimatedModal(modal, overlay, content) {
    if (!modal || !overlay || !content) {
        return;
    }

    modal.classList.remove('hidden');
    modal.classList.add('flex');

    requestAnimationFrame(() => {
        overlay.classList.remove('opacity-0');
        overlay.classList.add('opacity-100');
        content.classList.remove('opacity-0', 'scale-95');
        content.classList.add('opacity-100', 'scale-100');
    });
}

export function closeAnimatedModal(modal, overlay, content, callback) {
    if (!modal || !overlay || !content) {
        return;
    }

    overlay.classList.remove('opacity-100');
    overlay.classList.add('opacity-0');
    content.classList.remove('opacity-100', 'scale-100');
    content.classList.add('opacity-0', 'scale-95');

    window.setTimeout(() => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        callback?.();
    }, 300);
}
