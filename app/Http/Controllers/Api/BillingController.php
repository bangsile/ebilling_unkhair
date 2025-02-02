<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BillingResource;
use App\Models\BillingMahasiswa;
use App\Models\TahunPembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BillingController extends Controller
{
    public function get_detail_ukt(Request $request)
    {
        $apiKey = $request->header('X-API-KEY');

        if (!$apiKey || $apiKey !== 'secret') {
            return response()->json([
                'response' => false,
                'message' => 'Unauthorized: Invalid or missing API key.',
            ], 401);
        }
        $validator = Validator::make($request->all(), [
            'npm' => 'required',
            'tahun_akademik' => 'required|min:5|max:5',
        ], [
            'npm.required' => 'NPM wajib diisi',
            'tahun_akademik.required' => 'Tahun akademik wajib diisi',
            'tahun_akademik.min' => 'Tahun akademik harus terdiri dari 5 karakter',
            'tahun_akademik.max' => 'Tahun akademik harus terdiri dari 5 karakter',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'response' => false,
                'message' => $validator->errors(),
            ], 402);
        }

        $billing = BillingMahasiswa::where('no_identitas', $request->npm)->where('tahun_akademik', $request->tahun_akademik)->first();
        if (!$billing) {
            return new BillingResource(false, 'Billing Tidak Ditemukan', null);
        }
        return new BillingResource(true, 'Billing Ditemukan', $billing);
    }
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
            $tahun_pembayaran = TahunPembayaran::first();
            $billing = BillingMahasiswa::create([
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
                'tahun_akademik' => $tahun_pembayaran->tahun_akademik,
                'kode_prodi' => $request->kode_prodi,
                'nama_prodi' => $request->nama_prodi,
                'nama_fakultas' => $request->nama_fakultas,
                'kategori_ukt' => $request->kategori_ukt,
                'jalur' => $request->jalur,
                // 'detail' => $request->detail,
            ]);

            return new BillingResource(true, 'Berhasil Create Billing', $billing);
        } catch (\Throwable $th) {
            return response()->json([
                'response' => false,
                'message' => $th,
            ], 402);
            // throw $th;
        }
    }
    public function update_billing_ukt(Request $request)
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
                'npm' => 'required',
                'tahun_akademik' => 'required|min:5|max:5',
                'no_va' => 'required',
                'trx_id' => 'required',
                'nominal' => 'required|numeric',
                'tgl_expire' => 'required',
            ], [
                'npm.required' => 'NPM wajib diisi',
                'tahun_akademik.required' => 'Tahun akademik wajib diisi',
                'tahun_akademik.min' => 'Tahun akademik harus terdiri dari 5 karakter',
                'tahun_akademik.max' => 'Tahun akademik harus terdiri dari 5 karakter',
                'no_va.required' => 'No VA wajib diisi',
                'trx_id.required' => 'Trx ID wajib diisi',
                'nominal.required' => 'Nominal wajib diisi',
                'nominal.numeric' => 'Nominal harus berupa angka',
                'tgl_expire.required' => 'Tanggal Expire wajib diisi',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'response' => false,
                    'message' => $validator->errors(),
                ], 402);
            }
            $billing = BillingMahasiswa::where('no_identitas', $request->npm)->where('tahun_akademik', $request->tahun_akademik)->first()->update([
                'trx_id' => $request->trx_id,
                'no_va' => $request->no_va,
                'nominal' => $request->nominal,
                'tgl_expire' => $request->tgl_expire,
            ]);

            return new BillingResource(true, 'Berhasil Update Billing', $billing);
        } catch (\Throwable $th) {
            return response()->json([
                'response' => false,
                'message' => $th,
            ], 402);
            // throw $th;
        }
    }
}
