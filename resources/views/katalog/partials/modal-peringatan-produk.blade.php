@php($pesan = $pesan ?? null)

<x-modal.alert
    modal-id="peringatanProdukModal"
    overlay-id="peringatanProdukOverlay"
    panel-id="peringatanProdukModalContent"
    title="Peringatan"
    :message="$pesan"
    close-button-id="closePeringatanProdukModal"
    action-button-id="ackPeringatanProdukModal"
    action-label="OK"
    title-class="text-red-600"
    action-class="bg-red-600 text-white hover:bg-red-700"
    z-index="z-50"
    data-popup-peringatan="{{ $pesan ? 'true' : 'false' }}"
/>
