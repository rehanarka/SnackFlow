function initCheckoutPage() {
    const configElement = document.getElementById('checkoutPageData');

    if (!configElement) {
        return;
    }

    let config = {};

    try {
        config = JSON.parse(configElement.textContent || '{}');
    } catch (error) {
        return;
    }

    const subtotalValue = Number(config.subtotal ?? 0);
    const endpoint = config.autocompleteEndpoint;
    const ratesEndpoint = config.ratesEndpoint;
    const shippingEndpoint = config.shippingEndpoint;
    const csrfToken = config.csrfToken;
    const initialRates = Array.isArray(config.initialRates) ? config.initialRates : [];

    const input = document.getElementById('tujuan_pengiriman');
    const list = document.getElementById('destinationAutocompleteList');
    const helpText = document.getElementById('destinationAutocompleteHelp');
    const destinationIdInput = document.getElementById('selected_destination_id');
    const destinationLabelInput = document.getElementById('selected_destination_label');
    const destinationPostalCodeInput = document.getElementById('selected_destination_postal_code');
    const destinationProvinceInput = document.getElementById('selected_destination_province');
    const destinationCityInput = document.getElementById('selected_destination_city');
    const destinationDistrictInput = document.getElementById('selected_destination_district');
    const destinationSubdistrictInput = document.getElementById('selected_destination_subdistrict');
    const selectedDestinationCard = document.getElementById('selectedDestinationCard');
    const selectedDestinationLabel = document.getElementById('selectedDestinationLabel');
    const selectedDestinationPostalCode = document.getElementById('selectedDestinationPostalCode');
    const ratesForm = document.getElementById('ratesForm');
    const checkOngkirButton = document.getElementById('checkOngkirButton');
    const checkoutAjaxAlert = document.getElementById('checkoutAjaxAlert');
    const checkoutErrorAlert = document.getElementById('checkoutErrorAlert');
    const shippingEmptyState = document.getElementById('shippingEmptyState');
    const shippingEmptyStateText = document.getElementById('shippingEmptyStateText');
    const shippingOptionsSection = document.getElementById('shippingOptionsSection');
    const shippingRatesGrid = document.getElementById('shippingRatesGrid');
    const shippingCostSummary = document.getElementById('shippingCostSummary');
    const estimatedTotalSummary = document.getElementById('estimatedTotalSummary');
    const selectedShippingCard = document.getElementById('selectedShippingCard');
    const checkoutProceedForm = document.getElementById('checkoutProceedForm');
    const shippingModal = document.getElementById('shippingOptionsModal');
    const shippingOverlay = document.getElementById('shippingOptionsOverlay');
    const shippingPanel = document.getElementById('shippingOptionsPanel');
    const openShippingModalButton = document.getElementById('openShippingOptionsModal');
    const closeShippingModalButton = document.getElementById('closeShippingOptionsModal');

    if (
        !input ||
        !list ||
        !helpText ||
        !destinationIdInput ||
        !destinationLabelInput ||
        !destinationPostalCodeInput ||
        !destinationProvinceInput ||
        !destinationCityInput ||
        !destinationDistrictInput ||
        !destinationSubdistrictInput ||
        !ratesForm ||
        !checkOngkirButton
    ) {
        return;
    }

    let debounceTimer = null;
    let activeRequest = 0;

    const hideList = () => {
        list.classList.add('hidden');
        list.innerHTML = '';
    };

    const formatRupiah = (value) =>
        `Rp ${new Intl.NumberFormat('id-ID').format(Number(value || 0))}`;

    const showAlert = (message, type = 'success') => {
        if (!checkoutAjaxAlert) {
            return;
        }

        checkoutAjaxAlert.className = `mb-6 rounded-3xl px-5 py-4 text-sm font-medium ${
            type === 'error'
                ? 'border border-red-200 bg-red-50 text-red-700'
                : 'border border-emerald-200 bg-emerald-50 text-emerald-700'
        }`;
        checkoutAjaxAlert.textContent = message;
        checkoutAjaxAlert.classList.remove('hidden');

        if (checkoutErrorAlert) {
            checkoutErrorAlert.classList.add('hidden');
        }
    };

    const hideAlert = () => {
        if (!checkoutAjaxAlert) {
            return;
        }

        checkoutAjaxAlert.classList.add('hidden');
        checkoutAjaxAlert.textContent = '';
    };

    const setSelectedDestination = (destination) => {
        input.value = destination.label;
        destinationIdInput.value = destination.id;
        destinationLabelInput.value = destination.label;
        destinationPostalCodeInput.value = destination.postal_code || '';
        destinationProvinceInput.value = destination.province_name || '';
        destinationCityInput.value = destination.city_name || '';
        destinationDistrictInput.value = destination.district_name || '';
        destinationSubdistrictInput.value = destination.subdistrict_name || '';
        helpText.textContent = destination.postal_code
            ? `Tujuan dipilih: ${destination.label} (${destination.postal_code})`
            : `Tujuan dipilih: ${destination.label}`;

        if (selectedDestinationCard && selectedDestinationLabel && selectedDestinationPostalCode) {
            selectedDestinationCard.classList.remove('hidden');
            selectedDestinationLabel.textContent = destination.label;

            if (destination.postal_code) {
                selectedDestinationPostalCode.textContent = `Kode Pos ${destination.postal_code}`;
                selectedDestinationPostalCode.classList.remove('hidden');
            } else {
                selectedDestinationPostalCode.textContent = '';
                selectedDestinationPostalCode.classList.add('hidden');
            }
        }

        hideList();
    };

    const renderRateCard = (rate) => `
        <div class="rounded-[1.75rem] border border-slate-200 bg-gradient-to-br from-white to-slate-50 px-5 py-5 shadow-sm shadow-slate-100/80 transition duration-300 hover:-translate-y-0.5 hover:border-sky-200 hover:shadow-sky-100/80">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <div class="flex flex-wrap items-center gap-2">
                        <p class="text-base font-semibold text-slate-900">${rate.name ?? rate.courier ?? 'Kurir'}</p>
                        <span class="rounded-full bg-slate-900 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.16em] text-white">${rate.service ?? ''}</span>
                    </div>
                    <p class="mt-2 text-sm text-slate-500">${rate.description ?? rate.service_name ?? 'Layanan'}</p>
                </div>
                <div class="text-right">
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Ongkir</p>
                    <p class="mt-1 text-2xl font-bold text-slate-900">${formatRupiah(rate.cost ?? rate.shipping_cost ?? 0)}</p>
                </div>
            </div>

            <div class="mt-4 grid gap-3 sm:grid-cols-2">
                <div class="rounded-2xl bg-slate-100 px-4 py-3">
                    <p class="text-[11px] font-semibold uppercase tracking-[0.16em] text-slate-500">Estimasi</p>
                    <p class="mt-1 text-sm font-semibold text-slate-900">${String(rate.etd ?? '-').toUpperCase()}</p>
                </div>
                <div class="rounded-2xl bg-sky-50 px-4 py-3">
                    <p class="text-[11px] font-semibold uppercase tracking-[0.16em] text-sky-600">Kode Kurir</p>
                    <p class="mt-1 text-sm font-semibold text-sky-900">${String(rate.code ?? '-').toUpperCase()}</p>
                </div>
            </div>

            <form action="${shippingEndpoint}" method="POST" class="mt-4">
                <input type="hidden" name="_token" value="${csrfToken}">
                <input type="hidden" name="ongkir" value="${rate.cost ?? rate.shipping_cost ?? 0}">
                <input type="hidden" name="kurir" value="${rate.name ?? rate.courier ?? 'Kurir'}">
                <input type="hidden" name="service_pengiriman" value="${rate.service ?? rate.service_name ?? 'Layanan'}">
                <input type="hidden" name="estimasi_pengiriman" value="${rate.etd ?? '-'}">
                <button type="submit" class="w-full rounded-2xl bg-slate-900 px-4 py-3 text-sm font-semibold text-white transition duration-300 hover:-translate-y-0.5 hover:bg-slate-800">Pilih Layanan Ini</button>
            </form>
        </div>
    `;

    const renderRates = (rates) => {
        if (!shippingRatesGrid || !shippingOptionsSection) {
            return;
        }

        shippingRatesGrid.innerHTML = rates.map(renderRateCard).join('');
        shippingOptionsSection.classList.remove('hidden');
    };

    const renderItems = (items) => {
        if (!items.length) {
            list.innerHTML =
                '<li class="px-4 py-3 text-sm text-slate-500">Tujuan tidak ditemukan.</li>';
            list.classList.remove('hidden');
            return;
        }

        list.innerHTML = items
            .map((item) => {
                const postal = item.postal_code
                    ? `<span class="mt-1 block text-xs font-medium uppercase tracking-[0.14em] text-slate-500">Kode Pos ${item.postal_code}</span>`
                    : '';

                return `
                    <li>
                        <button
                            type="button"
                            class="w-full rounded-2xl px-4 py-3 text-left transition hover:bg-sky-50"
                            data-destination-id="${item.id}"
                            data-destination-label="${item.label.replace(/"/g, '&quot;')}"
                            data-destination-postal="${(item.postal_code || '').replace(/"/g, '&quot;')}"
                            data-destination-province="${(item.province_name || '').replace(/"/g, '&quot;')}"
                            data-destination-city="${(item.city_name || '').replace(/"/g, '&quot;')}"
                            data-destination-district="${(item.district_name || '').replace(/"/g, '&quot;')}"
                            data-destination-subdistrict="${(item.subdistrict_name || '').replace(/"/g, '&quot;')}"
                        >
                            <span class="block text-sm font-semibold text-slate-900">${item.label}</span>
                            ${postal}
                        </button>
                    </li>
                `;
            })
            .join('');

        list.classList.remove('hidden');

        list.querySelectorAll('button[data-destination-id]').forEach((button) => {
            button.addEventListener('click', () => {
                setSelectedDestination({
                    id: button.dataset.destinationId,
                    label: button.dataset.destinationLabel,
                    postal_code: button.dataset.destinationPostal,
                    province_name: button.dataset.destinationProvince,
                    city_name: button.dataset.destinationCity,
                    district_name: button.dataset.destinationDistrict,
                    subdistrict_name: button.dataset.destinationSubdistrict,
                });
            });
        });
    };

    const openShippingModal = () => {
        if (!shippingModal || !shippingOverlay || !shippingPanel) {
            return;
        }

        shippingModal.classList.remove('hidden');

        requestAnimationFrame(() => {
            shippingOverlay.classList.remove('opacity-0');
            shippingPanel.classList.remove('translate-y-10');
            shippingPanel.classList.add('sm:-translate-y-1/2');
        });
    };

    const closeShippingModal = () => {
        if (!shippingModal || !shippingOverlay || !shippingPanel) {
            return;
        }

        shippingOverlay.classList.add('opacity-0');
        shippingPanel.classList.add('translate-y-10');
        shippingPanel.classList.remove('sm:-translate-y-1/2');

        window.setTimeout(() => {
            shippingModal.classList.add('hidden');
        }, 250);
    };

    input.addEventListener('input', () => {
        window.clearTimeout(debounceTimer);

        const query = input.value.trim();
        destinationIdInput.value = '';
        destinationLabelInput.value = '';
        destinationPostalCodeInput.value = '';
        destinationProvinceInput.value = '';
        destinationCityInput.value = '';
        destinationDistrictInput.value = '';
        destinationSubdistrictInput.value = '';

        if (selectedDestinationCard) {
            selectedDestinationCard.classList.add('hidden');
        }

        if (query.length < 3) {
            helpText.textContent =
                'Ketik nama kecamatan atau kota, lalu pilih salah satu hasil yang muncul.';
            hideList();
            return;
        }

        helpText.textContent = 'Mencari tujuan pengiriman...';

        debounceTimer = window.setTimeout(async () => {
            activeRequest += 1;
            const currentRequest = activeRequest;

            try {
                const response = await fetch(`${endpoint}?q=${encodeURIComponent(query)}`, {
                    headers: {
                        Accept: 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                });

                const payload = await response.json();

                if (currentRequest !== activeRequest) {
                    return;
                }

                if (!response.ok) {
                    helpText.textContent =
                        payload.message || 'Gagal mencari tujuan pengiriman.';
                    hideList();
                    return;
                }

                helpText.textContent = payload.data.length
                    ? 'Pilih salah satu tujuan yang paling sesuai.'
                    : 'Tujuan tidak ditemukan.';

                renderItems(payload.data || []);
            } catch (error) {
                if (currentRequest !== activeRequest) {
                    return;
                }

                helpText.textContent = 'Gagal mengambil data tujuan pengiriman.';
                hideList();
            }
        }, 500);
    });

    document.addEventListener('click', (event) => {
        if (!list.contains(event.target) && event.target !== input) {
            hideList();
        }
    });

    input.addEventListener('focus', () => {
        if (list.children.length) {
            list.classList.remove('hidden');
        }
    });

    ratesForm.addEventListener('submit', async (event) => {
        event.preventDefault();
        hideAlert();

        checkOngkirButton.disabled = true;
        checkOngkirButton.classList.add('cursor-not-allowed', 'opacity-70');
        checkOngkirButton.textContent = 'Memuat Ongkir...';

        try {
            const response = await fetch(ratesEndpoint, {
                method: 'POST',
                headers: {
                    Accept: 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: new FormData(ratesForm),
            });

            const payload = await response.json();

            if (!response.ok) {
                showAlert(payload.message || 'Gagal memuat ongkir.', 'error');

                if (shippingOptionsSection) {
                    shippingOptionsSection.classList.add('hidden');
                }

                if (shippingEmptyState && shippingEmptyStateText) {
                    shippingEmptyState.classList.remove('hidden');
                    shippingEmptyStateText.textContent =
                        payload.message || 'Gagal memuat ongkir.';
                }

                if (shippingCostSummary) {
                    shippingCostSummary.textContent = 'Belum dipilih';
                }

                if (estimatedTotalSummary) {
                    estimatedTotalSummary.textContent = formatRupiah(subtotalValue);
                }

                return;
            }

            renderRates(payload.rates || []);
            showAlert(payload.message || 'Opsi pengiriman berhasil dimuat.');

            if (shippingEmptyState) {
                shippingEmptyState.classList.add('hidden');
            }

            if (selectedShippingCard) {
                selectedShippingCard.classList.add('hidden');
            }

            if (checkoutProceedForm) {
                checkoutProceedForm.classList.add('hidden');
            }

            if (shippingCostSummary) {
                shippingCostSummary.textContent = 'Belum dipilih';
            }

            if (estimatedTotalSummary) {
                estimatedTotalSummary.textContent = formatRupiah(subtotalValue);
            }
        } catch (error) {
            showAlert('Gagal terhubung ke server saat memuat ongkir.', 'error');
        } finally {
            checkOngkirButton.disabled = false;
            checkOngkirButton.classList.remove('cursor-not-allowed', 'opacity-70');
            checkOngkirButton.textContent = 'Cek Ongkir';
        }
    });

    if (Array.isArray(initialRates) && initialRates.length) {
        renderRates(initialRates);
    }

    if (shippingModal && shippingOverlay && shippingPanel && openShippingModalButton && closeShippingModalButton) {
        openShippingModalButton.addEventListener('click', openShippingModal);
        closeShippingModalButton.addEventListener('click', closeShippingModal);
        shippingOverlay.addEventListener('click', closeShippingModal);

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && !shippingModal.classList.contains('hidden')) {
                closeShippingModal();
            }
        });
    }
}

initCheckoutPage();
