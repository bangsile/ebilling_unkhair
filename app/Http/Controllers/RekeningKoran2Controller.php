<?php

namespace App\Http\Controllers;

use App\Exports\Billings;
use App\Exports\EbillingMahasiswa;

use App\Models\Billing;
use App\Models\BillingMahasiswa;
use App\Models\JenisBayar;
use App\Models\TahunPembayaran;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class RekeningKoran2Controller extends Controller
{
    public function index($jb_selected = NULL)
    {
        $jenisbayara = JenisBayar::orderBy('bank', 'ASC')->get();
        $data = [
            'judul' => 'Rekening Koran',
            'jenisbayara' => $jenisbayara,
            'jb_selected' => $jb_selected,
            'datatable' => []
        ];

        $datatable = [];
        if ($jb_selected && in_array($jb_selected, ['ukt', 'umb', 'ipi', 'pemkes'])) {
            $datatable = [
                'datatable' => [
                    'url' => route('rekeningkoran.ebilling-mahasiswa', $jb_selected),
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
        }

        if ($jb_selected && in_array($jb_selected, ['pmb', 'pmb-pasca', 'ppg'])) {
            $datatable = [
                'datatable' => [
                    'url' => route('rekeningkoran.manajemen_ebilling', $jb_selected),
                    'id_table' => 'id-datatable',
                    'columns' => [
                        ['data' => 'updated_at', 'name' => 'updated_at', 'orderable' => 'false', 'searchable' => 'false'],
                        ['data' => 'trx_id', 'name' => 'trx_id', 'orderable' => 'false', 'searchable' => 'true'],
                        ['data' => 'no_va', 'name' => 'no_va', 'orderable' => 'false', 'searchable' => 'true'],
                        ['data' => 'nama_bank', 'name' => 'nama_bank', 'orderable' => 'false', 'searchable' => 'true'],
                        ['data' => 'nominal', 'name' => 'nominal', 'orderable' => 'false', 'searchable' => 'false'],
                        ['data' => 'nama', 'name' => 'nama', 'orderable' => 'false', 'searchable' => 'false'],
                        ['data' => 'ket', 'name' => 'ket', 'orderable' => 'false', 'searchable' => 'false']
                    ]
                ]
            ];
        }

        if ($jb_selected) {
            $data = array_merge($data, $datatable);
        }

        return view('pages.rekening-koran.index2', $data);
    }


    public function ebilling_mahasiswa(Request $request, $jenisbayara = NULL)
    {
        if ($request->ajax()) {
            $tahun_akademik = TahunPembayaran::first();
            $billings = BillingMahasiswa::periode($tahun_akademik?->tahun_akademik)->jenisbayar($jenisbayara)
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
                    if ($request->get('tgl_transaksi')) {
                        $tgl_transaksi = explode('to', $request->get('tgl_transaksi'));
                        $tgl_awal = date('Y-m-d', strtotime(trim($tgl_transaksi[0])));
                        $tgl_akhir = date('Y-m-d', strtotime(trim($tgl_transaksi[1])));
                        $instance->whereBetween('updated_at', [$tgl_awal . " 00:00:00", $tgl_akhir . " 23:59:59"]);
                        // $filter = true;
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

    public function manajemen_ebilling(Request $request, $jenisbayara = NULL)
    {
        if ($request->ajax()) {
            $billings = Billing::pembayaran($jenisbayara)
                ->lunas(1)
                ->orderBy('updated_at', 'ASC');
            return DataTables::of($billings)
                ->addIndexColumn()
                ->editColumn('nominal', function ($billing) {
                    return formatRupiah($billing->nominal);
                })
                ->editColumn('updated_at', function ($billing) {
                    return tgl_indo($billing->updated_at);
                })
                ->editColumn('ket', function ($billing) {
                    return json_decode($billing->detail)->deskripsi ?? '-';
                })
                ->filter(function ($instance) use ($request) {
                    $filter = false;
                    if ($request->get('tgl_transaksi')) {
                        $tgl_transaksi = explode('to', $request->get('tgl_transaksi'));
                        $tgl_awal = date('Y-m-d', strtotime(trim($tgl_transaksi[0])));
                        $tgl_akhir = date('Y-m-d', strtotime(trim($tgl_transaksi[1])));
                        $instance->whereBetween('updated_at', [$tgl_awal . " 00:00:00", $tgl_akhir . " 23:59:59"]);
                        // $filter = true;
                    }

                    if (!empty($request->input('search.value'))) {
                        $instance->where(function ($w) use ($request) {
                            $search = $request->input('search.value');
                            $w->where('nama', 'LIKE', "%$search%")
                                ->orWhere('trx_id', 'LIKE', "%$search%")
                                ->orWhere('no_va', 'LIKE', "%$search%")
                                ->orWhere('nama_bank', 'LIKE', "%$search%");
                        });
                    }
                })
                ->rawColumns(['nominal', 'updated_at', 'ket'])
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

        if ($jenisbayar && in_array($jenisbayar, ['ukt', 'umb', 'ipi', 'pemkes'])) {

            $nama_file = time() . ' - Export Ebilling Mahasiswa (' . $jenisbayar . ').xlsx';
            $params = [
                'tgl_mulai' => trim($tanggal[0]),
                'tgl_akhir' => trim($tanggal[1]),
                'jenisbayar' => $jenisbayar
            ];

            return Excel::download(new EbillingMahasiswa($params), $nama_file);
        }

        if ($jenisbayar && in_array($jenisbayar, ['pmb', 'pmb-pasca', 'ppg'])) {

            $nama_file = time() . ' - Export Ebilling (' . $jenisbayar . ').xlsx';
            $params = [
                'tgl_mulai' => trim($tanggal[0]),
                'tgl_akhir' => trim($tanggal[1]),
                'jenisbayar' => $jenisbayar
            ];

            return Excel::download(new Billings($params), $nama_file);
        }
    }
}
