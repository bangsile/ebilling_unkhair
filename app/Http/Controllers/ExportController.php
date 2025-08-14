<?php

namespace App\Http\Controllers;

use App\Models\BillingMahasiswa;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ExportController extends Controller
{
    public function tagihanBillingMhs(Request $request)
    {
        $trx_id = $request->route('id');
        $billing = BillingMahasiswa::where('trx_id',$trx_id)->with('jenis_pembayaran')->firstOrFail();
        // dd($billing);
        $data = [
            'tahun_akademik' => $billing->tahun_akademik,
            'bank' => $billing->nama_bank,
            'trx_id' => $billing->trx_id,
            'no_va' => $billing->no_va,
            'nama' => $billing->nama,
            'prodi' => $billing->kode_prodi . ' - ' . $billing->nama_prodi,
            'nominal' => formatRupiah($billing->nominal),
            'keterangan' => $billing->jenis_pembayaran->keterangan,
            'expire' => \Carbon\Carbon::parse($billing->tgl_expire)->translatedFormat('d F Y')
        ];

        $pdf = Pdf::loadView('exports.tagihan', $data);

        // Download file PDF
        return $pdf->stream('laporan.pdf');
    }
}
