<x-modal.confirm
    modal-id="confirmDeleteProductModal"
    overlay-id="confirmDeleteProductOverlay"
    panel-id="confirmDeleteProductPanel"
    title="Konfirmasi Hapus Produk"
    message="Yakin ingin menghapus?"
    close-button-id="closeConfirmDeleteProductModal"
    cancel-button-id="cancelConfirmDeleteProductModal"
    cancel-label="Batal"
    submit-label="Iya"
    form-id="hapusProductForm"
    form-action="#"
    method="DELETE"
    submit-class="bg-rose-600 text-white hover:bg-rose-700"
/>

<x-modal.alert
    modal-id="deleteProductFeedbackModal"
    overlay-id="deleteProductFeedbackOverlay"
    panel-id="deleteProductFeedbackPanel"
    title="Informasi"
    message=""
    message-id="deleteProductFeedbackMessage"
    close-button-id="closeDeleteProductFeedbackModal"
    action-button-id="ackDeleteProductFeedbackModal"
    action-label="Oke"
    action-class="bg-slate-900 text-white hover:bg-slate-800"
    z-index="z-[64]"
/>

<div
    id="deleteProductModalState"
    data-success-message="{{ session('delete_success') }}"
></div>
 