@php
    $isUpdateValidationError = old('produk_id_edit') !== null;
    $updateErrorMessage = $isUpdateValidationError && $errors->any() ? $errors->first() : null;
@endphp

<div id="updateProductModal" class="fixed inset-0 z-[62] hidden items-center justify-center px-4 py-5">
    <div id="updateModalOverlay" class="absolute inset-0 bg-slate-950/45 opacity-0 transition-opacity duration-300 ease-out"></div>

    <div id="updateProductModalContent" class="relative flex max-h-[90vh] w-full max-w-4xl flex-col overflow-hidden rounded-[2rem] bg-white opacity-0 scale-95 shadow-2xl transition-all duration-300 ease-out">
        <div class="flex items-start justify-between gap-4 border-b border-slate-200 px-5 py-4 sm:px-6">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-sky-600">Edit Produk</p>
                <h2 class="mt-2 text-xl font-bold text-slate-900 sm:text-2xl">Perbarui Detail Katalog Produk</h2>
                <p class="mt-2 text-sm leading-6 text-slate-600">Periksa detail produk yang dipilih, lakukan perubahan seperlunya, lalu konfirmasi sebelum data disimpan.</p>
            </div>
            <button type="button" id="closeUpdateProductModal" class="rounded-full bg-slate-100 px-3 py-2 text-sm font-semibold text-slate-600 transition duration-300 hover:bg-slate-200 hover:cursor-pointer">&times;</button>
        </div>

        <form id="updateProductForm" action="" method="POST" enctype="multipart/form-data" autocomplete="off" class="grid flex-1 gap-4 overflow-y-auto px-5 py-5 lg:grid-cols-[0.84fr_1.16fr] lg:px-6">
            @csrf
            @method('PUT')
            <input type="hidden" id="update_produk_id_edit" name="produk_id_edit" value="{{ old('produk_id_edit') }}">

            <section class="space-y-4 rounded-[1.5rem] border border-slate-200 bg-slate-50 p-4 lg:sticky lg:top-0 lg:self-start">
                <div class="overflow-hidden rounded-[1.5rem] bg-white shadow-sm ring-1 ring-slate-200">
                    <div class="flex aspect-[4/3] max-h-64 items-center justify-center bg-slate-100">
                        <img
                            id="updateProductPreviewImage"
                            src="{{ old('foto_produk') ? '' : '' }}"
                            alt="Preview produk"
                            class="hidden h-full w-full object-cover"
                        >
                        <div id="updateProductPreviewPlaceholder" class="px-6 text-center">
                            <p class="text-sm font-semibold text-slate-700">Foto Produk</p>
                            <p class="mt-1 text-xs leading-5 text-slate-500">Preview foto akan tampil di sini untuk membantu memastikan produk yang sedang diedit.</p>
                        </div>
                    </div>
                </div>

                <div class="rounded-[1.25rem] bg-white p-4 ring-1 ring-slate-200">
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Detail Produk yang Ingin Diedit</p>
                    <dl class="mt-4 space-y-3 text-sm text-slate-600">
                        <div class="flex items-start justify-between gap-4">
                            <dt class="font-medium text-slate-500">Nama Produk</dt>
                            <dd id="updateDetailNama" class="text-right font-semibold text-slate-900">-</dd>
                        </div>
                        <div class="flex items-start justify-between gap-4">
                            <dt class="font-medium text-slate-500">Harga</dt>
                            <dd id="updateDetailHarga" class="text-right font-semibold text-slate-900">-</dd>
                        </div>
                        <div class="flex items-start justify-between gap-4">
                            <dt class="font-medium text-slate-500">Stok</dt>
                            <dd id="updateDetailStok" class="text-right font-semibold text-slate-900">-</dd>
                        </div>
                        <div class="flex items-start justify-between gap-4">
                            <dt class="font-medium text-slate-500">Berat</dt>
                            <dd id="updateDetailBerat" class="text-right font-semibold text-slate-900">-</dd>
                        </div>
                        <div class="space-y-2 rounded-2xl bg-slate-50 p-3">
                            <dt class="font-medium text-slate-500">Deskripsi</dt>
                            <dd id="updateDetailDeskripsi" class="leading-6 text-slate-700">-</dd>
                        </div>
                    </dl>
                </div>
            </section>

            <section class="space-y-4 rounded-[1.5rem] border border-slate-200 bg-white p-4">
                <div class="rounded-[1.25rem] bg-[linear-gradient(135deg,_#f8fbff_0%,_#eef6ff_100%)] p-4 ring-1 ring-sky-100">
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-sky-600">Form Edit Data Katalog Produk</p>
                    <p class="mt-2 text-sm leading-6 text-slate-600">Ubah field yang diperlukan. Jika data tidak sesuai, sistem akan menampilkan pesan validasi lalu mengembalikan form ini.</p>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div class="sm:col-span-2">
                        <label for="update_nama_produk" class="mb-1 block text-sm font-medium text-slate-700">Nama Produk</label>
                        <input type="text" id="update_nama_produk" name="nama_produk" value="{{ old('nama_produk') }}" autocomplete="off" class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-sky-400 focus:ring-4 focus:ring-sky-100" placeholder="Masukkan nama produk" required>
                    </div>

                    <div>
                        <label for="update_harga" class="mb-1 block text-sm font-medium text-slate-700">Harga</label>
                        <input type="number" id="update_harga" name="harga" value="{{ old('harga') }}" autocomplete="off" class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-sky-400 focus:ring-4 focus:ring-sky-100" placeholder="Masukkan harga" required>
                    </div>

                    <div>
                        <label for="update_stok" class="mb-1 block text-sm font-medium text-slate-700">Stok</label>
                        <input type="number" id="update_stok" name="stok" value="{{ old('stok') }}" autocomplete="off" class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-sky-400 focus:ring-4 focus:ring-sky-100" placeholder="Masukkan stok" required>
                    </div>

                    <div>
                        <label for="update_berat" class="mb-1 block text-sm font-medium text-slate-700">Berat (gram)</label>
                        <input type="number" id="update_berat" name="berat" value="{{ old('berat') }}" autocomplete="off" class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-sky-400 focus:ring-4 focus:ring-sky-100" placeholder="Masukkan berat produk" required>
                    </div>

                    <div>
                        <label for="update_foto_produk" class="mb-1 block text-sm font-medium text-slate-700">Foto Produk</label>
                        <input type="file" id="update_foto_produk" name="foto_produk" accept="image/*" autocomplete="off" class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm text-slate-700 outline-none file:mr-3 file:rounded-full file:border-0 file:bg-sky-100 file:px-3 file:py-1.5 file:text-sky-700 file:text-xs file:font-semibold">
                    </div>

                    <div class="sm:col-span-2">
                        <label for="update_deskripsi" class="mb-1 block text-sm font-medium text-slate-700">Deskripsi</label>
                        <textarea id="update_deskripsi" name="deskripsi" rows="4" autocomplete="off" class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-sky-400 focus:ring-4 focus:ring-sky-100" placeholder="Masukkan deskripsi produk">{{ old('deskripsi') }}</textarea>
                    </div>
                </div>

                <div class="flex flex-col gap-3 border-t border-slate-200 pt-4 sm:flex-row sm:justify-end">
                    <button type="button" id="cancelUpdateProductModal" class="rounded-2xl bg-slate-100 px-4 py-3 text-sm font-semibold text-slate-700 transition duration-300 hover:bg-slate-200 hover:cursor-pointer">Batal</button>
                    <button type="button" id="triggerConfirmUpdateProductModal" class="rounded-2xl bg-sky-600 px-4 py-3 text-sm font-semibold text-white transition duration-300 hover:-translate-y-0.5 hover:bg-sky-700 hover:cursor-pointer">Simpan Perubahan</button>
                </div>
            </section>
        </form>
    </div>
</div>

<x-modal.confirm
    modal-id="confirmUpdateProductModal"
    overlay-id="confirmUpdateProductOverlay"
    panel-id="confirmUpdateProductPanel"
    title="Konfirmasi Perubahan Produk"
    message="Sudah yakin dengan perubahan yang dilakukan?"
    close-button-id="closeConfirmUpdateProductModal"
    cancel-button-id="cancelConfirmUpdateProductModal"
    cancel-label="Belum Yakin"
    submit-label="Lanjut"
    form-action="#"
    submit-class="bg-sky-600 text-white hover:bg-sky-700"
/>

<x-modal.alert
    modal-id="updateProductFeedbackModal"
    overlay-id="updateProductFeedbackOverlay"
    panel-id="updateProductFeedbackPanel"
    title="Informasi"
    message=""
    message-id="updateProductFeedbackMessage"
    close-button-id="closeUpdateProductFeedbackModal"
    action-button-id="ackUpdateProductFeedbackModal"
    action-label="Oke"
    action-class="bg-slate-900 text-white hover:bg-slate-800"
    z-index="z-[64]"
/>

<div
    id="updateProductModalState"
    data-success-message="{{ session('update_success') }}"
    data-error-message="{{ $updateErrorMessage }}"
    data-has-errors="{{ $isUpdateValidationError && $errors->any() ? 'true' : 'false' }}"
    data-edit-id="{{ old('produk_id_edit') }}"
    data-old-nama="{{ old('nama_produk') }}"
    data-old-harga="{{ old('harga') }}"
    data-old-stok="{{ old('stok') }}"
    data-old-berat="{{ old('berat') }}"
    data-old-deskripsi="{{ old('deskripsi') }}"
></div>
