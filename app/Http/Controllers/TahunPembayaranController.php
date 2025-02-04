<?php

namespace App\Http\Controllers;

use App\Models\TahunPembayaran;
use Illuminate\Http\Request;

class TahunPembayaranController extends Controller
{
    public function index()
    {
        // Code to retrieve and return a list of resources, e.g.:
        $tahun_pembayaran = TahunPembayaran::first();
        return view('pages.tahun-pembayaran', compact('tahun_pembayaran'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'tahun_akademik' => 'required|min:5|max:5',
            'awal_pembayaran' => 'required',
            'akhir_pembayaran' => 'required',
        ], [
            'tahun_akademik.required' => 'Tahun akademik wajib diisi.',
            // 'tahun_akademik.numeric' => 'Tahun akademik harus berupa angka.',
            'tahun_akademik.min' => 'Tahun akademik harus terdiri dari 5 karakter.',
            'tahun_akademik.max' => 'Tahun akademik harus terdiri dari 5 karakter.',
            'awal_pembayaran.required' => 'Awal pembayaran wajib diisi.',
            'akhir_pembayaran.required' => 'Akhir pembayaran wajib diisi.',
        ]);

        $token = env('API_TOKEN_SIMAK', 'default_token');
        $response = json_decode(get_data(str_curl('https://simak.unkhair.ac.id/4pisim4k/kalender/cektahun', ['token' => $token, 'tahun' => $request->tahun_akademik])), TRUE);

        if ($response['status'] != '200') {
            return redirect()->route('tahun-pembayaran')->with('error', 'Tahun akademik ' . $request->tahun_akademik . ' tidak terdaftar di SIMAK, silahkan hubungi admin BAAKP.');
        }

        $tahunPembayaran = TahunPembayaran::first();
        if (!$tahunPembayaran) {
            $tahunPembayaran = new TahunPembayaran();
        }
        $tahunPembayaran->tahun_akademik = $request->tahun_akademik;
        $tahunPembayaran->awal_pembayaran = $request->awal_pembayaran;
        $tahunPembayaran->akhir_pembayaran = $request->akhir_pembayaran;
        $tahunPembayaran->save();
        return redirect()->route('tahun-pembayaran')->with('success', 'Berhasil Mengupdate Tahun Pembayaran');
    }
}
