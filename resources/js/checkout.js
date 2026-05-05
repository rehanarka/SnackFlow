function initCheckoutPage() {
    const configElement = document.getElementById('checkoutPageData');

    if (!configElement) {
        return;
    }

    let config = {};

    try {
        config = JSON.parse(configElement.textContent || '{}');
    } catch {
        return;
    }

    const subtotalValue = Number(config.subtotal ?? 0);
    const endpoint = config.autocompleteEndpoint;
    const ratesEndpoint = config.ratesEndpoint;
    const csrfToken = config.csrfToken;

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
    const checkoutAjaxAlert = document.getElementById('checkoutAjaxAlert');
    const checkoutErrorAlert = document.getElementById('checkoutErrorAlert');
    const shippingEmptyState = document.getElementById('shippingEmptyState');
    const shippingEmptyStateText = document.getElementById('shippingEmptyStateText');
    const shippingCostSummary = document.getElementById('shippingCostSummary');
    const estimatedTotalSummary = document.getElementById('estimatedTotalSummary');
    const selectedShippingCard = document.getElementById('selectedShippingCard');
    const selectedShippingCourier = document.getElementById('selectedShippingCourier');
    const selectedShippingService = document.getElementById('selectedShippingService');
    const selectedShippingEstimate = document.getElementById('selectedShippingEstimate');
    const selectedShippingCost = document.getElementById('selectedShippingCost');
    const checkoutProceedForm = document.getElementById('checkoutProceedForm');
    const namaPenerima = document.getElementById('nama_penerima');
    const noTelpPenerima = document.getElementById('no_telp_penerima');
    const alamatPenerima = document.getElementById('alamat_penerima');

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
        !namaPenerima ||
        !noTelpPenerima ||
        !alamatPenerima
    ) {
        return;
    }

    let autocompleteTimer = null;
    let shippingTimer = null;
    let activeAutocompleteRequest = 0;
    let activeShippingRequest = 0;
    let lastShippingSignature = '';

    const formatRupiah = (value) =>
        `Rp ${new Intl.NumberFormat('id-ID').format(Number(value || 0))}`;

    const hideList = () => {
        list.classList.add('hidden');
        list.innerHTML = '';
    };

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

    const resetShippingState = (message) => {
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

        if (shippingEmptyState && shippingEmptyStateText) {
            shippingEmptyState.classList.remove('hidden');
            shippingEmptyStateText.textContent = message;
        }
    };

    const applyShippingState = (shipping, estimatedTotal) => {
        if (!shipping) {
            return;
        }

        if (selectedShippingCard) {
            selectedShippingCard.classList.remove('hidden');
        }

        if (selectedShippingCourier) {
            selectedShippingCourier.textContent = shipping.kurir || 'JNE';
        }

        if (selectedShippingService) {
            selectedShippingService.textContent = shipping.service_pengiriman || 'REG';
        }

        if (selectedShippingEstimate) {
            selectedShippingEstimate.textContent = shipping.estimasi_pengiriman
                ? `Estimasi ${shipping.estimasi_pengiriman}`
                : '';
        }

        if (selectedShippingCost) {
            selectedShippingCost.textContent = formatRupiah(shipping.ongkir || 0);
        }

        if (shippingCostSummary) {
            shippingCostSummary.textContent = formatRupiah(shipping.ongkir || 0);
        }

        if (estimatedTotalSummary) {
            estimatedTotalSummary.textContent = formatRupiah(estimatedTotal ?? subtotalValue);
        }

        if (shippingEmptyState) {
            shippingEmptyState.classList.add('hidden');
        }

        if (checkoutProceedForm) {
            checkoutProceedForm.classList.remove('hidden');
        }
    };

    const buildShippingSignature = () => JSON.stringify({
        nama_penerima: namaPenerima.value.trim(),
        no_telp_penerima: noTelpPenerima.value.trim(),
        alamat_penerima: alamatPenerima.value.trim(),
        selected_destination_id: destinationIdInput.value.trim(),
        selected_destination_label: destinationLabelInput.value.trim(),
        selected_destination_postal_code: destinationPostalCodeInput.value.trim(),
        selected_destination_province: destinationProvinceInput.value.trim(),
        selected_destination_city: destinationCityInput.value.trim(),
        selected_destination_district: destinationDistrictInput.value.trim(),
        selected_destination_subdistrict: destinationSubdistrictInput.value.trim(),
    });

    const canAutoCalculateShipping = () =>
        Boolean(
            namaPenerima.value.trim() &&
            noTelpPenerima.value.trim() &&
            alamatPenerima.value.trim() &&
            destinationIdInput.value.trim() &&
            destinationLabelInput.value.trim()
        );

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
        triggerAutoShippingCalculation();
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

    const triggerAutoShippingCalculation = () => {
        window.clearTimeout(shippingTimer);

        if (!canAutoCalculateShipping()) {
            resetShippingState('Lengkapi nama penerima, no. telp, alamat, lalu pilih tujuan pengiriman agar ongkir JNE Reguler dipasang otomatis.');
            return;
        }

        const currentSignature = buildShippingSignature();

        if (currentSignature === lastShippingSignature) {
            return;
        }

        shippingTimer = window.setTimeout(async () => {
            activeShippingRequest += 1;
            const currentRequest = activeShippingRequest;

            if (shippingEmptyState && shippingEmptyStateText) {
                shippingEmptyState.classList.remove('hidden');
                shippingEmptyStateText.textContent = 'Menghitung ongkir JNE Reguler otomatis...';
            }

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

                if (currentRequest !== activeShippingRequest) {
                    return;
                }

                if (!response.ok) {
                    resetShippingState(payload.message || 'Gagal memasang ongkir otomatis.');
                    showAlert(payload.message || 'Gagal memasang ongkir otomatis.', 'error');
                    return;
                }

                lastShippingSignature = currentSignature;
                applyShippingState(payload.selected_shipping, payload.estimated_total);
                showAlert(payload.message || 'Ongkir JNE Reguler berhasil dipasang otomatis.');
            } catch {
                if (currentRequest !== activeShippingRequest) {
                    return;
                }

                resetShippingState('Gagal terhubung ke server saat menghitung ongkir otomatis.');
                showAlert('Gagal terhubung ke server saat menghitung ongkir otomatis.', 'error');
            }
        }, 450);
    };

    input.addEventListener('input', () => {
        window.clearTimeout(autocompleteTimer);

        const query = input.value.trim();
        destinationIdInput.value = '';
        destinationLabelInput.value = '';
        destinationPostalCodeInput.value = '';
        destinationProvinceInput.value = '';
        destinationCityInput.value = '';
        destinationDistrictInput.value = '';
        destinationSubdistrictInput.value = '';
        lastShippingSignature = '';

        if (selectedDestinationCard) {
            selectedDestinationCard.classList.add('hidden');
        }

        resetShippingState('Pilih tujuan pengiriman dari hasil autocomplete agar ongkir JNE Reguler dipasang otomatis.');

        if (query.length < 3) {
            helpText.textContent = 'Ketik nama kecamatan atau kota, lalu pilih salah satu hasil yang muncul.';
            hideList();
            return;
        }

        helpText.textContent = 'Mencari tujuan pengiriman...';

        autocompleteTimer = window.setTimeout(async () => {
            activeAutocompleteRequest += 1;
            const currentRequest = activeAutocompleteRequest;

            try {
                const response = await fetch(`${endpoint}?q=${encodeURIComponent(query)}`, {
                    headers: {
                        Accept: 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                });

                const payload = await response.json();

                if (currentRequest !== activeAutocompleteRequest) {
                    return;
                }

                if (!response.ok) {
                    helpText.textContent = payload.message || 'Gagal mencari tujuan pengiriman.';
                    hideList();
                    return;
                }

                helpText.textContent = payload.data.length
                    ? 'Pilih salah satu tujuan yang paling sesuai.'
                    : 'Tujuan tidak ditemukan.';

                renderItems(payload.data || []);
            } catch {
                if (currentRequest !== activeAutocompleteRequest) {
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

    [namaPenerima, noTelpPenerima, alamatPenerima].forEach((field) => {
        field.addEventListener('input', () => {
            lastShippingSignature = '';
            triggerAutoShippingCalculation();
        });
    });

    ratesForm.addEventListener('submit', (event) => {
        event.preventDefault();
        triggerAutoShippingCalculation();
    });

    if (config.hasSelectedShipping) {
        if (checkoutProceedForm) {
            checkoutProceedForm.classList.remove('hidden');
        }
    } else {
        resetShippingState('Lengkapi nama penerima, no. telp, alamat, lalu pilih tujuan pengiriman agar ongkir JNE Reguler dipasang otomatis.');
    }
}

initCheckoutPage();
