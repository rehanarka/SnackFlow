<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class MapsService
{
    public function getAreas($input)
    {
        $baseUrl = env('SHIPPER_BASE_URL');
        $apiKey = env('SHIPPER_API_KEY');

        $response = Http::withHeaders([
            'Authorization' => "Bearer $apiKey",
            'Accept' => 'application/json',
        ])->get("$baseUrl/v1/maps/areas", [
            'countries' => 'ID',
            'input' => $input,
            'type' => 'single',
        ]);

        return $response->json();
    }
}