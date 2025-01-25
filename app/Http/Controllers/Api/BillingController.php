<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BillingResource;
use App\Models\BillingUkt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BillingController extends Controller
{
    public function store_billing_ukt(Request $request)
    {
        $apiKey = $request->header('X-API-KEY');

        if (!$apiKey || $apiKey !== 'secret') {
            return response()->json([
                'response' => false,
                'message' => 'Unauthorized: Invalid or missing API key.',
            ], 401);
        }
        try {
            $validator = Validator::make($request->all(), [
                // 'no_va' => 'required|string|unique:billing_ukts,no_va',
                // 'trx_id' => 'required|string|unique:billing_ukts,trx_id',
                'jenis_bayar' => ['required', 'string'],
                // 'jenis_bayar' => ['required', 'string', new JenisBayarExists],
                'nama_bank' => 'required|string',
                'nominal' => 'required|numeric',
                'nama' => 'required|string',
                // 'deskripsi' => 'required',
                // 'tgl_expire' => 'required',
                // 'detail' => 'array'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'response' => false,
                    'message' => $validator->errors(),
                ], 402);
            }

            return response()->json([
                'response' => true,
                'message' => "OK",
            ], 200);

            $billing = BillingUkt::create([
                'trx_id' => $request->trx_id,
                'no_va' => $request->no_va,
                'nama_bank' => $request->nama_bank,
                'jenis_bayar' => $request->jenis_bayar,
                'nominal' => $request->nominal,
                'tgl_expire' => $request->tgl_expire,
                'lunas' => false,
                'nama' => $request->nama,
                'no_identitas' => $request->no_identitas,
                'angkatan' => $request->angkatan,
                // 'tahun_akademik' => $request->tahun_akademik,
                'tahun_akademik' => '20242',
                'kode_prodi' => $request->kode_prodi,
                'nama_prodi' => $request->nama_prodi,
                'nama_fakultas' => $request->nama_fakultas,
                'kategori_ukt' => $request->kategori_ukt,
                'jalur' => $request->jalur,
                // 'detail' => $request->detail,
            ]);

            return new BillingResource(true, 'Berhasil Create Billing', $billing);
        } catch (\Throwable $th) {

            throw $th;
        }
    }
}
