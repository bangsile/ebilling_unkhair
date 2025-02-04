<?php

namespace App\Http\Controllers;

use App\Models\BillingMahasiswa;
use App\Models\TahunPembayaran;
use App\Services\EcollService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class BillingMhsController extends Controller
{
    protected $ecollService;
    public function __construct(EcollService $ecollService)
    {
        $this->ecollService = $ecollService;
    }

    public function billing_ukt(Request $request)
    {
        $tahun_akademik = TahunPembayaran::first();

        if ($request->ajax()) {
            $billings = BillingMahasiswa::select(['id', 'trx_id', 'no_va', 'no_identitas', 'nama', 'angkatan', 'kategori_ukt', 'nama_prodi', 'nominal', 'tgl_expire', 'lunas'])
                ->where('tahun_akademik', $tahun_akademik?->tahun_akademik)->where('jenis_bayar', 'ukt');
            return DataTables::of($billings)
                ->addIndexColumn()
                ->editColumn('nominal', function ($billing) {
                    return formatRupiah($billing->nominal);
                })
                ->editColumn('status', function ($billing) {
                    if ($billing->tgl_expire) {
                        if ($billing->lunas) {
                            return '<span class="badge badge-success" style="font-size: 1rem">Lunas</span>';
                        } elseif ($billing->tgl_expire < now()) {
                            return '<span class="badge badge-danger" style="font-size: 1rem">Expired</span>';
                        } else {
                            return '<span class="badge badge-warning" style="font-size: 1rem">Pending</span>';
                        }
                    }
                    return '';
                })
                ->addColumn('action', function ($billing) {
                    $editButton = $billing->lunas ? '<button type="button" class="btn btn-sm btn-warning disabled"><i class="fas fa-edit"></i></button>' :
                        '<a href="' . route('billing.ukt.edit', $billing->id) . '" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>';
                    $printButton = $billing->lunas ? '<button type="button" class="btn btn-sm btn-info disabled"><i class="fas fa-print"></i></button>' :
                        '<a href="/" class="btn btn-sm btn-info"><i class="fas fa-print"></i></a>';
                    $setLunasButton = (!$billing->trx_id || $billing->lunas) ?
                        '<button type="button" class="btn btn-sm btn-success disabled">Set Lunas</button>' :
                        '<form id="lunas-form-' . $billing->id . '" action="' . route('billing.ukt.lunas') . '" method="POST" style="display: inline;">' .
                        csrf_field() .
                        '<input type="hidden" name="id" value="' . $billing->id . '">' .
                        '<button type="button" class="btn btn-sm btn-success" onclick="confirmLunas(\'' . $billing->id . '\')">Set Lunas</button>' .
                        '</form>';

                    if (Auth::check() && Auth::user()->hasRole(['developper', 'admin'])) {
                        return $editButton . ' ' . $printButton . ' ' . $setLunasButton;
                    }
                    return $editButton . ' ' . $printButton;
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }
        $data = [
            'judul' => 'Billing UKT',
            'datatable' => [
                'url' => route('billing.ukt'),
                'id_table' => 'id-datatable',
                'columns' => [
                    ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'orderable' => 'false', 'searchable' => 'false'],
                    ['data' => 'no_identitas', 'name' => 'no_identitas', 'orderable' => 'false', 'searchable' => 'true'],
                    ['data' => 'nama', 'name' => 'nama', 'orderable' => 'true', 'searchable' => 'true'],
                    ['data' => 'angkatan', 'name' => 'angkatan', 'orderable' => 'true', 'searchable' => 'true'],
                    ['data' => 'kategori_ukt', 'name' => 'kategori_ukt', 'orderable' => 'true', 'searchable' => 'false'],
                    ['data' => 'nama_prodi', 'name' => 'nama_prodi', 'orderable' => 'false', 'searchable' => 'false'],
                    ['data' => 'nominal', 'name' => 'nominal', 'orderable' => 'false', 'searchable' => 'false'],
                    ['data' => 'status', 'name' => 'status', 'orderable' => 'false', 'searchable' => 'false'],
                    ['data' => 'action', 'name' => 'action', 'orderable' => 'false', 'searchable' => 'false']
                ]
            ]
        ];
        return view('pages.billing.billing-ukt', $data);
    }



    public function edit_billing_ukt(Request $request)
    {
        $id = $request->route('id');
        $billing = BillingMahasiswa::find($id);
        return view('pages.billing.edit-billing-ukt', compact('billing'));
    }

    public function update_billing_ukt(Request $request)
    {
        $id = $request->route('id');
        $billing = BillingMahasiswa::find($id);

        if (!$billing->trx_id || !$billing->no_va) {
            $billing->update([
                'nominal' => $request->nominal
            ]);
            return redirect()->route('billing.ukt')->with('success', 'Berhasil Update Billing');
        }

        $data = [
            'trx_id' => $billing->trx_id,
            'no_va' => $billing->no_va,
            'nominal' => $request->nominal,
            'tgl_expire' => $billing->tgl_expire,
            'nama' => $billing->nama,
        ];

        $response = $this->ecollService->updateVaBNI($data);
        if (!$response['response']) {
            return back()->withErrors(['billing' => $response['message']]);
        }

        $billing->update([
            'nominal' => $request->nominal
        ]);
        return redirect()->route('billing.ukt')->with('success', 'Berhasil Update Billing');
    }

    public function set_lunas_billing(Request $request)
    {
        $id = $request->id;
        $billing = BillingMahasiswa::find($id);
        $billing->update([
            'lunas' => 1
        ]);
        return redirect()->route('billing.ukt')->with('success', 'Berhasil Update Billing Lunas');
    }


    // billing umb
    public function billing_umb(Request $request)
    {
        $tahun_akademik = TahunPembayaran::first();
        if ($request->ajax()) {
            $billings = BillingMahasiswa::where('tahun_akademik', $tahun_akademik?->tahun_akademik)->where('jenis_bayar', 'umb');
            return DataTables::of($billings)
                ->addIndexColumn()
                ->editColumn('nominal', function ($billing) {
                    return formatRupiah($billing->nominal);
                })
                ->editColumn('status', function ($billing) {
                    if ($billing->tgl_expire) {
                        if ($billing->lunas) {
                            return '<span class="badge badge-success" style="font-size: 1rem">Lunas</span>';
                        } elseif ($billing->tgl_expire < now()) {
                            return '<span class="badge badge-danger" style="font-size: 1rem">Expired</span>';
                        } else {
                            return '<span class="badge badge-warning" style="font-size: 1rem">Pending</span>';
                        }
                    }
                    return '';
                })
                ->editColumn('created_at', function ($billing) {
                    return tgl_indo($billing->created_at);
                })
                ->rawColumns(['status', 'nominal', 'created_at'])
                ->make(true);
        }
        $data = [
            'judul' => 'Billing UMB',
            'datatable' => [
                'url' => route('billing.umb'),
                'id_table' => 'id-datatable',
                'columns' => [
                    ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'orderable' => 'false', 'searchable' => 'false'],
                    ['data' => 'no_identitas', 'name' => 'no_identitas', 'orderable' => 'false', 'searchable' => 'true'],
                    ['data' => 'nama', 'name' => 'nama', 'orderable' => 'true', 'searchable' => 'true'],
                    ['data' => 'jalur', 'name' => 'jalur', 'orderable' => 'true', 'searchable' => 'true'],
                    ['data' => 'kategori_ukt', 'name' => 'kategori_ukt', 'orderable' => 'true', 'searchable' => 'false'],
                    ['data' => 'nama_prodi', 'name' => 'nama_prodi', 'orderable' => 'false', 'searchable' => 'false'],
                    ['data' => 'nominal', 'name' => 'nominal', 'orderable' => 'false', 'searchable' => 'false'],
                    ['data' => 'status', 'name' => 'status', 'orderable' => 'false', 'searchable' => 'false'],
                    ['data' => 'created_at', 'name' => 'created_at', 'orderable' => 'false', 'searchable' => 'false'],
                ]
            ]
        ];

        return view('pages.billing.billing-umb', $data);
    }
    public function billing_ipi(Request $request)
    {
        $tahun_akademik = TahunPembayaran::first();
        if ($request->ajax()) {
            $billings = BillingMahasiswa::where('tahun_akademik', $tahun_akademik?->tahun_akademik)->where('jenis_bayar', 'ipi');
            return DataTables::of($billings)
                ->addIndexColumn()
                ->editColumn('nominal', function ($billing) {
                    return formatRupiah($billing->nominal);
                })
                ->editColumn('status', function ($billing) {
                    if ($billing->tgl_expire) {
                        if ($billing->lunas) {
                            return '<span class="badge badge-success" style="font-size: 1rem">Lunas</span>';
                        } elseif ($billing->tgl_expire < now()) {
                            return '<span class="badge badge-danger" style="font-size: 1rem">Expired</span>';
                        } else {
                            return '<span class="badge badge-warning" style="font-size: 1rem">Pending</span>';
                        }
                    }
                    return '';
                })
                ->editColumn('created_at', function ($billing) {
                    return tgl_indo($billing->created_at);
                })
                ->rawColumns(['status', 'nominal', 'created_at'])
                ->make(true);
        }
        $data = [
            'judul' => 'Billing IPI',
            'datatable' => [
                'url' => route('billing.ipi'),
                'id_table' => 'id-datatable',
                'columns' => [
                    ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'orderable' => 'false', 'searchable' => 'false'],
                    ['data' => 'no_identitas', 'name' => 'no_identitas', 'orderable' => 'false', 'searchable' => 'true'],
                    ['data' => 'nama', 'name' => 'nama', 'orderable' => 'true', 'searchable' => 'true'],
                    ['data' => 'jalur', 'name' => 'jalur', 'orderable' => 'true', 'searchable' => 'true'],
                    ['data' => 'nama_prodi', 'name' => 'nama_prodi', 'orderable' => 'false', 'searchable' => 'false'],
                    ['data' => 'nominal', 'name' => 'nominal', 'orderable' => 'false', 'searchable' => 'false'],
                    ['data' => 'status', 'name' => 'status', 'orderable' => 'false', 'searchable' => 'false'],
                    ['data' => 'created_at', 'name' => 'created_at', 'orderable' => 'false', 'searchable' => 'false'],
                ]
            ]
        ];
        return view('pages.billing.billing-ipi', $data);
    }
    public function billing_pemkes(Request $request)
    {
        $tahun_akademik = TahunPembayaran::first();
        if ($request->ajax()) {
            $billings = BillingMahasiswa::where('tahun_akademik', $tahun_akademik?->tahun_akademik)->where('jenis_bayar', 'pemkes');
            return DataTables::of($billings)
                ->addIndexColumn()
                ->editColumn('nominal', function ($billing) {
                    return formatRupiah($billing->nominal);
                })
                ->editColumn('status', function ($billing) {
                    if ($billing->tgl_expire) {
                        if ($billing->lunas) {
                            return '<span class="badge badge-success" style="font-size: 1rem">Lunas</span>';
                        } elseif ($billing->tgl_expire < now()) {
                            return '<span class="badge badge-danger" style="font-size: 1rem">Expired</span>';
                        } else {
                            return '<span class="badge badge-warning" style="font-size: 1rem">Pending</span>';
                        }
                    }
                    return '';
                })
                ->editColumn('created_at', function ($billing) {
                    return tgl_indo($billing->created_at);
                })
                ->rawColumns(['status', 'nominal', 'created_at'])
                ->make(true);
        }
        $data = [
            'judul' => 'Billing PEMKES',
            'datatable' => [
                'url' => route('billing.pemkes'),
                'id_table' => 'id-datatable',
                'columns' => [
                    ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'orderable' => 'false', 'searchable' => 'false'],
                    ['data' => 'no_identitas', 'name' => 'no_identitas', 'orderable' => 'false', 'searchable' => 'true'],
                    ['data' => 'nama', 'name' => 'nama', 'orderable' => 'true', 'searchable' => 'true'],
                    ['data' => 'jalur', 'name' => 'jalur', 'orderable' => 'true', 'searchable' => 'true'],
                    ['data' => 'nama_prodi', 'name' => 'nama_prodi', 'orderable' => 'false', 'searchable' => 'false'],
                    ['data' => 'nominal', 'name' => 'nominal', 'orderable' => 'false', 'searchable' => 'false'],
                    ['data' => 'status', 'name' => 'status', 'orderable' => 'false', 'searchable' => 'false'],
                    ['data' => 'created_at', 'name' => 'created_at', 'orderable' => 'false', 'searchable' => 'false'],
                ]
            ]
        ];
        return view('pages.billing.billing-pemkes', $data);
    }
}
