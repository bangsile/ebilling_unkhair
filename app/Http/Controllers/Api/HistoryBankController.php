<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\HistoryBankJob;
use App\Models\Billing;
use App\Models\BillingUkt;
use App\Models\HistoryBank;
use Illuminate\Http\Request;

class HistoryBankController extends Controller
{
    // public function auto_lunas(Request $request)
    // {
    //     $request->validate([
    //         'trx_id' => 'required',
    //         'no_va' => 'required'
    //     ]);
    //     // dd($request);
    //     $apiKey = $request->header('X-API-KEY');
    //     if (!$apiKey || $apiKey !== 'secret') {
    //         return response()->json([
    //             'response' => false,
    //             'message' => 'Unauthorized',
    //         ], status: 401);
    //     }
    //     try {
    //         $billing_pembayaran = Billing::where('trx_id', $request->trx_id)->where('no_va', $request->no_va)->first();
    //         $billing_ukt = BillingUkt::where('trx_id', $request->trx_id)->where('no_va', $request->no_va)->first();
    //         $billing = $billing_pembayaran ?? $billing_ukt;
    //         // dd($billing);
    //         if ($billing) {
    //             HistoryBank::updateOrCreate([
    //                 "trx_id" => $billing->trx_id,
    //                 "no_va" => $billing->no_va,
    //                 "nominal" => $billing->nominal,
    //                 "nama" => $billing->nama,
    //                 "metode_pembayaran" => $billing->nama_bank,
    //             ]);

    //             //auto lunas
    //             $billing->update([
    //                 "lunas" => 1
    //             ]);

    //             return response()->json([
    //                 'response' => true,
    //                 'message' => 'Transaksi Berhasil. History Pembayaran Tersimpan',
    //             ], status: 200);
    //         } else {
    //             return response()->json([
    //                 'response' => false,
    //                 'message' => 'Billing Tidak Ditemukan',
    //             ], status: 404);
    //         }
    //     } catch (\Throwable $th) {
    //         // dd($th);
    //         return response()->json([
    //             'response' => false,
    //             'message' => 'Internal Server Error',
    //         ], status: 500);
    //     }
    // }

    public function auto_lunas(Request $request)
    {
        try {
            $data = $request->all();
            $apiKey = $request->header('X-API-KEY');
            HistoryBankJob::dispatch($data, $apiKey);
    
            return response()->json([
                'response' => true,
                'message' => 'Sedang Memproses Transaksi',
            ], status: 200);
        } catch (\Throwable $th) {
            dd($th);
            return response()->json([
                'response' => false,
                'message' => 'Internal Server Error',
            ], status: 500);
        }
    }
}
