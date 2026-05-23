<div id="productDetailModal" class="fixed inset-0 z-[58] hidden items-center justify-center px-4 py-5">
    <div id="productDetailOverlay" class="absolute inset-0 bg-slate-950/45 opacity-0 transition-opacity duration-300 ease-out"></div>

    <div id="productDetailContent" class="relative flex max-h-[88vh] w-full max-w-4xl flex-col overflow-hidden rounded-[2rem] bg-white opacity-0 scale-95 shadow-2xl transition-all duration-300 ease-out">
        <div class="flex items-start justify-between gap-4 border-b border-slate-200 px-5 py-4 sm:px-6">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-sky-600">Detail Produk</p>
                <h2 class="mt-2 text-xl font-bold text-slate-900 sm:text-2xl">Informasi Katalog Produk</h2>
                <p class="mt-2 text-sm leading-6 text-slate-600">Periksa detail produk terlebih dahulu, lalu pilih aksi edit atau hapus.</p>
            </div>
            <button type="button" id="closeProductDetailModal" class="rounded-full bg-slate-100 px-3 py-2 text-sm font-semibold text-slate-600 transition duration-300 hover:bg-slate-200 hover:cursor-pointer">&times;</button>
        </div>

        <div class="grid flex-1 gap-4 overflow-y-auto px-5 py-5 md:grid-cols-[0.9fr_1.1fr] lg:px-6">
            <section class="space-y-4 rounded-[1.5rem] border border-slate-200 bg-slate-50 p-4">
                <div class="overflow-hidden rounded-[1.5rem] bg-white shadow-sm ring-1 ring-slate-200">
                    <div class="flex aspect-[4/3] max-h-72 items-center justify-center bg-slate-100">
                        <img id="productDetailPreviewImage" src="" alt="Preview produk" class="hidden h-full w-full object-cover">
                        <div id="productDetailPreviewPlaceholder" class="px-6 text-center">
                            <p class="text-sm font-semibold text-slate-700">Foto Produk</p>
                            <p class="mt-1 text-xs leading-5 text-slate-500">Preview foto produk akan tampil di sini.</p>
                        </div>
                    </div>
                </div>
            </section>

            <section class="space-y-4 rounded-[1.5rem] border border-slate-200 bg-white p-4">
                <div class="rounded-[1.25rem] bg-[linear-gradient(135deg,_#f8fbff_0%,_#eef6ff_100%)] p-4 ring-1 ring-sky-100">
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-sky-600">Detail Produk</p>
                    <p class="mt-2 text-sm leading-6 text-slate-600">Aksi edit dan hapus sekarang dipusatkan dari detail ini supaya alur admin lebih rapi.</p>
                </div>

                <dl class="space-y-3 text-sm text-slate-600">
                    <div class="flex items-start justify-between gap-4">
                        <dt class="font-medium text-slate-500">Nama Produk</dt>
                        <dd id="productDetailNama" class="text-right font-semibold text-slate-900">-</dd>
                    </div>
                    <div class="flex items-start justify-between gap-4">
                        <dt class="font-medium text-slate-500">Harga</dt>
                        <dd id="productDetailHarga" class="text-right font-semibold text-slate-900">-</dd>
                    </div>
                    <div class="flex items-start justify-between gap-4">
                        <dt class="font-medium text-slate-500">Stok</dt>
                        <dd id="productDetailStok" class="text-right font-semibold text-slate-900">-</dd>
                    </div>
                    <div class="flex items-start justify-between gap-4">
                        <dt class="font-medium text-slate-500">Berat</dt>
                        <dd id="productDetailBerat" class="text-right font-semibold text-slate-900">-</dd>
                    </div>
                    <div class="space-y-2 rounded-2xl bg-slate-50 p-3">
                        <dt class="font-medium text-slate-500">Deskripsi</dt>
                        <dd id="productDetailDeskripsi" class="leading-6 text-slate-700">-</dd>
                    </div>
                </dl>

                <div class="flex flex-col gap-3 border-t border-slate-200 pt-4 sm:flex-row sm:justify-end">
                    <a href="#" id="detailActionReviewProduct" class="inline-flex justify-center rounded-2xl bg-amber-500 px-4 py-3 text-sm font-semibold text-white transition duration-300 hover:-translate-y-0.5 hover:bg-amber-600 hover:cursor-pointer">Review</a>
                    <button type="button" id="detailActionEditProduct" class="rounded-2xl bg-sky-600 px-4 py-3 text-sm font-semibold text-white transition duration-300 hover:-translate-y-0.5 hover:bg-sky-700 hover:cursor-pointer">Edit</button>
                    <button type="button" id="detailActionDeleteProduct" class="rounded-2xl bg-rose-600 px-4 py-3 text-sm font-semibold text-white transition duration-300 hover:-translate-y-0.5 hover:bg-rose-700 hover:cursor-pointer">Hapus</button>
                </div>
            </section>
        </div>
    </div>
</div>
