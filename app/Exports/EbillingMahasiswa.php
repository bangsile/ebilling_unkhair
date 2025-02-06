<?php

namespace App\Exports;

use App\Models\BillingMahasiswa;
use App\Models\TahunPembayaran;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class EbillingMahasiswa implements FromView
{
    protected $tgl_mulai;
    protected $tgl_akhir;
    protected $jenisbayar;

    public function __construct(array $params)
    {
        $this->tgl_mulai = $params['tgl_mulai'];
        $this->tgl_akhir = $params['tgl_akhir'];
        $this->jenisbayar = $params['jenisbayar'];
    }

    public function view(): View
    {
        $tahun_akademik = TahunPembayaran::first();
        $billings = BillingMahasiswa::periode($tahun_akademik?->tahun_akademik)
            ->lunas(1)
            ->jenisbayar($this->jenisbayar)
            ->whereBetween('updated_at', [$this->tgl_mulai . " 00:00:00", $this->tgl_akhir . " 23:59:59"])
            ->orderBy('updated_at', 'ASC')
            ->orderBy('nama_prodi', 'ASC')
            ->get();
        return view('exports.ebilling-mahasiswa', [
            'tgl_transaksi' => str_range_tanggal($this->tgl_mulai, $this->tgl_akhir),
            'result' => $billings
        ]);
    }
}
