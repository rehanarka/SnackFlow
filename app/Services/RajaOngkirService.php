<?php

namespace App\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class RajaOngkirService
{
    public function searchDomesticDestinations(string $search, int $limit = 8): array
    {
        $response = $this->baseRequest()->get('/destination/domestic-destination', [
            'search' => $search,
            'limit' => $limit,
            'offset' => 0,
        ]);

        if ($response->failed()) {
            throw new RuntimeException($response->json('meta.message') ?: 'Gagal mencari tujuan pengiriman.');
        }

        return $response->json('data') ?? [];
    }

    public function getConfiguredOrigin(): array
    {
        $originSearch = config('services.rajaongkir.origin_search');

        if (!$originSearch) {
            throw new RuntimeException('RAJAONGKIR_ORIGIN_SEARCH belum diatur di file .env.');
        }

        $origins = $this->searchDomesticDestinations($originSearch, 1);

        if (empty($origins[0])) {
            throw new RuntimeException('Lokasi origin toko tidak ditemukan di RajaOngkir.');
        }

        return $origins[0];
    }

    public function calculateDomesticCost(int $destinationId, Collection $keranjangItems): array
    {
        $origin = $this->getConfiguredOrigin();
        $weight = max(1, (int) $keranjangItems->sum(fn ($item) => ($item->produk->berat ?? 0) * $item->jumlah_produk));
        $courier = config('services.rajaongkir.couriers', 'jne:sicepat:jnt:gojek');

        $response = $this->baseRequest()
            ->asForm()
            ->post('/calculate/domestic-cost', [
                'origin' => $origin['id'],
                'destination' => $destinationId,
                'weight' => $weight,
                'courier' => $courier,
                'price' => 'lowest',
            ]);

        if ($response->failed()) {
            throw new RuntimeException($response->json('meta.message') ?: 'Gagal menghitung ongkir dari RajaOngkir.');
        }

        $data = $response->json('data') ?? [];
        $flattened = collect([
            $data['calculate_reguler'] ?? [],
            $data['calculate_cargo'] ?? [],
            $data['calculate_instant'] ?? [],
            is_array($data) ? $data : [],
        ])->flatten(1)->filter(fn ($item) => is_array($item) && !empty($item))->values()->all();

        Log::info('RajaOngkir calculate response', [
            'origin_id' => $origin['id'] ?? null,
            'destination_id' => $destinationId,
            'weight' => $weight,
            'courier' => $courier,
            'raw_data' => $data,
            'flattened_count' => count($flattened),
        ]);

        return $flattened;
    }

    protected function baseRequest()
    {
        $apiKey = config('services.rajaongkir.api_key');
        $baseUrl = rtrim(config('services.rajaongkir.base_url', 'https://rajaongkir.komerce.id/api/v1'), '/');

        if (!$apiKey) {
            throw new RuntimeException('RAJAONGKIR_API_KEY belum diatur di file .env.');
        }

        return Http::baseUrl($baseUrl)
            ->acceptJson()
            ->withHeaders([
                'key' => $apiKey,
            ])
            ->timeout(30);
    }
}
