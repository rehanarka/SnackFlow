<?php

namespace App\Http\Controllers;

use App\Services\MidtransService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class MidtransController extends Controller
{
    public function notification(Request $request, MidtransService $midtransService): JsonResponse
    {
        Log::info('Midtrans notification endpoint hit', [
            'payload' => $request->all(),
            'raw' => $request->getContent(),
        ]);

        try {
            $transaksi = $midtransService->handleNotification();

            if (!$transaksi) {
                return response()->json([
                    'message' => 'Notification diterima, tetapi transaksi lokal tidak ditemukan.',
                ], 200);
            }

            return response()->json([
                'message' => 'Notifikasi Midtrans berhasil diproses.',
                'transaction_id' => $transaksi->id,
                'status_pembayaran' => $transaksi->status_pembayaran,
            ]);
        } catch (Throwable $th) {
            Log::error('Midtrans notification processing failed', [
                'message' => $th->getMessage(),
                'payload' => $request->all(),
                'raw' => $request->getContent(),
            ]);

            return response()->json([
                'message' => 'Notification diterima tetapi tidak berhasil diproses.',
            ], 200);
        }
    }
}
