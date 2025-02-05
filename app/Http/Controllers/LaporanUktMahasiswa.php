<?php

namespace App\Http\Controllers;

use App\Models\BillingMahasiswa;
use App\Models\Fakultas;
use App\Models\Prodi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanUktMahasiswa extends Controller
{
    public function index(Request $request)
    {
        $result = [];
        if ($request->isMethod('post')) {
            $prodis = Prodi::perfakultas($request->fakultas)->orderBy('nm_prodi', 'ASC')->get();
            // dd($prodis);
            foreach ($prodis as $prodi) {
                $billingmhs = BillingMahasiswa::jenisbayar('ukt')
                    ->periode($request->periode)
                    ->angkatan($request->angkatan)
                    ->prodi($prodi->kd_prodi)
                    ->select(['kategori_ukt', 'nominal', DB::raw('COUNT(kategori_ukt) AS jml_mahasiswa')])
                    ->groupBy('kategori_ukt')
                    ->orderBy('kategori_ukt', 'ASC')
                    ->get();

                $result[] = [
                    'prodi' => $prodi->kd_prodi . ' - ' . $prodi->nm_prodi,
                    'angkatan' => $request->angkatan,
                    'jml_data' => $billingmhs->count() + 1,
                    'data' => $billingmhs
                ];
            }
        }

        $fakultas = Fakultas::orderBy('nama_fakultas', 'ASC')->get();
        $data = [
            'judul' => 'Laporan Billing UKT',
            'fakultas' => $fakultas,
            'result' => $result
        ];
        return view('pages.laporan.billing-ukt', $data);
    }
}
