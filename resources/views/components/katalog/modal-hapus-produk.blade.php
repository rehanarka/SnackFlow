<div id="hapusProductModal" class="fixed inset-0 z-[60] hidden items-center justify-center px-4 py-5">
    <div id="hapusModalOverlay" class="absolute inset-0 bg-slate-950/45 opacity-0 transition-opacity duration-300 ease-out"></div>

    <div id="hapusProductModalContent" class="relative flex max-h-[88vh] w-full max-w-3xl flex-col overflow-hidden rounded-[2rem] bg-white opacity-0 scale-95 shadow-2xl transition-all duration-300 ease-out">
        <div class="flex items-start justify-between gap-4 border-b border-slate-200 px-5 py-4 sm:px-6">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-rose-600">Hapus Produk</p>
                <h2 class="mt-2 text-xl font-bold text-slate-900 sm:text-2xl">Detail Produk yang Ingin Dihapus</h2>
                <p class="mt-2 text-sm leading-6 text-slate-600">Periksa kembali data produk. Jika sudah yakin, lanjutkan ke konfirmasi penghapusan.</p>
            </div>
            <button type="button" id="closeHapusProductModal" class="rounded-full bg-slate-100 px-3 py-2 text-sm font-semibold text-slate-600 transition duration-300 hover:bg-slate-200 hover:cursor-pointer">&times;</button>
        </div>

        <div class="grid flex-1 gap-4 overflow-y-auto px-5 py-5 md:grid-cols-[0.9fr_1.1fr] lg:px-6">
            <section class="space-y-4 rounded-[1.5rem] border border-slate-200 bg-slate-50 p-4">
                <div class="overflow-hidden rounded-[1.5rem] bg-white shadow-sm ring-1 ring-slate-200">
                    <div class="flex aspect-[4/3] max-h-64 items-center justify-center bg-slate-100">
                        <img id="hapusProductPreviewImage" src="" alt="Preview produk" class="hidden h-full w-full object-cover">
                        <div id="hapusProductPreviewPlaceholder" class="px-6 text-center">
                            <p class="text-sm font-semibold text-slate-700">Foto Produk</p>
                            <p class="mt-1 text-xs leading-5 text-slate-500">Preview foto produk akan tampil di sini sebelum data dihapus.</p>
                        </div>
                    </div>
                </div>
            </section>

            <section class="space-y-4 rounded-[1.5rem] border border-slate-200 bg-white p-4">
                <div class="rounded-[1.25rem] bg-[linear-gradient(135deg,_#fff7f7_0%,_#fff1f1_100%)] p-4 ring-1 ring-rose-100">
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-rose-600">Detail Produk</p>
                    <p class="mt-2 text-sm leading-6 text-slate-600">Produk berikut akan dihapus dari katalog jika kamu menekan tombol lanjut.</p>
                </div>

                <dl class="space-y-3 text-sm text-slate-600">
                    <div class="flex items-start justify-between gap-4">
                        <dt class="font-medium text-slate-500">Nama Produk</dt>
                        <dd id="hapusDetailNama" class="text-right font-semibold text-slate-900">-</dd>
                    </div>
                    <div class="flex items-start justify-between gap-4">
                        <dt class="font-medium text-slate-500">Harga</dt>
                        <dd id="hapusDetailHarga" class="text-right font-semibold text-slate-900">-</dd>
                    </div>
                    <div class="flex items-start justify-between gap-4">
                        <dt class="font-medium text-slate-500">Stok</dt>
                        <dd id="hapusDetailStok" class="text-right font-semibold text-slate-900">-</dd>
                    </div>
                    <div class="space-y-2 rounded-2xl bg-slate-50 p-3">
                        <dt class="font-medium text-slate-500">Deskripsi</dt>
                        <dd id="hapusDetailDeskripsi" class="leading-6 text-slate-700">-</dd>
                    </div>
                </dl>

                <div class="flex flex-col gap-3 border-t border-slate-200 pt-4 sm:flex-row sm:justify-end">
                    <button type="button" id="cancelHapusProductModal" class="rounded-2xl bg-slate-100 px-4 py-3 text-sm font-semibold text-slate-700 transition duration-300 hover:bg-slate-200 hover:cursor-pointer">Batal</button>
                    <button type="button" id="triggerConfirmDeleteProductModal" class="rounded-2xl bg-rose-600 px-4 py-3 text-sm font-semibold text-white transition duration-300 hover:-translate-y-0.5 hover:bg-rose-700 hover:cursor-pointer">Hapus</button>
                </div>
            </section>
        </div>
    </div>
</div>

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
