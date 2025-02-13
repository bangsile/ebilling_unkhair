<?php

use App\Http\Controllers\Api\BillingPaymentController;
use App\Http\Controllers\Api\BillingMhsController;
use App\Http\Controllers\Api\HistoryBankController;
use App\Models\JenisBayar;
use App\Models\TahunPembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/history-bank', [HistoryBankController::class, 'auto_lunas']);
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

// E-Billing Mahasiswa
Route::post('/billing-mahasiswa', [BillingMhsController::class, 'store_billing_mahasiswa'])->withoutMiddleware(['throttle:api']);
Route::post('/billing-mahasiswa/detail', [BillingMhsController::class, 'get_detail_ukt'])->withoutMiddleware(['throttle:api']);
Route::patch('/billing-mahasiswa/update', [BillingMhsController::class, 'update_billing_ukt'])->withoutMiddleware(['throttle:api']);

// Manajemen E-Billing
Route::get('/billing', function () {
    $jenis_pembayaran = JenisBayar::select(['kode', 'keterangan', 'bank'])->orderBy('bank', 'ASC')->get()->toArray();
    return response()->json([
        'response' => true,
        'jenis-pembayaran' => $jenis_pembayaran,
    ]);
});

Route::post('/billing-detail', [BillingPaymentController::class, 'detail_billing'])->withoutMiddleware(['throttle:api']);
Route::post('/billing-repayment', [BillingPaymentController::class, 'repayment_billing'])->withoutMiddleware(['throttle:api']);
Route::post('/billing-store', [BillingPaymentController::class, 'store_billing'])->withoutMiddleware(['throttle:api']);
Route::patch('/billing-update', [BillingPaymentController::class, 'update_billing'])->withoutMiddleware(['throttle:api']);
Route::delete('/billing-delete', [BillingPaymentController::class, 'delete_billing'])->withoutMiddleware(['throttle:api']);
