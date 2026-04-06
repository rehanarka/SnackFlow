<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\MapsService;

class MapsController extends Controller
{
    protected $mapsService;

    public function __construct(MapsService $mapsService)
    {
        $this->mapsService = $mapsService;
    }

    // 🔥 PASTIKAN ADA INI
    public function searchArea(Request $request)
    {
        $request->validate([
            'q' => 'required|string|min:3',
        ]);

        $data = $this->mapsService->getAreas($request->q);

        return response()->json($data);
    }
}