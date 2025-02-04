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
    public function store_billing_mahasiswa(Request $request)
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
                'no_identitas' => 'required',
                'jenis_bayar' => ['required', 'string', 'exists:jenis_bayars,kode'],
                'nama_bank' => 'required|string',
                'nominal' => 'required|numeric',
                'nama' => 'required|string',
                'tahun_akademik' => 'required|min:5|max:5',
            ], [
                'jenis_bayar.exists' => 'Jenis bayar tidak terdaftar'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'response' => false,
                    'message' => $validator->errors(),
                ], 402);
            }
            // $tahun_pembayaran = TahunPembayaran::first();
            $billing = BillingMahasiswa::where('no_identitas', $request->no_identitas)->where('jenis_bayar', $request->jenis_bayar)->where('tahun_akademik', $request->tahun_akademik)->first();
            if ($billing) {
                $billing->update([
                    'trx_id' =>  $request->trx_id ?? $billing->trx_id ?? null,
                    'no_va' => $request->no_va ?? $billing->no_va ?? null,
                    'nama_bank' => $request->nama_bank,
                    'nominal' => $request->nominal,
                    'tgl_expire' => $request->tgl_expire,
                    'lunas' => false,
                    'nama' => $request->nama,
                    'angkatan' => $request->angkatan,
                    'kode_prodi' => $request->kode_prodi,
                    'nama_prodi' => $request->nama_prodi,
                    'nama_fakultas' => $request->nama_fakultas,
                    'kategori_ukt' => $request->kategori_ukt,
                    'jalur' => $request->jalur,
                    'no_identitas' => $request->no_identitas,
                    'jenis_bayar' => $request->jenis_bayar,
                    'tahun_akademik' => $request->tahun_akademik,
                ]);
                return new BillingResource(true, 'Berhasil Update Billing', $billing);
            }

            $billing = BillingMahasiswa::create([
                'nama_bank' => $request->nama_bank,
                'nominal' => $request->nominal,
                'tgl_expire' => $request->tgl_expire,
                'lunas' => false,
                'nama' => $request->nama,
                'angkatan' => $request->angkatan,
                'kode_prodi' => $request->kode_prodi,
                'nama_prodi' => $request->nama_prodi,
                'nama_fakultas' => $request->nama_fakultas,
                'kategori_ukt' => $request->kategori_ukt,
                'jalur' => $request->jalur,
                'no_identitas' => $request->no_identitas,
                'jenis_bayar' => $request->jenis_bayar,
                'tahun_akademik' => $request->tahun_akademik,
            ]);

            return new BillingResource(true, 'Berhasil Create Billing', $billing);
        } catch (\Throwable $th) {
            return response()->json([
                'response' => false,
                'message' => $th,
            ], 402);
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
                'jenis_bayar' => 'required'
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
            $billing = BillingMahasiswa::where('no_identitas', $request->npm)
                ->where('tahun_akademik', $request->tahun_akademik)
                ->where('jenis_bayar', $request->jenis_bayar)->first();
            if (!$billing) {
                return new BillingResource(false, 'Billing Tidak Ditemukan', null);
            }

            $value = [
                'trx_id' => $request->trx_id,
                'no_va' => $request->no_va,
                'nominal' => $request->nominal,
                'tgl_expire' => $request->tgl_expire,
            ];

            if ($request->nama_bank) {
                $value += ['nama_bank' => $request->nama_bank];
            }

            $billing->update($value);

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
