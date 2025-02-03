<?php

use App\Http\Controllers\Api\BillingController;
use App\Http\Controllers\Api\HistoryBankController;
use App\Models\TahunPembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/billing-mahasiswa', [BillingController::class, 'store_billing_mahasiswa']);
Route::post('/history-bank', [HistoryBankController::class, 'auto_lunas']);
Route::post('/billing-mahasiswa/detail', [BillingController::class, 'get_detail_ukt'])->withoutMiddleware(['throttle:api']);
Route::patch('/billing-mahasiswa/update', [BillingController::class, 'update_billing_ukt'])->withoutMiddleware(['throttle:api']);

Route::get('/tahun-pembayaran', function (Request $request) {
    $apiKey = $request->header('X-API-KEY');

    if (!$apiKey || $apiKey !== 'secret') {
        return response()->json([
            'response' => false,
            'message' => 'Unauthorized: Invalid or missing API key.',
        ], 401);
    }
    $tahun_pembayaran = TahunPembayaran::first();
    if ($tahun_pembayaran) {
        return response()->json([
            'response' => true,
            'data' => [
                'tahun_akademik' => $tahun_pembayaran->tahun_akademik,
                'tgl_mulai' => $tahun_pembayaran->awal_pembayaran,
                'tgl_selesai' => $tahun_pembayaran->akhir_pembayaran
            ],
        ]);
    } else {
        return response()->json([
            'response' => false,
            'message' => 'Tahun Pembayaran Tidak Ditemukan',
        ], 404);
    }
});