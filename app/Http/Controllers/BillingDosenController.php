<?php

namespace App\Http\Controllers;

use App\Models\Billing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class BillingDosenController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $user = Auth::user();
            if ($user->hasRole('dosen')) {
                $billings = Billing::select(['*'])->whereHas('dosenHasBilling', function ($query) use ($user) {
                    $query->where('user_dosen_id', $user->id);
                })
                    ->pembayaran('fee-dosen')
                    ->orderBy('created_at', 'DESC');
            } else {
                $billings = Billing::select(['*'])
                    ->pembayaran('fee-dosen')
                    ->orderBy('created_at', 'DESC');
            }
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
                ->editColumn('deskripsi', function ($billing) {
                    return json_decode($billing->detail)->deskripsi ?? '-';
                })
                ->addColumn('action', function ($billing) {

                    $cetak = ($billing->lunas || empty($billing->trx_id)) ? '<button type="button" class="btn btn-sm btn-info disabled"><i class="fas fa-print"></i></button>' :
                        '<a href="' . route('export.tagihanDosen', $billing->trx_id) . '" target="_blank" class="btn btn-sm btn-info"><i class="fas fa-print"></i></a>';

                    return $cetak;
                })
                ->filter(function ($instance) use ($request) {
                    if (!empty($request->input('search.value'))) {
                        $instance->where(function ($w) use ($request) {
                            $search = $request->input('search.value');
                            $w->where('nama', 'LIKE', "%$search%")
                                ->orWhere('no_va', 'LIKE', "%$search%");
                        });
                    }
                })
                ->rawColumns(['nominal', 'status', 'deskripsi', 'action'])
                ->make(true);
        }

        $data = [
            'judul' => 'My Billing',
            'datatable' => [
                'url' => route('billing-dosen.index'),
                'id_table' => 'id-datatable',
                'columns' => [
                    ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'orderable' => 'false', 'searchable' => 'false'],
                    ['data' => 'no_va', 'name' => 'no_va', 'orderable' => 'true', 'searchable' => 'false'],
                    ['data' => 'nama', 'name' => 'nama', 'orderable' => 'true', 'searchable' => 'true'],
                    ['data' => 'nama_bank', 'name' => 'nama_bank', 'orderable' => 'true', 'searchable' => 'false'],
                    ['data' => 'nominal', 'name' => 'nominal', 'orderable' => 'false', 'searchable' => 'false'],
                    ['data' => 'deskripsi', 'name' => 'deskripsi', 'orderable' => 'false', 'searchable' => 'false'],
                    ['data' => 'status', 'name' => 'status', 'orderable' => 'false', 'searchable' => 'false'],
                    ['data' => 'action', 'name' => 'action', 'orderable' => 'false', 'searchable' => 'false']
                ]
            ]
        ];
        return view('pages.billing.dosen.index', $data);
    }

    public function create()
    {
        $data = [
            'judul' => 'Buat Billing Dosen',
        ];
        return view('pages.billing.dosen.create', $data);
    }
}
