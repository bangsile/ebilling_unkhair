<?php

namespace App\Http\Controllers;

use App\Models\TahunPembayaran;
use Illuminate\Http\Request;

class TahunPembayaranController extends Controller
{
    public function index()
    {
        // Code to retrieve and return a list of resources, e.g.:
        $tahunPembayaran = TahunPembayaran::first();
        return view('pages.tahun-pembayaran', compact('tahunPembayaran'));
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
