<?php

namespace App\Http\Controllers;

use App\Exports\EbillingMahasiswa;
use App\Models\BillingMahasiswa;
use App\Models\JenisBayar;
use App\Models\TahunPembayaran;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class RekeningKoranController extends Controller
{
    public function index()
    {
        $jenisbayara = JenisBayar::orderBy('bank', 'ASC')->get();
        $data = [
            'judul' => 'Rekening Koran - E-Billing Mahasiswa',
            'jenisbayara' => $jenisbayara,
            'datatable' => [
                'url' => route('rekening-koran.tampil'),
                'id_table' => 'id-datatable',
                'columns' => [
                    ['data' => 'updated_at', 'name' => 'updated_at', 'orderable' => 'false', 'searchable' => 'false'],
                    ['data' => 'trx_id', 'name' => 'trx_id', 'orderable' => 'false', 'searchable' => 'true'],
                    ['data' => 'no_va', 'name' => 'no_va', 'orderable' => 'false', 'searchable' => 'true'],
                    ['data' => 'nama_bank', 'name' => 'nama_bank', 'orderable' => 'false', 'searchable' => 'true'],
                    ['data' => 'nominal', 'name' => 'nominal', 'orderable' => 'false', 'searchable' => 'false'],
                    ['data' => 'kategori_ukt', 'name' => 'kategori_ukt', 'orderable' => 'false', 'searchable' => 'true'],
                    ['data' => 'mahasiswa', 'name' => 'mahasiswa', 'orderable' => 'false', 'searchable' => 'false'],
                    ['data' => 'angkatan', 'name' => 'angkatan', 'orderable' => 'false', 'searchable' => 'false'],
                    ['data' => 'prodi', 'name' => 'prodi', 'orderable' => 'false', 'searchable' => 'false'],
                    ['data' => 'ket', 'name' => 'ket', 'orderable' => 'false', 'searchable' => 'false']
                ]
            ]
        ];
        return view('pages.rekening-koran.index', $data);
    }

    public function tampil(Request $request)
    {
        if ($request->ajax()) {
            $tahun_akademik = TahunPembayaran::first();
            $billings = BillingMahasiswa::periode($tahun_akademik?->tahun_akademik)
                ->lunas(1)
                ->orderBy('updated_at', 'ASC')
                ->orderBy('nama_prodi', 'ASC');
            return DataTables::of($billings)
                ->addIndexColumn()
                ->editColumn('nominal', function ($billing) {
                    return formatRupiah($billing->nominal);
                })
                ->editColumn('updated_at', function ($billing) {
                    return tgl_indo($billing->updated_at);
                })
                ->editColumn('prodi', function ($billing) {
                    return $billing->kode_prodi . ' - ' . $billing->nama_prodi;
                })
                ->editColumn('mahasiswa', function ($billing) {
                    return $billing->nama . '<br>NPM: ' . $billing->no_identitas;
                })
                ->editColumn('ket', function ($billing) {
                    if ($billing->jenis_bayar == 'ukt') {
                        return 'Pembayaran UKT ' . $billing->tahun_akademik;
                    }

                    if (in_array($billing->jenis_bayar, ['umb', 'ipi', 'pemkes'])) {
                        $ket = 'Pembayaran UKT ' . $billing->tahun_akademik;
                        $ket .= '<br>Jalur ' . $billing->jalur;
                        return $ket;
                    }
                })
                ->filter(function ($instance) use ($request) {
                    $filter = false;
                    if ($request->get('jenisbayar')) {
                        $instance->where('jenis_bayar', '=', $request->get('jenisbayar'));
                        $filter = true;
                    }
                    if ($request->get('tgl_transaksi')) {
                        $tgl_transaksi = explode('to', $request->get('tgl_transaksi'));
                        $tgl_awal = date('Y-m-d', strtotime(trim($tgl_transaksi[0])));
                        $tgl_akhir = date('Y-m-d', strtotime(trim($tgl_transaksi[1])));
                        $instance->whereBetween('updated_at', [$tgl_awal . " 00:00:00", $tgl_akhir . " 23:59:59"]);
                        // $filter = true;
                    }

                    if (!$filter) {
                        $instance->where('jenis_bayar', '---');
                    }

                    if (!empty($request->input('search.value'))) {
                        $instance->where(function ($w) use ($request) {
                            $search = $request->input('search.value');
                            $w->where('no_identitas', 'LIKE', "%$search%")
                                ->orWhere('nama', 'LIKE', "%$search%")
                                ->orWhere('trx_id', 'LIKE', "%$search%")
                                ->orWhere('no_va', 'LIKE', "%$search%")
                                ->orWhere('nama_bank', 'LIKE', "%$search%")
                                ->orWhere('kategori_ukt', 'LIKE', "%$search%");
                        });
                    }
                })
                ->rawColumns(['nominal', 'updated_at', 'prodi', 'mahasiswa', 'ket'])
                ->make(true);
        }
    }

    public function excel(Request $request)
    {
        // dd($request);

        if (!trim($request->date) || trim($request->date) == 'to') {
            abort(500);
        }
        if (!trim($request->jb)) {
            abort(500);
        }



        $tanggal = explode('to', $request->date);
        $jenisbayar = $request->jb;

        $nama_file = time() . ' - Export Ebilling Mahasiswa.xlsx';
        $params = [
            'tgl_mulai' => trim($tanggal[0]),
            'tgl_akhir' => trim($tanggal[1]),
            'jenisbayar' => $jenisbayar
        ];

        return Excel::download(new EbillingMahasiswa($params), $nama_file);
    }
}
